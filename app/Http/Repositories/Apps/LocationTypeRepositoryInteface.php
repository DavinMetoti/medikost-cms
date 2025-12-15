<?php

namespace App\Http\Contracts\Apps;

use Illuminate\Support\Collection;
use App\Models\LocationType;

interface LocationTypeRepositoryInteface
{
    /**
     * Get all location types
     */
    public function all(): Collection;

    /**
     * Get only active location types
     */
    public function allActive(): Collection;

    /**
     * Find location type by ID
     */
    public function findById(int $id): ?LocationType;

    /**
     * Find location type by code
     */
    public function findByCode(string $code): ?LocationType;

    /**
     * Create location type
     */
    public function create(array $data): LocationType;

    /**
     * Update location type
     */
    public function update(int $id, array $data): LocationType;

    /**
     * Delete location type
     */
    public function delete(int $id): bool;

    /**
     * Activate / Deactivate location type
     */
    public function toggleStatus(int $id, bool $status): bool;

    /**
     * Datatable (Yajra)
     */
    public function datatable();
}
