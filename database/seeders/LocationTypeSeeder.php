<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LocationType;

class LocationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'code'  => 'AREA',
                'name'  => 'Area',
                'level_order'=> 1,
                'is_active'  => true,
            ],
            [
                'code'  => 'RACK',
                'name'  => 'Rack',
                'level_order'=> 2,
                'is_active'  => true,
            ],
            [
                'code'  => 'SHELF',
                'name'  => 'Shelf',
                'level_order'=> 3,
                'is_active'  => true,
            ],
            [
                'code'  => 'BIN',
                'name'  => 'Bin',
                'level_order'=> 4,
                'is_active'  => true,
            ],
        ];

        foreach ($types as $type) {
            LocationType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
