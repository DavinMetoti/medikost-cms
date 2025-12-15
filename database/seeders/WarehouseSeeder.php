<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Warehouse Utama
        $mainWarehouse = Warehouse::create([
            'code'        => 'WH-JKT',
            'name'        => 'Warehouse Jakarta',
            'address'     => 'Jl. Industri No. 1, Jakarta',
            'description' => 'Warehouse utama Jakarta',
            'is_active'   => true,
        ]);

        // Warehouse Cabang / Area
        Warehouse::insert([
            [
                'parent_id'  => $mainWarehouse->id,
                'code'       => 'WH-JKT-A',
                'name'       => 'Warehouse Jakarta - Area A',
                'address'    => 'Area A - Gudang Jakarta',
                'description'=> 'Area penyimpanan A',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id'  => $mainWarehouse->id,
                'code'       => 'WH-JKT-B',
                'name'       => 'Warehouse Jakarta - Area B',
                'address'    => 'Area B - Gudang Jakarta',
                'description'=> 'Area penyimpanan B',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
