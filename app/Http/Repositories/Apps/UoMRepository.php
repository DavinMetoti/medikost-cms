<?php

namespace App\Http\Repositories\Apps;

use App\Http\Contracts\Apps\UoMRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\UnitOfMeasurement;
use Yajra\DataTables\Facades\DataTables;


class UoMRepository implements UoMRepositoryInterface
{
    public function store(array $data)
    {
        return UnitOfMeasurement::create($data);
    }

    public function update(int $id, array $data)
    {
        $unit = UnitOfMeasurement::findOrFail($id);

        $unit->update($data);

        return $unit;
    }

    public function delete(int $id)
    {
        $unit = UnitOfMeasurement::findOrFail($id);


        return $unit->delete();
    }

    public function datatable(Request $request)
    {
        $query = UnitOfMeasurement::query();

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

    public function find(int $id)
    {
        return UnitOfMeasurement::find($id);
    }

    public function countAll()
    {
        return UnitOfMeasurement::count();
    }

    public function getLimitedWithSearch(?string $search = null)
    {
        $query = UnitOfMeasurement::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('abbreviation', 'like', '%' . $search . '%');
        }

        return $query->limit(10)->get();
    }
}
