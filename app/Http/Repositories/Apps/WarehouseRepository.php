<?php

namespace App\Http\Repositories\Apps;

use App\Http\Contracts\Apps\WarehouseRepositoryInterface;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class WarehouseRepository implements WarehouseRepositoryInterface
{
    /**
     * Get all warehouses
     */
    public function all(): Collection
    {
        return Warehouse::orderBy('name')->get();
    }

    /**
     * Get all active warehouses
     */
    public function allActive(): Collection
    {
        return Warehouse::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Find warehouse by ID
     */
    public function findById(int $id): ?Warehouse
    {
        return Warehouse::with('parent')->find($id);
    }

    /**
     * Find warehouse by code
     */
    public function findByCode(string $code): ?Warehouse
    {
        return Warehouse::where('code', $code)->first();
    }

    /**
     * Get parent warehouse
     */
    public function getParent(int $id): ?Warehouse
    {
        return Warehouse::with('parent')->find($id)?->parent;
    }

    /**
     * Get child warehouses
     */
    public function getChildren(int $id): Collection
    {
        return Warehouse::where('parent_id', $id)->get();
    }

    /**
     * Create warehouse
     */
    public function create(array $data): Warehouse
    {
        $created_by = auth()->user()->id ?? null;
        $data['created_by'] = $created_by;
        return Warehouse::create($data);
    }

    /**
     * Update warehouse
     */
    public function update(int $id, array $data): Warehouse
    {
        $warehouse = $this->findById($id);
        $updated_by = auth()->user()->id ?? null;
        $data['updated_by'] = $updated_by;

        if (! $warehouse) {
            throw new \Exception('Warehouse not found');
        }

        // If deactivating the warehouse, also deactivate all children
        if (isset($data['is_active']) && $data['is_active'] == false) {
            $this->deactivateChildren($id);
        }

        $warehouse->update($data);

        return $warehouse;
    }

    /**
     * Delete warehouse
     */
    public function delete(int $id): bool
    {
        $warehouse = $this->findById($id);

        if (! $warehouse) {
            return false;
        }

        return (bool) $warehouse->delete();
    }

    /**
     * Deactivate all children of a warehouse recursively
     */
    private function deactivateChildren(int $warehouseId): void
    {
        $children = $this->getChildren($warehouseId);

        foreach ($children as $child) {
            // Deactivate the child
            $child->update(['is_active' => false]);

            // Recursively deactivate its children
            $this->deactivateChildren($child->id);
        }
    }

    /**
     * Activate / Deactivate warehouse
     */
    public function toggleStatus(int $id, bool $status): bool
    {
        $warehouse = $this->findById($id);

        if (! $warehouse) {
            return false;
        }

        $warehouse->is_active = $status;
        return $warehouse->save();
    }

    /**
     * Datatable (Yajra) with hierarchical display
     */
    public function datatable()
    {
        // Get all warehouses ordered by hierarchy
        $warehouses = Warehouse::with('parent')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        // Build hierarchical structure
        $hierarchicalData = $this->buildHierarchy($warehouses);

        return DataTables::of(collect($hierarchicalData))
            ->addColumn('name_display', function ($row) {
                $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $row['level']);
                $prefix = $row['level'] > 0 ? '<span class="tree-connector">└─ </span>' : '';
                return '<span class="tree-level-' . $row['level'] . '">' . $indent . $prefix . $row['name'] . '</span>';
            })
            ->addColumn('parent', function ($row) {
                return $row['parent_name'] ?? '-';
            })
            ->addColumn('status', function ($row) {
                return $row['is_active'] ? 'Active' : 'Inactive';
            })
            ->rawColumns(['name_display'])
            ->make(true);
    }

    /**
     * Build hierarchical structure from flat warehouse data
     */
    private function buildHierarchy(Collection $warehouses): array
    {
        $items = [];
        $lookup = [];

        // First pass: create lookup array
        foreach ($warehouses as $warehouse) {
            $lookup[$warehouse->id] = [
                'id' => $warehouse->id,
                'code' => $warehouse->code,
                'name' => $warehouse->name,
                'address' => $warehouse->address,
                'description' => $warehouse->description,
                'parent_id' => $warehouse->parent_id,
                'parent_name' => $warehouse->parent?->name,
                'is_active' => $warehouse->is_active,
                'level' => 0,
                'children' => []
            ];
        }

        // Second pass: build hierarchy
        $rootItems = [];
        foreach ($lookup as $id => &$item) {
            if ($item['parent_id']) {
                if (isset($lookup[$item['parent_id']])) {
                    $lookup[$item['parent_id']]['children'][] = &$item;
                }
            } else {
                $rootItems[] = &$item;
            }
        }

        // Flatten hierarchy with levels
        $result = [];
        $this->flattenHierarchy($rootItems, $result, 0);

        return $result;
    }

    /**
     * Flatten hierarchical structure into flat array with level information
     */
    private function flattenHierarchy(array &$items, array &$result, int $level): void
    {
        foreach ($items as &$item) {
            $item['level'] = $level;
            $result[] = $item;

            if (!empty($item['children'])) {
                $this->flattenHierarchy($item['children'], $result, $level + 1);
            }
        }
    }
}
