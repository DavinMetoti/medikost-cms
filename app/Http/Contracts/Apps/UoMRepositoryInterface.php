<?php

namespace App\Http\Contracts\Apps;

use Illuminate\Http\Request;

interface UoMRepositoryInterface
{
    /**
     * Store a new prodyct.
     *
     * @param array $data
     * @return \App\Models\UnitOfMeasurement
     */
    public function store(array $data);

    /**
     * Update an existing prodyct.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\UnitOfMeasurement
     */
    public function update(int $id, array $data);

    /**
     * Delete a prodyct.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);

    /**
     * Get datatable data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function datatable(Request $request);

    /**
     * Find a UnitOfMeasurement by ID.
     *
     * @param int $id
     * @return \App\Models\UnitOfMeasurement|null
     */
    public function find(int $id);

    /**
     * Count all units of measurement.
     *
     * @return int
     */
    public function countAll();

    /**
     * Get limited units of measurement with optional search.
     *
     * @param string $search
     * @return \Illuminate\Support\Collection
     */
    public function getLimitedWithSearch(?string $search = null);
}
