<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\JsonResponse;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of product details for a specific product.
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function index($productId): JsonResponse
    {
        $product = Product::where('is_published', true)
            ->where('is_active', true)
            ->findOrFail($productId);

        $productDetails = ProductDetail::where('product_id', $productId)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Product details retrieved successfully',
            'data' => [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category,
                ],
                'details' => $productDetails,
            ],
        ], 200);
    }

    /**
     * Display the specified product detail.
     *
     * @param int $productId
     * @param int $id
     * @return JsonResponse
     */
    public function show($productId, $id): JsonResponse
    {
        $product = Product::where('is_published', true)
            ->where('is_active', true)
            ->findOrFail($productId);

        $productDetail = ProductDetail::where('product_id', $productId)
            ->where('is_active', true)
            ->findOrFail($id);

        // Get other rooms from the same product
        $anotherRooms = ProductDetail::where('product_id', $productId)
            ->where('is_active', true)
            ->where('id', '!=', $id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Product detail retrieved successfully',
            'data' => [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'address' => $product->address,
                    'distance_to_kariadi' => $product->distance_to_kariadi,
                    'whatsapp' => $product->whatsapp,
                    'description' => $product->description,
                    'facilities' => $product->facilities,
                    'google_maps_link' => $product->google_maps_link,
                    'images' => $product->images,
                    'category' => $product->category,
                ],
                'detail' => $productDetail,
                'list_another_rooms' => $anotherRooms,
            ],
        ], 200);
    }
}
