<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apps\ProductDetail\StoreRequest;
use App\Http\Requests\Apps\ProductDetail\UpdateRequest;
use App\Http\Contracts\Apps\ProductDetailRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductDetailController extends Controller
{
    protected $productDetailRepo;

    public function __construct(ProductDetailRepositoryInterface $productDetailRepo)
    {
        $this->productDetailRepo = $productDetailRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.content.product-detail.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.content.product-detail.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        try {
            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('file_image')) {
                foreach ($request->file('file_image') as $image) {
                    $uniqueName = time() . '-' . $image->getClientOriginalName();
                    $storedPath = $image->storeAs('product-details', $uniqueName, 'public');
                    $imagePaths[] = str_replace('product-details/', '', $storedPath);
                }
            }

            $validated['images'] = json_encode($imagePaths);

            $productDetail = $this->productDetailRepo->store($validated);

            return response()->json([
                'message' => 'Product Detail successfully created!',
                'data' => $productDetail,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Product Detail creation failed!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productDetail = $this->productDetailRepo->find($id);
        $bookings = \App\Models\Booking::where('product_detail_id', $id)->with('user')->orderBy('created_at', 'desc')->get();
        return view('pages.content.product-detail.show', compact('productDetail', 'bookings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $productDetail = $this->productDetailRepo->find($id);
        return view('pages.content.product-detail.edit', compact('productDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $productDetail = $this->productDetailRepo->find($id);

            // Handle new image uploads
            $newImagePaths = [];
            if ($request->hasFile('file_image')) {
                foreach ($request->file('file_image') as $image) {
                    $uniqueName = time() . '-' . $image->getClientOriginalName();
                    $newImagePaths[] = $image->storeAs('product-details', $uniqueName, 'public');
                }
            }

            // Merge existing images with new ones
            $existingImages = json_decode($productDetail->images ?? '[]', true);
            $existingImages = array_map(function ($image) {
                return str_replace('product-details/', '', $image);
            }, $existingImages);
            $newImagePaths = array_map(function ($image) {
                return str_replace('product-details/', '', $image);
            }, $newImagePaths);
            $mergedImages = array_merge($existingImages, $newImagePaths);

            // Handle removed images
            $removedImages = json_decode($request->input('removed_images', '[]'), true);
            $mergedImages = array_filter($mergedImages, function($image) use ($removedImages) {
                return !in_array($image, $removedImages);
            });

            // Delete removed image files
            foreach ($removedImages as $removedImage) {
                Storage::disk('public')->delete('product-details/' . $removedImage);
            }

            $validated['images'] = json_encode(array_values($mergedImages));

            $updatedProductDetail = $this->productDetailRepo->update($id, $validated);

            return response()->json([
                'message' => 'Product Detail successfully updated!',
                'data' => $updatedProductDetail,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Product Detail update failed!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $productDetail = $this->productDetailRepo->delete($id);
            return response()->json([
                'message' => 'Product Detail successfully deleted!',
                'data' => $productDetail,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Product Detail delete failed!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function datatable(Request $request)
    {
        try {
            return $this->productDetailRepo->datatable($request);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function counts()
    {
        try {
            $allCount = $this->productDetailRepo->countAll();
            $draftCount = $this->productDetailRepo->countDraft();
            $publishedCount = $this->productDetailRepo->countPublished();

            return response()->json([
                'all' => $allCount,
                'draft' => $draftCount,
                'published' => $publishedCount,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch product detail counts!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
