<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OnlineOrderingManagementController extends Controller
{
    public function onlineOrderingDashboardIndex()
    {
        // Dummy Config
        $config = (object) ['enabled' => true]; // Set to false to test disabled state

        // Dummy Analytics
        $analytics = (object) [
            'todayOrders' => 12,
            'todayRevenue' => 15450.00,
            'averageOrderValue' => 1287.50,
            'pendingOrders' => 3
        ];

        // Dummy Recent Orders
        $recentOrders = [
            (object) [
                'id' => '101',
                'orderNumber' => 'ORD-2024-001',
                'status' => 'pending',
                'createdAt' => now()->subMinutes(10)->toIso8601String(),
                'customer' => (object) ['name' => 'John Doe'],
                'pickup' => (object) ['outletName' => 'Downtown Bakery', 'scheduledDate' => now()->addDay()->format('Y-m-d'), 'scheduledTime' => '14:00'],
                'items' => [(object) [], (object) []], // just for count
                'summary' => (object) ['total' => 627.00]
            ],
            (object) [
                'id' => '102',
                'orderNumber' => 'ORD-2024-002',
                'status' => 'confirmed',
                'createdAt' => now()->subMinutes(45)->toIso8601String(),
                'customer' => (object) ['name' => 'Jane Smith'],
                'pickup' => (object) ['outletName' => 'Westside Cafe', 'scheduledDate' => now()->format('Y-m-d'), 'scheduledTime' => '10:30'],
                'items' => [(object) []],
                'summary' => (object) ['total' => 297.00]
            ],
            (object) [
                'id' => '103',
                'orderNumber' => 'ORD-2024-003',
                'status' => 'ready',
                'createdAt' => now()->subHours(2)->toIso8601String(),
                'customer' => (object) ['name' => 'Alice Johnson'],
                'pickup' => (object) ['outletName' => 'Downtown Bakery', 'scheduledDate' => now()->format('Y-m-d'), 'scheduledTime' => '12:00'],
                'items' => [(object) [], (object) []],
                'summary' => (object) ['total' => 715.00]
            ],
            (object) [
                'id' => '105',
                'orderNumber' => 'ORD-2024-005',
                'status' => 'completed',
                'createdAt' => now()->subDay()->toIso8601String(),
                'customer' => (object) ['name' => 'Emily Brown'],
                'pickup' => (object) ['outletName' => 'Westside Cafe', 'scheduledDate' => now()->subDay()->format('Y-m-d'), 'scheduledTime' => '09:00'],
                'items' => [(object) []],
                'summary' => (object) ['total' => 1485.00]
            ],
        ];

        return view('onlineOrdering.onlineDashboard', compact('analytics', 'recentOrders', 'config'));
    }

    public function onlineOrderManagementIndex()
    {
        // Dummy Locations
        $locations = [
            (object) ['id' => '1', 'name' => 'Downtown Bakery', 'hasRetail' => true],
            (object) ['id' => '2', 'name' => 'Westside Cafe', 'hasRetail' => true],
            (object) ['id' => '3', 'name' => 'Mall Kiosk', 'hasRetail' => true],
        ];

        // Dummy Orders
        $orders = [
            (object) [
                'id' => '101',
                'orderNumber' => 'ORD-2024-001',
                'status' => 'pending',
                'createdAt' => now()->subMinutes(10)->toIso8601String(),
                'customer' => (object) [
                    'name' => 'John Doe',
                    'phone' => '+1 555-0123',
                    'email' => 'john@example.com',
                    'isGuest' => false
                ],
                'pickup' => (object) [
                    'outletId' => '1',
                    'outletName' => 'Downtown Bakery',
                    'scheduledDate' => now()->addDay()->format('Y-m-d'),
                    'scheduledTime' => '14:00'
                ],
                'items' => [
                    (object) ['productName' => 'Chocolate Cake', 'quantity' => 1, 'subtotal' => 450.00, 'notes' => 'Happy Birthday msg'],
                    (object) ['productName' => 'Croissant', 'quantity' => 2, 'subtotal' => 120.00, 'notes' => ''],
                ],
                'summary' => (object) [
                    'subtotal' => 570.00,
                    'discount' => 0,
                    'tax' => 57.00,
                    'total' => 627.00
                ],
                'payment' => (object) ['method' => 'Card', 'status' => 'succeeded']
            ],
            (object) [
                'id' => '102',
                'orderNumber' => 'ORD-2024-002',
                'status' => 'confirmed',
                'createdAt' => now()->subMinutes(45)->toIso8601String(),
                'customer' => (object) [
                    'name' => 'Jane Smith',
                    'phone' => '+1 555-0124',
                    'email' => 'jane@example.com',
                    'isGuest' => true
                ],
                'pickup' => (object) [
                    'outletId' => '2',
                    'outletName' => 'Westside Cafe',
                    'scheduledDate' => now()->format('Y-m-d'),
                    'scheduledTime' => '10:30'
                ],
                'items' => [
                    (object) ['productName' => 'Sourdough Bread', 'quantity' => 2, 'subtotal' => 300.00, 'notes' => 'Sliced'],
                ],
                'summary' => (object) [
                    'subtotal' => 300.00,
                    'discount' => 30.00,
                    'tax' => 27.00,
                    'total' => 297.00
                ],
                'payment' => (object) ['method' => 'Cash', 'status' => 'pending']
            ],
            (object) [
                'id' => '103',
                'orderNumber' => 'ORD-2024-003',
                'status' => 'ready',
                'createdAt' => now()->subHours(2)->toIso8601String(),
                'customer' => (object) [
                    'name' => 'Alice Johnson',
                    'phone' => '+1 555-0125',
                    'email' => null,
                    'isGuest' => false
                ],
                'pickup' => (object) [
                    'outletId' => '1',
                    'outletName' => 'Downtown Bakery',
                    'scheduledDate' => now()->format('Y-m-d'),
                    'scheduledTime' => '12:00'
                ],
                'items' => [
                    (object) ['productName' => 'Bagel Box', 'quantity' => 1, 'subtotal' => 250.00, 'notes' => ''],
                    (object) ['productName' => 'Coffee', 'quantity' => 4, 'subtotal' => 400.00, 'notes' => 'Sugar on side'],
                ],
                'summary' => (object) [
                    'subtotal' => 650.00,
                    'discount' => 0,
                    'tax' => 65.00,
                    'total' => 715.00
                ],
                'payment' => (object) ['method' => 'Card', 'status' => 'succeeded']
            ],
            (object) [
                'id' => '104',
                'orderNumber' => 'ORD-2024-004',
                'status' => 'preparing',
                'createdAt' => now()->subMinutes(20)->toIso8601String(),
                'customer' => (object) [
                    'name' => 'Robert Wilson',
                    'phone' => '+1 555-0126',
                    'email' => 'rob@test.com',
                    'isGuest' => false
                ],
                'pickup' => (object) [
                    'outletId' => '3',
                    'outletName' => 'Mall Kiosk',
                    'scheduledDate' => now()->format('Y-m-d'),
                    'scheduledTime' => '16:00'
                ],
                'items' => [
                    (object) ['productName' => 'Red Velvet Cupcake', 'quantity' => 6, 'subtotal' => 900.00, 'notes' => ''],
                ],
                'summary' => (object) [
                    'subtotal' => 900.00,
                    'discount' => 0,
                    'tax' => 90.00,
                    'total' => 990.00
                ],
                'payment' => (object) ['method' => 'Card', 'status' => 'succeeded']
            ],
            (object) [
                'id' => '105',
                'orderNumber' => 'ORD-2024-005',
                'status' => 'completed',
                'createdAt' => now()->subDay()->toIso8601String(),
                'customer' => (object) [
                    'name' => 'Emily Brown',
                    'phone' => '+1 555-0127',
                    'email' => 'emily@brown.com',
                    'isGuest' => false
                ],
                'pickup' => (object) [
                    'outletId' => '2',
                    'outletName' => 'Westside Cafe',
                    'scheduledDate' => now()->subDay()->format('Y-m-d'),
                    'scheduledTime' => '09:00'
                ],
                'items' => [
                    (object) ['productName' => 'Sandwich Platter', 'quantity' => 1, 'subtotal' => 1500.00, 'notes' => 'No mayo'],
                ],
                'summary' => (object) [
                    'subtotal' => 1500.00,
                    'discount' => 150.00,
                    'tax' => 135.00,
                    'total' => 1485.00
                ],
                'payment' => (object) ['method' => 'Card', 'status' => 'succeeded']
            ],
        ];

        return view('onlineOrdering.orderManagement', compact('orders', 'locations'));
    }

    public function onlineOrderSettingsIndex()
    {
        // Dummy Config for Settings
        $config = (object) [
            'enabled' => true,
            'storeName' => 'Your Bakery',
            'contactEmail' => 'contact@yourbakery.com',
            'contactPhone' => '011-234-5678',
            'currency' => 'LKR',
            'storeDescription' => 'Welcome to our online bakery...',
            'taxRate' => 0.10, // 10%
            'globalOrderSettings' => (object) [
                'minOrderValue' => 1500.00,
                'allowGuestCheckout' => true,
            ],
            'defaultPickupSettings' => (object) [
                'advanceOrderMinutes' => 60,
            ],
            'paymentMethods' => (object) [
                'online' => (object) ['enabled' => true],
                'cashOnPickup' => (object) ['enabled' => true],
                'cardOnPickup' => (object) ['enabled' => false],
            ],
        ];

        // Dummy Locations for Outlook Configuration
        $locations = [
            (object) ['id' => '1', 'name' => 'Downtown Bakery', 'hasRetail' => true, 'address' => '123 Main St', 'enabled' => true],
            (object) ['id' => '2', 'name' => 'Westside Cafe', 'hasRetail' => true, 'address' => '456 West Ave', 'enabled' => false],
            (object) ['id' => '3', 'name' => 'Mall Kiosk', 'hasRetail' => true, 'address' => '789 Mall Rd', 'enabled' => true],
        ];


        return view('onlineOrdering.onlineSettings', compact('config', 'locations'));
    }
}
