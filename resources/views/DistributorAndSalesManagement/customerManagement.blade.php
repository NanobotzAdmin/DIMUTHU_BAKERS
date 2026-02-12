@extends('layouts.app')

@section('content')

    {{--
    ==========================================================================
    MOCK DATA & HELPERS
    (In a real app, this logic belongs in your Controller)
    ==========================================================================
    --}}
    @php
        // 1. Statistics
        $stats = [
            'totalCustomers' => 1240,
            'activeCustomers' => 850,
            'vipCustomers' => 45,
            'totalRevenue' => 12500000,
            'averageLifetimeValue' => 15000,
            'averageRating' => 4.8,
            'totalFeedback' => 128,
            'upcomingOccasions' => 12,
            'totalLoyaltyPoints' => 45000
        ];

        // 2. Mock Customers Data
        $customers = [
            (object) [
                'id' => 1,
                'name' => 'John Doe',
                'type' => 'retail',
                'status' => 'vip',
                'code' => 'CUS-001',
                'email' => 'john@example.com',
                'phone' => '0771234567',
                'spent' => 45000,
                'orders' => 12,
                'loyaltyTier' => 'platinum',
                'loyaltyPoints' => 5200,
                'firstPurchase' => '2023-01-15',
                'pointsEarned' => 6000,
                'pointsRedeemed' => 800,
                'creditReport' => null
            ],
            (object) [
                'id' => 2,
                'name' => 'ABC Corp',
                'type' => 'corporate',
                'status' => 'active',
                'code' => 'COR-089',
                'email' => 'purchasing@abc.com',
                'phone' => '0112233445',
                'spent' => 125000,
                'orders' => 5,
                'loyaltyTier' => 'gold',
                'loyaltyPoints' => 2100,
                'firstPurchase' => '2023-03-10',
                'pointsEarned' => 2500,
                'pointsRedeemed' => 400,
                'creditReport' => (object) [
                    'riskLevel' => 'low',
                    'creditScore' => 85,
                    'paymentTerm' => 'net-30',
                    'creditLimit' => 500000,
                    'currentBalance' => 45000,
                    'availableCredit' => 455000,
                    'overdueAmount' => 0,
                    'paymentHistory' => (object) ['onTime' => 12, 'late' => 0, 'missed' => 0]
                ]
            ],
            (object) [
                'id' => 3,
                'name' => 'City Bakers',
                'type' => 'wholesale',
                'status' => 'active',
                'code' => 'WHO-112',
                'email' => 'orders@citybakers.lk',
                'phone' => '0714455667',
                'spent' => 890000,
                'orders' => 45,
                'loyaltyTier' => 'silver',
                'loyaltyPoints' => 800,
                'firstPurchase' => '2022-11-05',
                'pointsEarned' => 1000,
                'pointsRedeemed' => 200,
                'creditReport' => (object) [
                    'riskLevel' => 'medium',
                    'creditScore' => 65,
                    'paymentTerm' => 'net-15',
                    'creditLimit' => 200000,
                    'currentBalance' => 150000,
                    'availableCredit' => 50000,
                    'overdueAmount' => 25000,
                    'paymentHistory' => (object) ['onTime' => 8, 'late' => 4, 'missed' => 1]
                ]
            ]
        ];

        // 3. Mock Purchase History
        $purchaseHistory = [
            (object) [
                'id' => 101,
                'customerName' => 'John Doe',
                'orderId' => 'ORD-998',
                'date' => '2023-10-25',
                'status' => 'paid',
                'total' => 15000,
                'items' => 3,
                'method' => 'Credit Card'
            ],
            (object) [
                'id' => 102,
                'customerName' => 'ABC Corp',
                'orderId' => 'ORD-999',
                'date' => '2023-10-24',
                'status' => 'pending',
                'total' => 45000,
                'items' => 12,
                'method' => 'Bank Transfer'
            ]
        ];

        // 4. Mock Communications
        $communications = [
            (object) [
                'id' => 1,
                'customerName' => 'John Doe',
                'direction' => 'outbound',
                'type' => 'email',
                'status' => 'completed',
                'subject' => 'Welcome to VIP Tier',
                'date' => '2023-10-01',
                'summary' => 'Sent welcome email for upgrading to Platinum tier.',
                'agent' => 'Admin'
            ],
            (object) [
                'id' => 2,
                'customerName' => 'City Bakers',
                'direction' => 'inbound',
                'type' => 'phone',
                'status' => 'pending',
                'subject' => 'Payment Extension Request',
                'date' => '2023-10-26',
                'summary' => 'Customer requested 5 more days to settle overdue invoice.',
                'agent' => 'Accounts',
                'followUp' => '2023-10-31'
            ]
        ];

        // 5. Mock Occasions
        $occasions = [
            (object) ['id' => 1, 'customerName' => 'John Doe', 'type' => 'birthday', 'days' => 2, 'date' => 'Oct 28'],
            (object) ['id' => 2, 'customerName' => 'ABC Corp', 'type' => 'business-anniversary', 'days' => 15, 'date' => 'Nov 12'],
        ];

        // 6. Mock Feedback
        $feedback = [
            (object) ['id' => 1, 'customerName' => 'John Doe', 'rating' => 5, 'sentiment' => 'positive', 'comment' => 'Great service!', 'date' => '2 days ago'],
            (object) ['id' => 2, 'customerName' => 'Anonymous', 'rating' => 3, 'sentiment' => 'neutral', 'comment' => 'Delivery was okay but late.', 'date' => '1 week ago'],
            (object) ['id' => 3, 'customerName' => 'Angry User', 'rating' => 1, 'sentiment' => 'negative', 'comment' => 'Wrong items delivered.', 'date' => 'Yesterday']
        ];

        // Helpers
        function getStatusColor($status)
        {
            return match ($status) {
                'active' => 'bg-green-100 text-green-700 border-green-300',
                'inactive' => 'bg-gray-100 text-gray-700 border-gray-300',
                'vip' => 'bg-purple-100 text-purple-700 border-purple-300',
                default => 'bg-gray-100 text-gray-700',
            };
        }
        function getTypeColor($type)
        {
            return match ($type) {
                'wholesale' => 'from-blue-500 to-indigo-600',
                'retail' => 'from-green-500 to-emerald-600',
                'corporate' => 'from-purple-500 to-violet-600',
                default => 'from-gray-500 to-gray-600',
            };
        }
    @endphp

    {{-- Lucide Icons Script --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 p-4 md:p-6">

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="users" class="w-8 h-8 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Customer Management</h1>
                        <p class="text-gray-600">Comprehensive CRM & relationship management</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button
                        class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                        <i data-lucide="download" class="w-5 h-5"></i> Export
                    </button>
                    <button id="btn-add-customer"
                        class="h-12 px-5 bg-gradient-to-br from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                        <i data-lucide="user-plus" class="w-5 h-5"></i> Add Customer
                    </button>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-purple-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Total Customers</span>
                        <i data-lucide="users" class="w-5 h-5 text-purple-500"></i>
                    </div>
                    <div class="text-3xl font-bold text-purple-600">{{ $stats['totalCustomers'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $stats['activeCustomers'] }} active</div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-pink-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">VIP Customers</span>
                        <i data-lucide="crown" class="w-5 h-5 text-pink-500"></i>
                    </div>
                    <div class="text-3xl font-bold text-pink-600">{{ $stats['vipCustomers'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Premium tier</div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Revenue</span>
                        <i data-lucide="dollar-sign" class="w-5 h-5 text-blue-500"></i>
                    </div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['totalRevenue'] / 1000000, 1) }}M
                    </div>
                    <div class="text-sm text-gray-500 mt-1">Lifetime value</div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Avg LTV</span>
                        <i data-lucide="target" class="w-5 h-5 text-green-500"></i>
                    </div>
                    <div class="text-3xl font-bold text-green-600">
                        {{ number_format($stats['averageLifetimeValue'] / 1000, 0) }}K
                    </div>
                    <div class="text-sm text-gray-500 mt-1">Per customer</div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-orange-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Avg Rating</span>
                        <i data-lucide="star" class="w-5 h-5 text-orange-500"></i>
                    </div>
                    <div class="text-3xl font-bold text-orange-600">{{ $stats['averageRating'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $stats['totalFeedback'] }} reviews</div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-indigo-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Upcoming</span>
                        <i data-lucide="cake" class="w-5 h-5 text-indigo-500"></i>
                    </div>
                    <div class="text-3xl font-bold text-indigo-600">{{ $stats['upcomingOccasions'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Occasions (30d)</div>
                </div>
            </div>

            {{-- Tabs Navigation --}}
            <div class="flex gap-2 mb-4 overflow-x-auto pb-2" id="tabs-container">
                <button onclick="switchTab('overview')"
                    class="tab-btn active flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[140px] bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg"
                    data-target="overview">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Overview</span>
                </button>
                <button onclick="switchTab('database')"
                    class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[180px] bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200"
                    data-target="database">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Database</span>
                    <span
                        class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full text-xs font-bold">{{ $stats['totalCustomers'] }}</span>
                </button>
                <button onclick="switchTab('segments')"
                    class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[140px] bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200"
                    data-target="segments">
                    <i data-lucide="target" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Segments</span>
                </button>
                <button onclick="switchTab('purchase-history')"
                    class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[160px] bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200"
                    data-target="purchase-history">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Purchases</span>
                </button>
                <button onclick="switchTab('credit-payments')"
                    class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[160px] bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200"
                    data-target="credit-payments">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Credit</span>
                </button>
                <button onclick="switchTab('loyalty')"
                    class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[140px] bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200"
                    data-target="loyalty">
                    <i data-lucide="gift" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Loyalty</span>
                </button>
                <button onclick="switchTab('communications')"
                    class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[160px] bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200"
                    data-target="communications">
                    <i data-lucide="message-square" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Comms</span>
                </button>
                <button onclick="switchTab('occasions')"
                    class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[140px] bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200"
                    data-target="occasions">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Occasions</span>
                </button>
                <button onclick="switchTab('feedback')"
                    class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[140px] bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200"
                    data-target="feedback">
                    <i data-lucide="star" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Feedback</span>
                </button>
            </div>
        </div>

        {{--
        ================================================
        TAB CONTENT: OVERVIEW
        ================================================
        --}}
        <div id="overview" class="tab-content block space-y-4">
            {{-- Segments Overview --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Customer Segments Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Wholesale Segment --}}
                    <div class="bg-gradient-to-br from-gray-50 to-purple-50 rounded-xl p-5 border-2 border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="building" class="w-6 h-6 text-white"></i>
                            </div>
                            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold">15%</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Wholesale</h3>
                        <div class="text-2xl font-bold text-purple-600 mb-2">120 customers</div>
                        <div class="text-sm text-gray-600">Revenue: Rs 5.2M</div>
                    </div>
                    {{-- Retail Segment --}}
                    <div class="bg-gradient-to-br from-gray-50 to-purple-50 rounded-xl p-5 border-2 border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="shopping-bag" class="w-6 h-6 text-white"></i>
                            </div>
                            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold">75%</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Retail</h3>
                        <div class="text-2xl font-bold text-purple-600 mb-2">950 customers</div>
                        <div class="text-sm text-gray-600">Revenue: Rs 3.8M</div>
                    </div>
                    {{-- Corporate Segment --}}
                    <div class="bg-gradient-to-br from-gray-50 to-purple-50 rounded-xl p-5 border-2 border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="briefcase" class="w-6 h-6 text-white"></i>
                            </div>
                            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold">10%</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Corporate</h3>
                        <div class="text-2xl font-bold text-purple-600 mb-2">170 customers</div>
                        <div class="text-sm text-gray-600">Revenue: Rs 3.5M</div>
                    </div>
                </div>
            </div>

            {{-- Top Customers --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Top Customers</h2>
                    <div class="space-y-3">
                        @foreach($customers as $index => $customer)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center flex-shrink-0 text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div
                                    class="w-10 h-10 bg-gradient-to-br {{ getTypeColor($customer->type) }} rounded-lg flex items-center justify-center">
                                    <i data-lucide="user" class="w-5 h-5 text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $customer->orders }} orders</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-purple-600">Rs {{ number_format($customer->spent / 1000, 0) }}K
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Upcoming Occasions</h2>
                    <div class="space-y-3">
                        @foreach($occasions as $occasion)
                            <div
                                class="flex items-center gap-3 p-3 bg-gradient-to-r from-pink-50 to-purple-50 rounded-xl border-2 border-pink-100">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <i data-lucide="{{ $occasion->type == 'birthday' ? 'cake' : 'party-popper' }}"
                                        class="w-5 h-5 text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $occasion->customerName }}</div>
                                    <div class="text-sm text-gray-600 capitalize">{{ str_replace('-', ' ', $occasion->type) }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-pink-600">{{ $occasion->days }}d</div>
                                    <div class="text-xs text-gray-500">{{ $occasion->date }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{--
        ================================================
        TAB CONTENT: CUSTOMER DATABASE
        ================================================
        --}}
        <div id="database" class="tab-content hidden space-y-4">
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100 flex gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1 block">Type</label>
                    <select class="px-4 py-2 border-2 border-gray-200 rounded-lg outline-none w-full md:w-auto">
                        <option value="all">All Types</option>
                        <option value="wholesale">Wholesale</option>
                        <option value="retail">Retail</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1 block">Status</label>
                    <select class="px-4 py-2 border-2 border-gray-200 rounded-lg outline-none w-full md:w-auto">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="vip">VIP</option>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($customers as $customer)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100 hover:shadow-md transition-all">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br {{ getTypeColor($customer->type) }} rounded-xl flex items-center justify-center">
                                <i data-lucide="user" class="w-7 h-7 text-white"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between mb-2">
                                    <div>
                                        <div class="flex items-center gap-3 mb-1">
                                            <h3 class="text-xl font-bold text-gray-900">{{ $customer->name }}</h3>
                                            <span
                                                class="{{ getStatusColor($customer->status) }} border capitalize px-2 py-0.5 rounded text-xs font-bold">{{ $customer->status }}</span>
                                            <span
                                                class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">{{ $customer->code }}</span>
                                        </div>
                                        <div class="flex gap-4 text-sm text-gray-600">
                                            <div class="flex items-center gap-1"><i data-lucide="mail" class="w-4 h-4"></i>
                                                {{ $customer->email }}</div>
                                            <div class="flex items-center gap-1"><i data-lucide="phone" class="w-4 h-4"></i>
                                                {{ $customer->phone }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-600">Total Spent</div>
                                        <div class="text-2xl font-bold text-purple-600">Rs
                                            {{ number_format($customer->spent / 1000, 0) }}K
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $customer->orders }} orders</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{--
        ================================================
        TAB CONTENT: SEGMENTS
        ================================================
        --}}
        <div id="segments" class="tab-content hidden space-y-4">
            {{-- Repeating the segments cards with more detail --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="building" class="w-7 h-7 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Wholesale Customers</h3>
                            <p class="text-gray-600">120 total customers • 85 active</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold">+12%
                                    Growth</span>
                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-bold">95%
                                    Retention</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Total Revenue</div>
                        <div class="text-3xl font-bold text-purple-600">Rs 5.20M</div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 mb-4">
                    <div>
                        <div class="text-sm text-gray-600">Avg Order Value</div>
                        <div class="text-xl font-bold text-gray-900">Rs 45,000</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Avg LTV</div>
                        <div class="text-xl font-bold text-gray-900">Rs 890K</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Market Share</div>
                        <div class="text-xl font-bold text-gray-900">42%</div>
                    </div>
                </div>
            </div>
        </div>

        {{--
        ================================================
        TAB CONTENT: PURCHASE HISTORY
        ================================================
        --}}
        <div id="purchase-history" class="tab-content hidden space-y-4">
            @foreach($purchaseHistory as $purchase)
                <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-gray-900">{{ $purchase->customerName }}</h3>
                                <span
                                    class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-bold">{{ $purchase->orderId }}</span>
                                <span
                                    class="{{ $purchase->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} capitalize px-2 py-0.5 rounded text-xs font-bold">{{ $purchase->status }}</span>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1"><i data-lucide="calendar" class="w-4 h-4"></i>
                                    {{ $purchase->date }}</div>
                                <div class="flex items-center gap-1"><i data-lucide="credit-card" class="w-4 h-4"></i>
                                    {{ $purchase->method }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Total Amount</div>
                            <div class="text-3xl font-bold text-purple-600">Rs {{ number_format($purchase->total) }}</div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 mb-3">
                        <div class="font-medium text-gray-700 mb-2">Items ({{ $purchase->items }}):</div>
                        <div class="text-sm text-gray-600">Product details hidden for mock...</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{--
        ================================================
        TAB CONTENT: CREDIT & PAYMENTS
        ================================================
        --}}
        <div id="credit-payments" class="tab-content hidden space-y-4">
            @foreach($customers as $customer)
                @if($customer->creditReport)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $customer->name }}</h3>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="bg-{{ $customer->creditReport->riskLevel == 'low' ? 'green' : ($customer->creditReport->riskLevel == 'medium' ? 'yellow' : 'red') }}-100 text-{{ $customer->creditReport->riskLevel == 'low' ? 'green' : ($customer->creditReport->riskLevel == 'medium' ? 'yellow' : 'red') }}-700 px-2 py-0.5 rounded text-xs font-bold uppercase">{{ $customer->creditReport->riskLevel }}
                                        RISK</span>
                                    <span class="text-sm text-gray-600">Score: {{ $customer->creditReport->creditScore }}/100</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-600">Payment Term</div>
                                <span
                                    class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-bold uppercase">{{ $customer->creditReport->paymentTerm }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border-2 border-blue-100">
                                <div class="text-sm text-gray-600">Credit Limit</div>
                                <div class="text-xl font-bold text-blue-600">Rs
                                    {{ number_format($customer->creditReport->creditLimit) }}
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-4 border-2 border-orange-100">
                                <div class="text-sm text-gray-600">Current Balance</div>
                                <div class="text-xl font-bold text-orange-600">Rs
                                    {{ number_format($customer->creditReport->currentBalance) }}
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border-2 border-green-100">
                                <div class="text-sm text-gray-600">Available</div>
                                <div class="text-xl font-bold text-green-600">Rs
                                    {{ number_format($customer->creditReport->availableCredit) }}
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-100">
                                <div class="text-sm text-gray-600">Overdue</div>
                                <div
                                    class="text-xl font-bold {{ $customer->creditReport->overdueAmount > 0 ? 'text-red-600' : 'text-gray-600' }}">
                                    Rs {{ number_format($customer->creditReport->overdueAmount) }}</div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="font-medium text-gray-700 mb-3">Payment History:</div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                                    <span class="text-gray-600">On Time: <span
                                            class="font-bold text-green-600">{{ $customer->creditReport->paymentHistory->onTime }}</span></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
                                    <span class="text-gray-600">Late: <span
                                            class="font-bold text-yellow-600">{{ $customer->creditReport->paymentHistory->late }}</span></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                                    <span class="text-gray-600">Missed: <span
                                            class="font-bold text-red-600">{{ $customer->creditReport->paymentHistory->missed }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{--
        ================================================
        TAB CONTENT: LOYALTY
        ================================================
        --}}
        <div id="loyalty" class="tab-content hidden space-y-4">
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Loyalty Program Overview</h2>
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl p-4 text-white">
                        <div class="flex justify-between mb-2"><span class="font-bold">Platinum</span><i data-lucide="award"
                                class="w-6 h-6"></i></div>
                        <div class="text-3xl font-bold">12</div>
                        <div class="text-xs opacity-60">customers</div>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl p-4 text-white">
                        <div class="flex justify-between mb-2"><span class="font-bold">Gold</span><i data-lucide="award"
                                class="w-6 h-6"></i></div>
                        <div class="text-3xl font-bold">45</div>
                        <div class="text-xs opacity-60">customers</div>
                    </div>
                    <div class="bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl p-4 text-white">
                        <div class="flex justify-between mb-2"><span class="font-bold">Silver</span><i data-lucide="award"
                                class="w-6 h-6"></i></div>
                        <div class="text-3xl font-bold">120</div>
                        <div class="text-xs opacity-60">customers</div>
                    </div>
                    <div class="bg-gradient-to-br from-orange-600 to-orange-800 rounded-xl p-4 text-white">
                        <div class="flex justify-between mb-2"><span class="font-bold">Bronze</span><i data-lucide="award"
                                class="w-6 h-6"></i></div>
                        <div class="text-3xl font-bold">400</div>
                        <div class="text-xs opacity-60">customers</div>
                    </div>
                </div>
            </div>

            @foreach($customers as $customer)
                <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $customer->name }}</h3>
                            <span
                                class="bg-gray-800 text-white px-2 py-0.5 rounded text-xs uppercase font-bold">{{ $customer->loyaltyTier }}</span>
                            <div class="text-sm text-gray-600 mt-1">Member since {{ $customer->firstPurchase }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Current Points</div>
                            <div class="text-3xl font-bold text-purple-600">{{ number_format($customer->loyaltyPoints) }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4">
                        <div>
                            <div class="text-sm text-gray-600">Points Earned</div>
                            <div class="text-xl font-bold text-green-600">+{{ number_format($customer->pointsEarned) }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Redeemed</div>
                            <div class="text-xl font-bold text-orange-600">-{{ number_format($customer->pointsRedeemed) }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Redemption Rate</div>
                            <div class="text-xl font-bold text-gray-900">
                                {{ number_format(($customer->pointsRedeemed / $customer->pointsEarned) * 100, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{--
        ================================================
        TAB CONTENT: COMMUNICATIONS
        ================================================
        --}}
        <div id="communications" class="tab-content hidden space-y-4">
            @foreach($communications as $comm)
                <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-gray-900">{{ $comm->customerName }}</h3>
                                <span
                                    class="{{ $comm->direction == 'outbound' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }} px-2 py-0.5 rounded text-xs font-bold flex items-center gap-1">
                                    <i data-lucide="{{ $comm->direction == 'outbound' ? 'send' : 'mail' }}" class="w-3 h-3"></i>
                                    {{ $comm->direction }}
                                </span>
                                <span
                                    class="bg-gray-100 text-gray-700 capitalize px-2 py-0.5 rounded text-xs font-bold">{{ $comm->type }}</span>
                            </div>
                            <div class="text-gray-600 mb-1">{{ $comm->subject }}</div>
                            <div class="text-sm text-gray-600">{{ $comm->date }}</div>
                        </div>
                        <span
                            class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-xs font-bold">{{ $comm->agent }}</span>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 mb-3">
                        <p class="text-gray-700">{{ $comm->summary }}</p>
                    </div>
                    @if(isset($comm->followUp))
                        <div class="flex items-center gap-2 text-sm">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-orange-500"></i>
                            <span class="text-orange-600 font-medium">Follow-up required by {{ $comm->followUp }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{--
        ================================================
        TAB CONTENT: OCCASIONS
        ================================================
        --}}
        <div id="occasions" class="tab-content hidden space-y-4">
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">All Special Occasions</h2>
                <div class="space-y-2">
                    @foreach($occasions as $occasion)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="{{ $occasion->type == 'birthday' ? 'cake' : 'party-popper' }}"
                                    class="w-5 h-5 text-pink-500"></i>
                                <div>
                                    <span class="font-medium text-gray-900">{{ $occasion->customerName }}</span>
                                    <span class="text-gray-600 text-sm ml-2">• {{ $occasion->date }}</span>
                                </div>
                            </div>
                            <span
                                class="bg-gray-200 text-gray-700 capitalize px-2 py-0.5 rounded text-xs font-bold">{{ str_replace('-', ' ', $occasion->type) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{--
        ================================================
        TAB CONTENT: FEEDBACK
        ================================================
        --}}
        <div id="feedback" class="tab-content hidden space-y-4">
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Feedback Overview</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border-2 border-green-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-gray-600">Positive</span>
                            <i data-lucide="thumbs-up" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div class="text-3xl font-bold text-green-600">105</div>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-xl p-4 border-2 border-red-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-gray-600">Negative</span>
                            <i data-lucide="thumbs-down" class="w-5 h-5 text-red-600"></i>
                        </div>
                        <div class="text-3xl font-bold text-red-600">8</div>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-4 border-2 border-yellow-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-gray-600">Avg</span>
                            <i data-lucide="star" class="w-5 h-5 text-yellow-600"></i>
                        </div>
                        <div class="text-3xl font-bold text-yellow-600">{{ $stats['averageRating'] }}</div>
                    </div>
                </div>
            </div>

            @foreach($feedback as $fb)
                <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-gray-900">{{ $fb->customerName }}</h3>
                                <div class="flex items-center gap-1">
                                    {{-- Simple star loop --}}
                                    @for($i = 0; $i < 5; $i++)
                                        <i data-lucide="star"
                                            class="w-4 h-4 {{ $i < $fb->rating ? 'text-yellow-500 fill-yellow-500' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <span
                                    class="bg-{{ $fb->sentiment == 'positive' ? 'green' : ($fb->sentiment == 'neutral' ? 'gray' : 'red') }}-100 text-{{ $fb->sentiment == 'positive' ? 'green' : ($fb->sentiment == 'neutral' ? 'gray' : 'red') }}-600 capitalize px-2 py-0.5 rounded text-xs font-bold">{{ $fb->sentiment }}</span>
                            </div>
                            <div class="text-sm text-gray-600">{{ $fb->date }}</div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 mb-3">
                        <p class="text-gray-700">{{ $fb->comment }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{--
    ================================================
    VANILLA JS: TABS LOGIC
    ================================================
    --}}
    <script>
        // 1. Initialize Lucide Icons
        lucide.createIcons();

        // 2. Tab Switching Logic
        function switchTab(tabId) {
            // Hide all tab content
            const allContents = document.querySelectorAll('.tab-content');
            allContents.forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });

            // Show specific tab content
            const targetContent = document.getElementById(tabId);
            if (targetContent) {
                targetContent.classList.remove('hidden');
                targetContent.classList.add('block');
            }

            // Update Button Styles
            const allButtons = document.querySelectorAll('.tab-btn');
            allButtons.forEach(btn => {
                // Reset to inactive state
                btn.className = 'tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[140px] bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200';

                // Fix internal badges if they exist (reset transparency)
                const badge = btn.querySelector('span.rounded-full');
                if (badge) {
                    badge.className = 'bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full text-xs font-bold';
                }
            });

            // Set active button style
            const activeBtn = document.querySelector(`button[data-target="${tabId}"]`);
            if (activeBtn) {
                activeBtn.className = 'tab-btn active flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[140px] bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg';

                // Fix internal badge for active state (white transparent)
                const badge = activeBtn.querySelector('span.rounded-full');
                if (badge) {
                    badge.className = 'bg-white/20 text-white px-2 py-0.5 rounded-full text-xs font-bold';
                }
            }
        }

        // 3. Modal Logic
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('customer-modal');
            const btnAdd = document.getElementById('btn-add-customer');
            const btnClose = document.getElementById('btn-close-modal');
            const btnCancel = document.getElementById('btn-cancel-modal');
            const btnSave = document.getElementById('btn-save-customer');
            const body = document.body;

            // Open Modal
            if (btnAdd) {
                btnAdd.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    body.classList.add('overflow-hidden');
                });
            }

            // Close Modal Helper
            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                body.classList.remove('overflow-hidden');
            };

            if (btnClose) btnClose.addEventListener('click', closeModal);
            if (btnCancel) btnCancel.addEventListener('click', closeModal);

            // Customer Type Toggle
            const typeBtns = document.querySelectorAll('.type-btn');
            const customerTypeInput = document.getElementById('customerType');
            const b2bSection = document.getElementById('b2b-types-section');
            const creditSection = document.getElementById('credit-terms-section');

            typeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const type = btn.getAttribute('data-type');

                    // Update State
                    customerTypeInput.value = type;

                    // Update UI Classes
                    typeBtns.forEach(b => {
                        b.classList.remove('border-[#D4A017]', 'bg-[#D4A017]/5');
                        b.classList.add('border-gray-300', 'hover:border-gray-400');
                    });
                    btn.classList.remove('border-gray-300', 'hover:border-gray-400');
                    btn.classList.add('border-[#D4A017]', 'bg-[#D4A017]/5');

                    // Show/Hide Sections
                    if (type === 'b2b') {
                        if (b2bSection) b2bSection.classList.remove('hidden');
                        if (creditSection) creditSection.classList.remove('hidden');
                    } else {
                        if (b2bSection) b2bSection.classList.add('hidden');
                        if (creditSection) creditSection.classList.add('hidden');
                    }
                });
            });

            // Credit Terms Toggle
            const allowCredit = document.getElementById('allowCredit');
            const creditFields = document.getElementById('credit-fields');
            if (allowCredit) {
                allowCredit.addEventListener('change', (e) => {
                    if (e.target.checked) {
                        creditFields.classList.remove('hidden');
                    } else {
                        creditFields.classList.add('hidden');
                    }
                });
            }

            // Address Search Mock
            const addressSearch = document.getElementById('addressSearch');
            const manualLocBtn = document.getElementById('manual-location-btn');
            const manualLocText = document.getElementById('manual-location-text');
            const selectedLocDisplay = document.getElementById('selected-location-display');
            const locationAddress = document.getElementById('location-address');
            const btnClearLoc = document.getElementById('btn-clear-location');

            if (addressSearch) {
                addressSearch.addEventListener('input', (e) => {
                    const val = e.target.value;
                    if (val.length > 5) {
                        manualLocBtn.classList.remove('hidden');
                        manualLocText.textContent = `Use "${val.substring(0, 30)}${val.length > 30 ? '...' : ''}" as location`;
                    } else {
                        manualLocBtn.classList.add('hidden');
                    }
                });
            }

            if (manualLocBtn) {
                manualLocBtn.addEventListener('click', () => {
                    const val = addressSearch.value;
                    selectedLocDisplay.classList.remove('hidden');
                    locationAddress.textContent = val;
                    manualLocBtn.classList.add('hidden');
                });
            }

            if (btnClearLoc) {
                btnClearLoc.addEventListener('click', () => {
                    selectedLocDisplay.classList.add('hidden');
                    addressSearch.value = '';
                });
            }

            // Day Buttons Toggle
            const dayBtns = document.querySelectorAll('.day-btn');
            dayBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (btn.classList.contains('bg-gray-100')) {
                        btn.classList.remove('bg-gray-100', 'text-gray-600');
                        btn.classList.add('bg-[#D4A017]', 'text-white');
                    } else {
                        btn.classList.add('bg-gray-100', 'text-gray-600');
                        btn.classList.remove('bg-[#D4A017]', 'text-white');
                    }
                });
            });

            // Save Button (Validation Mock)
            if (btnSave) {
                btnSave.addEventListener('click', () => {
                    let isValid = true;

                    // Reset Errors
                    document.querySelectorAll('.error-msg').forEach(el => el.classList.add('hidden'));
                    document.querySelectorAll('input').forEach(el => el.classList.remove('border-red-500'));

                    const busName = document.getElementById('businessName');
                    const contact = document.getElementById('contactPerson');
                    const phone = document.getElementById('phoneNumber');

                    if (!busName.value.trim()) {
                        busName.classList.add('border-red-500');
                        document.getElementById('err-businessName').classList.remove('hidden');
                        isValid = false;
                    }
                    if (!contact.value.trim()) {
                        contact.classList.add('border-red-500');
                        document.getElementById('err-contactPerson').classList.remove('hidden');
                        isValid = false;
                    }
                    if (!phone.value.trim()) {
                        phone.classList.add('border-red-500');
                        document.getElementById('err-phoneNumber').classList.remove('hidden');
                        isValid = false;
                    }

                    if (isValid) {
                        // Prepare Data Payload
                        const customerType = document.getElementById('customerType').value;
                        const b2bType = document.getElementById('b2bType').value;

                        const payload = {
                            customerType: customerType,
                            b2bType: b2bType,
                            businessName: busName.value.trim(),
                            contact: {
                                contactPerson: contact.value.trim(),
                                phoneNumber: phone.value.trim(),
                                email: document.getElementById('email').value,
                                alternatePhone: document.getElementById('alternatePhone').value
                            },
                            location: {
                                address: document.getElementById('location-address').textContent === 'Selected Address' ? '' : document.getElementById('location-address').textContent,
                                latitude: 6.9271, // Mock for now, or get from map selection if implemented
                                longitude: 79.8612
                            },
                            // Credit Terms
                            creditTerms: {
                                allowCredit: document.getElementById('allowCredit').checked,
                                creditLimit: document.getElementById('creditLimit').value || 0,
                                paymentTermsDays: document.getElementById('paymentTermsDays').value || 30
                            },
                            // Visit Schedule
                            visitSchedule: {
                                frequency: document.getElementById('visitFrequency').value,
                                preferredTime: document.getElementById('preferredTime').value,
                                preferredDays: Array.from(document.querySelectorAll('.day-btn')).filter(btn => btn.classList.contains('bg-[#D4A017]')).map(btn => btn.innerText.trim())
                            },
                            // Assignment
                            assignedAgentId: document.getElementById('assignedAgentId').value,
                            assignedRouteId: document.getElementById('assignedRouteId').value,
                            stopSequence: document.getElementById('stopSequence').value,

                            // Other
                            specialInstructions: '',
                            deliveryInstructions: '',
                            notes: document.getElementById('notes').value
                        };

                        // Send Request
                        const saveBtn = document.getElementById('btn-save-customer');
                        const originalBtnText = saveBtn.innerHTML;
                        saveBtn.disabled = true;
                        saveBtn.innerHTML = '<i class="animate-spin" data-lucide="loader-2"></i> Saving...';
                        lucide.createIcons();

                        fetch('{{ route("customers.create") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(payload)
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Success
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Customer created successfully',
                                        icon: 'success',
                                        confirmButtonColor: '#D4A017'
                                    }).then(() => {
                                        closeModal();
                                        location.reload(); // Reload to see new data
                                    });
                                } else {
                                    // Backend Error
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.message || 'Something went wrong',
                                        icon: 'error',
                                        confirmButtonColor: '#d33'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Network error or server issue',
                                    icon: 'error',
                                    confirmButtonColor: '#d33'
                                });
                            })
                            .finally(() => {
                                saveBtn.disabled = false;
                                saveBtn.innerHTML = originalBtnText;
                                lucide.createIcons();
                            });
                    }
                });
            }

            // Initialize Lucide icons for the modal contents
            lucide.createIcons();
        });
    </script>

    {{--
    ================================================
    MODAL: ADD/EDIT CUSTOMER
    ================================================
    --}}
    @include('DistributorAndSalesManagement.modals.addCustomerModal')

@endsection