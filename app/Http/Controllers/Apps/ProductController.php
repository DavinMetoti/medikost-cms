<?php

namespace App\Http\Controllers\Apps;

use App\Http\Contracts\Apps\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Apps\Product\StoreRequest;
use App\Http\Requests\Apps\Product\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.content.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.content.product.create');
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
                    $storedPath = $image->storeAs('products', $uniqueName, 'public');
                    $imagePaths[] = str_replace('products/', '', $storedPath); // Remove 'products/' prefix
                }
            }

            $validated['images'] = json_encode($imagePaths); // Save images as JSON

            $product = $this->productRepo->store($validated);

            return response()->json([
                'message' => 'Product successfully created!',
                'data' => $product,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Product creation failed!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productRepo->find($id);
        return view('pages.content.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = $this->productRepo->find($id);
        return view('pages.content.product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $product = $this->productRepo->find($id);

            // Handle new image uploads
            $newImagePaths = [];
            if ($request->hasFile('file_image')) {
                foreach ($request->file('file_image') as $image) {
                    $uniqueName = time() . '-' . $image->getClientOriginalName();
                    $newImagePaths[] = $image->storeAs('products', $uniqueName, 'public');
                }
            }

            // Merge existing images with new ones
            $existingImages = json_decode($product->images ?? '[]', true);

            // Remove 'products/' prefix from existing images
            $existingImages = array_map(function ($image) {
                return str_replace('products/', '', $image);
            }, $existingImages);

            // Remove 'products/' prefix from new images
            $newImagePaths = array_map(function ($image) {
                return str_replace('products/', '', $image);
            }, $newImagePaths);

            $mergedImages = array_merge($existingImages, $newImagePaths);

            // Handle removed images
            $removedImages = json_decode($request->input('removed_images', '[]'), true);
            $mergedImages = array_filter($mergedImages, function($image) use ($removedImages) {
                return !in_array($image, $removedImages);
            });

            // Delete removed image files
            foreach ($removedImages as $removedImage) {
                Storage::disk('public')->delete('products/' . $removedImage);
            }

            $validated['images'] = json_encode(array_values($mergedImages));

            $updatedProduct = $this->productRepo->update($id, $validated);

            return response()->json([
                'message' => 'Product successfully updated!',
                'data' => $updatedProduct,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Product update failed!',
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
            $product = $this->productRepo->delete($id);
            return response()->json([
                'message' => 'Product successfully deleted!',
                'data' => $product,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Product delete failed!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function datatable(Request $request)
    {
        try {
            return $this->productRepo->datatable($request);
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
            $allCount = $this->productRepo->countAll();
            $draftCount = $this->productRepo->countDraft();
            $publishedCount = $this->productRepo->countPublished();

            return response()->json([
                'all' => $allCount,
                'draft' => $draftCount,
                'published' => $publishedCount,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch product counts!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function search(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $products = $this->productRepo->search($search);

            $results = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'text' => $product->name,
                ];
            });

            return response()->json([
                'results' => $results,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to search products!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
