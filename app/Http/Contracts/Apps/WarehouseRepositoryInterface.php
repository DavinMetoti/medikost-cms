<?php

namespace App\Http\Contracts\Apps;

use Illuminate\Support\Collection;
use App\Models\Warehouse;

interface WarehouseRepositoryInterface
{
    /**
     * Get all warehouses
     */
    public function all(): Collection;

    /**
     * Get only active warehouses
     */
    public function allActive(): Collection;

    /**
     * Find warehouse by ID
     */
    public function findById(int $id): ?Warehouse;

    /**
     * Find warehouse by code
     */
    public function findByCode(string $code): ?Warehouse;

    /**
     * Get parent warehouse
     */
    public function getParent(int $id): ?Warehouse;

    /**
     * Get child warehouses
     */
    public function getChildren(int $id): Collection;

    /**
     * Create new warehouse
     */
    public function create(array $data): Warehouse;

    /**
     * Update warehouse
     */
    public function update(int $id, array $data): Warehouse;

    /**
     * Delete warehouse (soft logic / hard delete)
     */
    public function delete(int $id): bool;

    /**
     * Activate / Deactivate warehouse
     */
    public function toggleStatus(int $id, bool $status): bool;

    /**
     * Get datatable data
     */
    public function datatable();
}
