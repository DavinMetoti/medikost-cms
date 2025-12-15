<?php

namespace App\Http\Repositories\Apps;

use App\Http\Contracts\Apps\LocationRepositoryInteface;
use App\Models\Location;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class LocationRepository implements LocationRepositoryInteface
{
    /**
     * Get all locations
     */
    public function all(): Collection
    {
        return Location::with(['warehouse', 'locationType', 'parent'])
            ->orderBy('code')
            ->get();
    }

    /**
     * Get all active locations
     */
    public function allActive(): Collection
    {
        return Location::where('is_active', true)
            ->with(['warehouse', 'locationType', 'parent'])
            ->orderBy('code')
            ->get();
    }

    /**
     * Get locations by warehouse
     */
    public function getByWarehouse(int $warehouseId): Collection
    {
        return Location::where('warehouse_id', $warehouseId)
            ->with(['locationType', 'parent'])
            ->orderBy('code')
            ->get();
    }

    /**
     * Find location by ID
     */
    public function findById(int $id): ?Location
    {
        return Location::with(['warehouse', 'locationType', 'parent', 'children.children'])
            ->find($id);
    }

    /**
     * Find location by code
     */
    public function findByCode(string $code): ?Location
    {
        return Location::where('code', $code)->first();
    }

    /**
     * Get parent location
     */
    public function getParent(int $id): ?Location
    {
        return Location::find($id)?->parent;
    }

    /**
     * Get child locations
     */
    public function getChildren(int $id): Collection
    {
        return Location::where('parent_id', $id)->get();
    }

    /**
     * Get locations by type (AREA / RACK / SHELF / BIN)
     */
    public function getByType(int $locationTypeId): Collection
    {
        return Location::where('location_type_id', $locationTypeId)
            ->orderBy('code')
            ->get();
    }

    /**
     * Create location
     */
    public function create(array $data): Location
    {
        return Location::create($data);
    }

    /**
     * Update location
     */
    public function update(int $id, array $data): Location
    {
        $location = $this->findById($id);

        if (! $location) {
            throw new \Exception('Location tidak ditemukan');
        }

        // Jika nonaktif → nonaktifkan semua child
        if (isset($data['is_active']) && $data['is_active'] === false) {
            $this->deactivateChildren($location->id);
        }

        $location->update($data);

        return $location;
    }

    /**
     * Delete location
     */
    public function delete(int $id): bool
    {
        $location = $this->findById($id);

        if (! $location) {
            return false;
        }

        return (bool) $location->delete();
    }

    /**
     * Activate / Deactivate location
     */
    public function toggleStatus(int $id, bool $status): bool
    {
        $location = $this->findById($id);

        if (! $location) {
            return false;
        }

        return $location->update(['is_active' => $status]);
    }

    /**
     * Datatable (Hierarchical)
     */
    public function datatable()
    {
        $locations = Location::with(['warehouse', 'locationType', 'parent'])
            ->orderBy('warehouse_id')
            ->orderBy('parent_id')
            ->orderBy('code')
            ->get();

        $data = $this->buildHierarchy($locations);

        return DataTables::of(collect($data))
            ->addColumn('location_display', function ($row) {
                $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $row['level']);
                return $indent . $row['code'] . ' - ' . $row['name'];
            })
            ->addColumn('warehouse', fn ($row) => $row['warehouse_name'])
            ->addColumn('type', fn ($row) => $row['location_type'])
            ->addColumn('status', fn ($row) => $row['is_active'] ? 'Active' : 'Inactive')
            ->rawColumns(['location_display'])
            ->make(true);
    }

    /**
     * ==========================
     * PRIVATE METHODS
     * ==========================
     */

    /**
     * Deactivate children recursively
     */
    private function deactivateChildren(int $parentId): void
    {
        $children = Location::where('parent_id', $parentId)->get();

        foreach ($children as $child) {
            $child->update(['is_active' => false]);
            $this->deactivateChildren($child->id);
        }
    }

    /**
     * Build hierarchy
     */
    private function buildHierarchy(Collection $locations): array
    {
        $items = [];
        $lookup = [];

        foreach ($locations as $location) {
            $lookup[$location->id] = [
                'id' => $location->id,
                'code' => $location->code,
                'name' => $location->name,
                'warehouse_name' => $location->warehouse->name ?? '-',
                'location_type' => $location->type->name ?? '-',
                'parent_id' => $location->parent_id,
                'is_active' => $location->is_active,
                'level' => 0,
                'children' => [],
            ];
        }

        $roots = [];

        foreach ($lookup as $id => &$item) {
            if ($item['parent_id'] && isset($lookup[$item['parent_id']])) {
                $lookup[$item['parent_id']]['children'][] = &$item;
            } else {
                $roots[] = &$item;
            }
        }

        $result = [];
        $this->flattenHierarchy($roots, $result, 0);

        return $result;
    }

    /**
     * Flatten hierarchy
     */
    private function flattenHierarchy(array &$items, array &$result, int $level): void
    {
        foreach ($items as &$item) {
            $item['level'] = $level;
            $result[] = $item;

            if (! empty($item['children'])) {
                $this->flattenHierarchy($item['children'], $result, $level + 1);
            }
        }
    }
}
