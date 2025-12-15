<?php

return [
    'sidebar' => [
        [
            'title' => 'Dashboard',
            'children' => [
                [
                    'title' => 'Overview',
                    'icon' => 'fas fa-tachometer-alt',
                    'father' => '',
                    'route' => 'app.dashboard.index',
                ],
            ],
        ],
        [
            'title' => 'Inventory Management',
            'children' => [
                [
                    'title' => 'Warehouses',
                    'icon' => 'fas fa-warehouse',
                    'father' => '',
                    'route' => 'app.warehouses.index',
                ],
                [
                    'title' => 'Locations Type',
                    'icon' => 'fas fa-boxes',
                    'father' => '',
                    'route' => 'app.location-types.index',
                ],
                [
                    'title' => 'Locations',
                    'icon' => 'fas fa-map-marker-alt',
                    'father' => '',
                    'route' => 'app.locations.index',
                ],
                [
                    'title' => 'Products',
                    'icon' => 'fas fa-box-open',
                    'father' => '',
                    'route' => 'app.products.index',
                ],
                [
                    'title' => 'Categories',
                    'icon' => 'fas fa-tags',
                    'father' => '',
                    'route' => 'app.categories.index',
                ],
                [
                    'title' => 'Suppliers',
                    'icon' => 'fas fa-truck',
                    'father' => '',
                    'route' => 'app.suppliers.index',
                ],
                [
                    'title' => 'Units of Measurement',
                    'icon' => 'fas fa-ruler-combined',
                    'father' => '',
                    'route' => 'app.uom.index',
                ],
            ],
        ],
        [
            'title' => 'User Management',
            'children' => [
                [
                    'title' => 'Users',
                    'icon' => 'fas fa-users',
                    'father' => '',
                    'route' => 'user-management.users.index',
                ],
            ],
        ],
    ],
];
