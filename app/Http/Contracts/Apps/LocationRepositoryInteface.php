<?php

namespace App\Http\Contracts\Apps;

use Illuminate\Support\Collection;
use App\Models\Location;

interface LocationRepositoryInteface
{
    /**
     * Get all locations
     */
    public function all(): Collection;

    /**
     * Get all active locations
     */
    public function allActive(): Collection;

    /**
     * Get locations by warehouse
     */
    public function getByWarehouse(int $warehouseId): Collection;

    /**
     * Find location by ID
     */
    public function findById(int $id): ?Location;

    /**
     * Find location by code
     */
    public function findByCode(string $code): ?Location;

    /**
     * Get parent location
     */
    public function getParent(int $id): ?Location;

    /**
     * Get child locations
     */
    public function getChildren(int $id): Collection;

    /**
     * Get locations by type (AREA, RACK, SHELF, BIN)
     */
    public function getByType(int $locationTypeId): Collection;

    /**
     * Create location
     */
    public function create(array $data): Location;

    /**
     * Update location
     */
    public function update(int $id, array $data): Location;

    /**
     * Delete location
     */
    public function delete(int $id): bool;

    /**
     * Activate / Deactivate location
     */
    public function toggleStatus(int $id, bool $status): bool;

    /**
     * Datatable (Yajra)
     */
    public function datatable();
}
