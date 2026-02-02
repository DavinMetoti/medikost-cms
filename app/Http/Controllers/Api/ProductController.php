<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        ->whereHas('productDetails', function ($q) {
            $q->where('is_active', true)->whereNotNull('price');
        })
        ->select('id', 'name', 'address', 'distance_to_kariadi', 'whatsapp', 'google_maps_link', 'facilities', 'description', 'images');

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

            // Calculate total available rooms and status
            $totalAvailable = $product->productDetails->sum('available_rooms');
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with([
            'productDetails' => function ($q) {
                $q->where('is_active', true)
                  ->select('id', 'product_id', 'room_name', 'price', 'status', 'available_rooms', 'images', 'facilities', 'description');
            }
        ])
        ->where('is_published', true)
        ->select('id', 'name', 'address', 'distance_to_kariadi', 'whatsapp', 'google_maps_link', 'facilities', 'description', 'images')
        ->findOrFail($id);

        // Process facilities
        $facilities = json_decode($product->facilities, true) ?? [];
        $product->facilities = is_array($facilities) ? $facilities : [];

        // Process images
        $images = json_decode($product->images, true) ?? [];
        $product->images = is_array($images) ? $images : [];

        // Process product details
        $product->product_details = $product->productDetails->map(function ($detail) {
            $detailFacilities = json_decode($detail->facilities, true) ?? [];
            $detail->facilities = is_array($detailFacilities) ? $detailFacilities : [];

            $detailImages = json_decode($detail->images, true) ?? [];
            $detail->images = is_array($detailImages) ? $detailImages : [];

            return $detail->only(['id', 'room_name', 'price', 'status', 'available_rooms', 'facilities', 'description', 'images']);
        });

        // Calculate total available rooms and status
        $totalAvailable = $product->productDetails->sum('available_rooms');
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
    }
}
