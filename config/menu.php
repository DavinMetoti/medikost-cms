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
                    'permission' => 'view landing page', // Basic permission for dashboard
                ],
            ],
        ],
        [
            'title' => 'Product Management',
            'children' => [
                [
                    'title' => 'Kost',
                    'icon' => 'fas fa-box-open',
                    'father' => '',
                    'route' => 'app.products.index',
                    'permission' => 'view kos',
                ],
                [
                    'title' => 'Kost Details',
                    'icon' => 'fas fa-list',
                    'father' => '',
                    'route' => 'app.product-details.index',
                    'permission' => 'view rooms',
                ],
                // [
                //     'title' => 'Bookings',
                //     'icon' => 'fas fa-calendar-check',
                //     'father' => '',
                //     'route' => 'app.bookings.index',
                //     'permission' => 'view rooms', // Assuming bookings are part of room management
                // ],
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
                    'permission' => 'view users',
                ],
            ],
        ],
    ],
];
