<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with([
            'productDetails' => function ($q) {
                $q->where('is_active', true)
                  ->select('id', 'product_id', 'room_name', 'price', 'status', 'available_rooms', 'images');
            }
        ])
        ->where('is_published', true)
        ->select('id', 'name', 'address','category', 'distance_to_kariadi', 'whatsapp', 'google_maps_link', 'facilities', 'description', 'images');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('productDetails', function ($detailQ) use ($search) {
                      $detailQ->where('room_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['kosong', 'isi'])) {
            $query->whereHas('productDetails', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Price range filter
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->whereHas('productDetails', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        // Distance range filter
        if ($request->has('min_distance') && is_numeric($request->min_distance)) {
            $query->where('distance_to_kariadi', '>=', $request->min_distance);
        }

        if ($request->has('max_distance') && is_numeric($request->max_distance)) {
            $query->where('distance_to_kariadi', '<=', $request->max_distance);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at'); // Default sort by created_at (terbaru)
        $sortOrder = $request->get('sort_order', 'desc'); // Default descending
        $allowedSorts = ['name', 'distance_to_kariadi', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Prioritize products with 'kosong' status first, then by the selected sort
        $query->orderByRaw("CASE WHEN EXISTS (SELECT 1 FROM product_details pd WHERE pd.product_id = products.id AND pd.is_active = 1 AND pd.status = 'kosong') THEN 0 ELSE 1 END");

        $perPage = min($request->get('per_page', 9), 9); // Maximal 9 items per page
        $products = $query->paginate($perPage);

        // Transform the data for frontend efficiency - simplified format
        $products->getCollection()->transform(function ($product) {
            // Get thumbnail (first image)
            $images = json_decode($product->images, true) ?? [];
            $thumbnail = !empty($images) ? Storage::url('products/' . $images[0]) : null;

            // Get starting price (lowest price from active product details)
            $startingPrice = $product->productDetails->min('price');

            // Calculate total available rooms and status from vacant rooms only
            $vacantDetails = $product->productDetails->where('status', 'kosong');
            $totalAvailable = $vacantDetails->sum('available_rooms');
            $totalRooms = $product->productDetails->count();
            if ($totalAvailable == 0) {
                $status = 'habis';
                $roomAvailable = 0;
            } else {
                $status = 'tersedia';
                $roomAvailable = $totalAvailable;
            }

            // Get facilities preview (first 3-4 key facilities)
            $facilities = json_decode($product->facilities, true) ?? [];
            $facilitiesPreview = [];
            if (is_array($facilities)) {
                foreach ($facilities as $facilityGroup) {
                    if (isset($facilityGroup['items']) && is_array($facilityGroup['items'])) {
                        $facilitiesPreview = array_merge($facilitiesPreview, array_slice($facilityGroup['items'], 0, 2));
                        if (count($facilitiesPreview) >= 4) break;
                    }
                }
                $facilitiesPreview = array_slice($facilitiesPreview, 0, 4);
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'address' => $product->address,
                'distance_to_kariadi' => (float) $product->distance_to_kariadi,
                'latitude' => $product->latitude,
                'longitude' => $product->longitude,
                'thumbnail' => $thumbnail,
                'starting_price' => (int) $startingPrice,
                'whatsapp' => $product->whatsapp,
                'google_maps_link' => $product->google_maps_link,
                'facilities_preview' => $facilitiesPreview,
                'status' => $status,
                'room_available' => $roomAvailable,
                'total_rooms' => $totalRooms,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ]);
    }

    /**
     * Flexible search endpoint that returns detailed product information
     */
    public function search(Request $request)
    {
        $query = Product::with([
            'productDetails' => function ($q) {
                $q->where('is_active', true)
                  ->select('id', 'product_id', 'room_name', 'price', 'status', 'available_rooms', 'images', 'facilities', 'description');
            }
        ])
        ->where('is_published', true)
        ->whereHas('productDetails', function ($q) {
            $q->where('is_active', true)->whereNotNull('price');
        })
        ->select('id', 'name', 'address', 'distance_to_kariadi', 'whatsapp', 'google_maps_link', 'facilities', 'description', 'images', 'category', 'created_at');

        // Flexible search functionality - search in multiple fields
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%")
                  ->orWhereHas('productDetails', function ($detailQ) use ($searchTerm) {
                      $detailQ->where('room_name', 'like', "%{$searchTerm}%")
                              ->orWhere('description', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Advanced filters
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Status filter (room availability)
        if ($request->has('status') && in_array($request->status, ['kosong', 'isi', 'tersedia', 'habis'])) {
            if ($request->status === 'tersedia') {
                $query->whereHas('productDetails', function ($q) {
                    $q->where('is_active', true)->where('available_rooms', '>', 0);
                });
            } elseif ($request->status === 'habis') {
                $query->whereDoesntHave('productDetails', function ($q) {
                    $q->where('is_active', true)->where('available_rooms', '>', 0);
                });
            } else {
                $query->whereHas('productDetails', function ($q) use ($request) {
                    $q->where('status', $request->status);
                });
            }
        }

        // Price range filter
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->whereHas('productDetails', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }
        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->whereHas('productDetails', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Distance range filter
        if ($request->has('min_distance') && is_numeric($request->min_distance)) {
            $query->where('distance_to_kariadi', '>=', $request->min_distance);
        }
        if ($request->has('max_distance') && is_numeric($request->max_distance)) {
            $query->where('distance_to_kariadi', '<=', $request->max_distance);
        }

        // Facilities filter
        if ($request->has('facilities') && !empty($request->facilities)) {
            $facilities = is_array($request->facilities) ? $request->facilities : [$request->facilities];
            foreach ($facilities as $facility) {
                $query->where('facilities', 'like', "%{$facility}%");
            }
        }

        // Sorting options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['name', 'distance_to_kariadi', 'created_at'];

        // Special sorting for price
        if ($sortBy === 'price') {
            $query->leftJoin('product_details', function ($join) {
                $join->on('products.id', '=', 'product_details.product_id')
                     ->where('product_details.is_active', '=', true);
            })
            ->select('products.*', DB::raw('MIN(product_details.price) as min_price'))
            ->groupBy('products.id')
            ->orderBy('min_price', $sortOrder);
        } elseif (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Prioritize products with available rooms
        $query->orderByRaw("CASE WHEN EXISTS (SELECT 1 FROM product_details pd WHERE pd.product_id = products.id AND pd.is_active = 1 AND pd.available_rooms > 0) THEN 0 ELSE 1 END");

        $perPage = min($request->get('per_page', 12), 20); // Allow up to 20 items per page for search
        $products = $query->paginate($perPage);

        // Transform the data with detailed product information
        $products->getCollection()->transform(function ($product) use ($request) {
            // Process facilities
            $facilities = is_array($product->facilities) ? $product->facilities : json_decode($product->facilities, true) ?? [];
            $product->facilities = is_array($facilities) ? $facilities : [];

            // Process images
            $images = is_array($product->images) ? $product->images : json_decode($product->images, true) ?? [];
            $product->images = is_array($images) ? $images : [];

            // Get thumbnail
            $thumbnail = !empty($product->images) ? Storage::url('products/' . $product->images[0]) : null;

            // Process product details - filter based on search query if searching for room names
            $searchTerm = $request->get('q');
            $filteredDetails = $product->productDetails->map(function ($detail) {
                $detailFacilities = is_array($detail->facilities) ? $detail->facilities : json_decode($detail->facilities, true) ?? [];
                $detail->facilities = is_array($detailFacilities) ? $detailFacilities : [];

                $detailImages = is_array($detail->images) ? $detail->images : json_decode($detail->images, true) ?? [];
                $detail->images = is_array($detailImages) ? $detailImages : [];

                return $detail->only(['id', 'room_name', 'price', 'status', 'available_rooms', 'facilities', 'description', 'images']);
            })->filter();

            // If searching for specific room name, filter the details
            if ($searchTerm && !empty($searchTerm)) {
                $filteredDetails = $filteredDetails->filter(function ($detail) use ($searchTerm) {
                    return stripos($detail['room_name'], $searchTerm) !== false ||
                           stripos($detail['description'], $searchTerm) !== false;
                });
            }

            // Calculate pricing and availability based on filtered details (vacant rooms only)
            $vacantFilteredDetails = $filteredDetails->where('status', 'kosong');
            $startingPrice = $filteredDetails->min('price') ?: $product->productDetails->min('price');
            $totalAvailable = $vacantFilteredDetails->sum('available_rooms') ?: $product->productDetails->where('status', 'kosong')->sum('available_rooms');
            $totalRooms = $filteredDetails->count() ?: $product->productDetails->count();

            if ($totalAvailable == 0) {
                $status = 'habis';
                $roomAvailable = 0;
            } else {
                $status = 'tersedia';
                $roomAvailable = $totalAvailable;
            }

            // Get facilities preview
            $facilitiesPreview = [];
            if (is_array($product->facilities)) {
                foreach ($product->facilities as $facilityGroup) {
                    if (isset($facilityGroup['items']) && is_array($facilityGroup['items'])) {
                        $facilitiesPreview = array_merge($facilitiesPreview, array_slice($facilityGroup['items'], 0, 2));
                        if (count($facilitiesPreview) >= 4) break;
                    }
                }
                $facilitiesPreview = array_slice($facilitiesPreview, 0, 4);
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'address' => $product->address,
                'distance_to_kariadi' => (float) $product->distance_to_kariadi,
                'latitude' => $product->latitude,
                'longitude' => $product->longitude,
                'whatsapp' => $product->whatsapp,
                'google_maps_link' => $product->google_maps_link,
                'facilities' => $product->facilities,
                'description' => $product->description,
                'images' => $product->images,
                'category' => $product->category,
                'thumbnail' => $thumbnail,
                'starting_price' => (int) $startingPrice,
                'facilities_preview' => $facilitiesPreview,
                'status' => $status,
                'room_available' => $roomAvailable,
                'total_rooms' => $totalRooms,
                'product_details' => $filteredDetails->values(), // Only show filtered details
                'created_at' => $product->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Search completed successfully',
            'meta' => [
                'query' => $request->get('q'),
                'total_results' => $products->total(),
                'filters_applied' => array_filter([
                    'category' => $request->get('category'),
                    'status' => $request->get('status'),
                    'min_price' => $request->get('min_price'),
                    'max_price' => $request->get('max_price'),
                    'min_distance' => $request->get('min_distance'),
                    'max_distance' => $request->get('max_distance'),
                    'facilities' => $request->get('facilities'),
                    'sort_by' => $request->get('sort_by'),
                    'sort_order' => $request->get('sort_order'),
                ])
            ]
        ]);
    }
    
    public function show($id)
    {
        \Log::info('ProductController show called', ['id' => $id]);

        try {
            $product = Product::findOrFail($id);

            if (!$product->is_published) {
                \Log::warning('Product not published', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $product->load([
                'productDetails' => function ($q) {
                    $q->where('is_active', true)
                      ->select('id', 'product_id', 'room_name', 'price', 'status', 'available_rooms', 'images', 'facilities', 'description');
                }
            ]);

            \Log::info('Product loaded with details', ['product_id' => $product->id, 'details_count' => $product->productDetails->count()]);

            // Process facilities
            if (is_array($product->facilities)) {
                // Already decoded
            } else {
                $facilities = json_decode($product->facilities, true) ?? [];
                $product->facilities = is_array($facilities) ? $facilities : [];
            }

            // Process images
            if (is_array($product->images)) {
                // Already decoded
            } else {
                $images = json_decode($product->images, true) ?? [];
                $product->images = is_array($images) ? $images : [];
            }

            // Process product details
            $product->product_details = $product->productDetails->map(function ($detail) {
                try {
                    if (is_array($detail->facilities)) {
                        // Already decoded
                    } else {
                        $detailFacilities = json_decode($detail->facilities, true) ?? [];
                        $detail->facilities = is_array($detailFacilities) ? $detailFacilities : [];
                    }

                    if (is_array($detail->images)) {
                        // Already decoded
                    } else {
                        $detailImages = json_decode($detail->images, true) ?? [];
                        $detail->images = is_array($detailImages) ? $detailImages : [];
                    }

                    return $detail->only(['id', 'room_name', 'price', 'status', 'available_rooms', 'facilities', 'description', 'images']);
                } catch (\Exception $e) {
                    \Log::error('Error processing product detail', ['detail_id' => $detail->id, 'error' => $e->getMessage()]);
                    return null; // Skip invalid detail
                }
            })->filter(); // Remove nulls

            // Calculate total available rooms and status from vacant rooms only
            $vacantDetails = $product->productDetails->where('status', 'kosong');
            $totalAvailable = $vacantDetails->sum('available_rooms');
            $totalRooms = $product->productDetails->count();
            if ($totalAvailable == 0) {
                $product->status = 'habis';
                $product->room_available = 0;
            } else {
                $product->status = 'tersedia';
                $product->room_available = $totalAvailable;
            }
            $product->total_rooms = $totalRooms;

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product details retrieved successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning('Product not found', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error in ProductController show', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the product'
            ], 500);
        }
    }
}
