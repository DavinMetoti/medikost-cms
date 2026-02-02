<?php

namespace App\Http\Repositories\Apps;

use App\Http\Contracts\Apps\ProductRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;


class ProductRepository implements ProductRepositoryInterface
{
    public function store(array $data)
    {
        return Product::create($data);
    }

    public function update(string $id, array $data)
    {
        $product = Product::findOrFail($id);

        $product->update($data);

        return $product;
    }

    public function delete(string $id)
    {
        $product = Product::findOrFail($id);


        return $product->delete();
    }

    public function datatable(Request $request)
    {
        $query = Product::with('productDetails');

        // Apply filter based on is_published if provided
        if ($request->has('filter')) {
            $filter = $request->input('filter');
            if ($filter === 'publish') {
                $query->where('is_published', 1);
            } elseif ($filter === 'draft') {
                $query->where('is_published', 0);
            }
        }

        return DataTables::of($query)->make(true);
    }

    public function find(string $id)
    {
        return Product::with('productDetails')->find($id);
    }

    public function countAll()
    {
        return Product::count();
    }

    public function countDraft()
    {
        return Product::where('is_published', 0)->count();
    }

    public function countPublished()
    {
        return Product::where('is_published', 1)->count();
    }

    public function search(string $search)
    {
        return Product::where('name', 'like', '%' . $search . '%')
            ->where('is_published', 1) // Only search published products
            ->limit(20) // Limit results for performance
            ->get();
    }
}
