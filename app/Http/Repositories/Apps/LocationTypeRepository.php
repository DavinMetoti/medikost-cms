<?php

namespace App\Http\Repositories\Apps;

use App\Http\Contracts\Apps\LocationTypeRepositoryInteface;
use App\Models\LocationType;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class LocationTypeRepository implements LocationTypeRepositoryInteface
{
    /**
     * Get all Location Types
     */
    public function all(): Collection
    {
        return LocationType::orderBy('level_order')->get();
    }

    /**
     * Get all active Location Types
     */
    public function allActive(): Collection
    {
        return LocationType::where('is_active', true)
            ->orderBy('level_order')
            ->get();
    }

    /**
     * Find LocationType by ID
     */
    public function findById(int $id): ?LocationType
    {
        return LocationType::find($id);
    }

    /**
     * Find LocationType by code
     */
    public function findByCode(string $code): ?LocationType
    {
        return LocationType::where('code', $code)->first();
    }

    /**
     * Create LocationType
     */
    public function create(array $data): LocationType
    {
        return LocationType::create($data);
    }

    /**
     * Update LocationType
     */
    public function update(int $id, array $data): LocationType
    {
        $locationType = $this->findById($id);

        if (! $locationType) {
            throw new \Exception('Location Type tidak ditemukan');
        }

        $locationType->update($data);

        return $locationType;
    }

    /**
     * Delete LocationType
     */
    public function delete(int $id): bool
    {
        $locationType = $this->findById($id);

        if (! $locationType) {
            return false;
        }

        return (bool) $locationType->delete();
    }

    /**
     * Activate / Deactivate LocationType
     */
    public function toggleStatus(int $id, bool $status): bool
    {
        $locationType = $this->findById($id);

        if (! $locationType) {
            return false;
        }

        return $locationType->update(['is_active' => $status]);
    }

    /**
     * Datatable (Yajra)
     */
    public function datatable()
    {
        $query = LocationType::query()
            ->orderBy('level_order');

        return DataTables::of($query)
            ->addColumn('status', function ($row) {
                return $row->is_active ? 'Active' : 'Inactive';
            })
            ->make(true);
    }
}
