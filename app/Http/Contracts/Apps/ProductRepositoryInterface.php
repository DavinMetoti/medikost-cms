<?php

namespace App\Http\Contracts\Apps;

use Illuminate\Http\Request;

interface ProductRepositoryInterface
{
    /**
     * Store a new prodyct.
     *
     * @param array $data
     * @return \App\Models\Product
     */
    public function store(array $data);

    /**
     * Update an existing prodyct.
     *
     * @param string $id
     * @param array $data
     * @return \App\Models\Product
     */
    public function update(string $id, array $data);

    /**
     * Delete a prodyct.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id);

    /**
     * Get datatable data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function datatable(Request $request);

    /**
     * Find a Product by ID.
     *
     * @param string $id
     * @return \App\Models\Product|null
     */
    public function find(string $id);

    /**
     * Count all products.
     *
     * @return int
     */
    public function countAll();

    /**
     * Count draft products.
     *
     * @return int
     */
    public function countDraft();

    /**
     * Count published products.
     *
     * @return int
     */
    public function countPublished();
}
