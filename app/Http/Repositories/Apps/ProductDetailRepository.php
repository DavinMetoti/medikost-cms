<?php

namespace App\Http\Repositories\Apps;

use App\Http\Contracts\Apps\ProductDetailRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;


class ProductDetailRepository implements ProductDetailRepositoryInterface
{
    public function store(array $data)
    {
        return ProductDetail::create($data);
    }

    public function update(string $id, array $data)
    {
        $product = ProductDetail::findOrFail($id);

        $product->update($data);

        return $product;
    }

    public function delete(string $id)
    {
        $product = ProductDetail::findOrFail($id);


        return $product->delete();
    }

    public function datatable(Request $request)
    {
        $query = ProductDetail::with('product');

        // Apply filter based on is_active if provided
        if ($request->has('filter')) {
            $filter = $request->input('filter');
            if ($filter === 'publish') {
                $query->where('is_active', 1);
            } elseif ($filter === 'draft') {
                $query->where('is_active', 0);
            }
        }

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where(function($q) use ($search) {
                        $q->where('room_name', 'like', '%' . $search . '%')
                          ->orWhere('price', 'like', '%' . $search . '%')
                          ->orWhere('status', 'like', '%' . $search . '%')
                          ->orWhere('available_rooms', 'like', '%' . $search . '%')
                          ->orWhereHas('product', function($pq) use ($search) {
                              $pq->where('name', 'like', '%' . $search . '%');
                          });
                    });
                }
            })
            ->filterColumn('product_name', function($query, $keyword) {
                $query->whereHas('product', function($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->make(true);
    }

    public function find(string $id)
    {
        return ProductDetail::with('product')->find($id);
    }

    public function countAll()
    {
        return ProductDetail::count();
    }

    public function countDraft()
    {
        return ProductDetail::where('is_active', 0)->count();
    }

    public function countPublished()
    {
        return ProductDetail::where('is_active', 1)->count();
    }
}
