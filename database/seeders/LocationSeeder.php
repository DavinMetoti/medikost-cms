<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Location;
use App\Models\LocationType;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil warehouse utama
        $warehouse = Warehouse::where('code', 'WH-JKT')->first();

        if (! $warehouse) {
            $this->command->error('Warehouse WH-JKT tidak ditemukan.');
            return;
        }

        // Ambil location types
        $areaType  = LocationType::where('code', 'AREA')->first();
        $rackType  = LocationType::where('code', 'RACK')->first();
        $shelfType = LocationType::where('code', 'SHELF')->first();
        $binType   = LocationType::where('code', 'BIN')->first();

        if (! $areaType || ! $rackType || ! $shelfType || ! $binType) {
            $this->command->error('LocationType belum lengkap.');
            return;
        }

        /*
         * ======================
         * AREA
         * ======================
         */
        $areaA = Location::updateOrCreate(
            [
                'warehouse_id' => $warehouse->id,
                'code'         => 'A',
            ],
            [
                'parent_id'        => null,
                'location_type_id'=> $areaType->id,
                'name'             => 'Area A',
                'description'      => 'Area penyimpanan A',
                'is_active'        => true,
            ]
        );

        /*
         * ======================
         * RACK
         * ======================
         */
        $rackA1 = Location::updateOrCreate(
            [
                'warehouse_id' => $warehouse->id,
                'code'         => 'A-R1',
            ],
            [
                'parent_id'        => $areaA->id,
                'location_type_id'=> $rackType->id,
                'name'             => 'Rack A1',
                'description'      => 'Rack A1 di Area A',
                'is_active'        => true,
            ]
        );

        /*
         * ======================
         * SHELF
         * ======================
         */
        $shelfA1S1 = Location::updateOrCreate(
            [
                'warehouse_id' => $warehouse->id,
                'code'         => 'A-R1-S1',
            ],
            [
                'parent_id'        => $rackA1->id,
                'location_type_id'=> $shelfType->id,
                'name'             => 'Shelf 1',
                'description'      => 'Shelf 1 di Rack A1',
                'is_active'        => true,
            ]
        );

        /*
         * ======================
         * BIN
         * ======================
         */
        $bins = ['B01', 'B02', 'B03'];

        foreach ($bins as $binCode) {
            Location::updateOrCreate(
                [
                    'warehouse_id' => $warehouse->id,
                    'code'         => "A-R1-S1-$binCode",
                ],
                [
                    'parent_id'        => $shelfA1S1->id,
                    'location_type_id'=> $binType->id,
                    'name'             => "Bin $binCode",
                    'description'      => "Bin $binCode di Shelf 1",
                    'is_active'        => true,
                ]
            );
        }
    }
}
