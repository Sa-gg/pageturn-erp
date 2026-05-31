@extends('layouts.app')

@section('title', 'My Profile | PageTurn Books')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex justify-between items-end mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Account</h1>
        <a href="/logout" class="text-red-500 font-medium hover:underline">Sign Out</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8 rounded-md">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 text-center border-b border-gray-100">
                    <img class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-brand-cream" src="https://ui-avatars.com/api/?name={{ urlencode($user['name'] ?? 'User') }}&size=128&color=fff&background=5C4033" alt="Avatar">
                    <h2 class="text-xl font-bold text-gray-900">{{ $user['name'] ?? 'Customer' }}</h2>
                    <p class="text-gray-500">{{ $user['email'] ?? 'customer@example.com' }}</p>
                    <p class="mt-2 text-xs font-semibold text-brand-forest bg-green-50 inline-block px-2 py-1 rounded">Role: {{ $user['role'] ?? 'user' }}</p>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-gray-900 mb-3">Account Details</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex justify-between"><span>Member Since:</span> <span class="font-medium text-gray-900">Today</span></li>
                        <li class="flex justify-between"><span>Total Orders:</span> <span class="font-medium text-gray-900">{{ count($orders['data'] ?? []) }}</span></li>
                    </ul>
                    
                    @if(in_array(($user['role'] ?? ''), ['admin', 'super_admin', 'catalog_admin', 'orders_admin', 'inventory_admin', 'finance_admin', 'staff']))
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <a href="/admin/dashboard" class="w-full btn-secondary text-center block">Go to Admin Dashboard</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-900">Order History & Tracking</h2>
                </div>
                
                @if(empty($orders) || empty($orders['data']))
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <p class="text-gray-500">You haven't placed any orders yet.</p>
                        <a href="/catalog" class="text-brand-forest mt-2 inline-block hover:underline font-medium">Start Shopping</a>
                    </div>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($orders['data'] as $order)
                        <li class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Order #{{ $order['order_number'] }}</h3>
                                    <p class="text-sm text-gray-500">Placed on {{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y') }}</p>
                                </div>
                                <div class="mt-2 sm:mt-0">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                        @if($order['status'] === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order['status'] === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order['status'] === 'processing') bg-indigo-100 text-indigo-800
                                        @elseif($order['status'] === 'shipped') bg-purple-100 text-purple-800
                                        @elseif($order['status'] === 'delivered') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif
                                    ">
                                        {{ $order['status'] }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center text-sm border-t border-gray-100 pt-4 mt-2">
                                <div class="text-gray-600">
                                    {{ count($order['items'] ?? []) }} items &bull; Total: <span class="font-bold text-gray-900">${{ number_format($order['total'], 2) }}</span>
                                </div>
                                <div>
                                    <button class="text-brand-forest font-medium hover:underline">View Details</button>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
