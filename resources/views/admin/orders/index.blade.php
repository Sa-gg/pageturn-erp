@extends('layouts.admin')

@section('title', 'Manage Orders')
@section('page_title', 'Orders Management')

@section('styles')
<style>
    .admin-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2.5rem 2rem;
    }

    .admin-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .admin-header h1 {
        font-family: var(--font-display);
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--color-brown-dark);
    }

    .admin-card {
        background: var(--color-white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid rgba(0,0,0,0.04);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 16px;
        text-align: left;
        border-bottom: 1px solid var(--color-gray-200);
    }

    th {
        background-color: var(--color-gray-100);
        color: var(--color-gray-600);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        color: var(--color-gray-800);
        font-size: 0.95rem;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-confirmed { background: #cce5ff; color: #004085; }
    .badge-processing { background: #e2e3e5; color: #383d41; }
    .badge-shipped { background: #d1ecf1; color: #0c5460; }
    .badge-delivered { background: #d4edda; color: #155724; }
    .badge-cancelled { background: #f8d7da; color: #721c24; }

    .status-select {
        padding: 4px 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background: white;
        color: #333;
    }
    @media (prefers-color-scheme: dark) {
        .status-select { background: #1e293b; color: #e2e8f0; border-color: rgba(255,255,255,0.15); }
    }
    :root.dark .status-select {
        background: #1e293b;
        color: #e2e8f0;
        border-color: rgba(255,255,255,0.15);
    }
</style>
@endsection

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <div>
            <h1><i class="fas fa-shopping-bag" style="color: var(--color-gold); margin-right: 10px;"></i>Manage Orders</h1>
        </div>
        <div>
            <form method="GET" action="/admin/orders">
                <select name="status" class="status-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="admin-card">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders['data'] ?? $orders as $order)
                <tr>
                    <td><strong>{{ $order['order_number'] ?? $order['id'] }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y H:i') }}</td>
                    <td>
                        {{ $order['shipping_name'] ?? 'Unknown' }}<br>
                        <small class="text-muted">{{ $order['shipping_email'] ?? '' }}</small>
                    </td>
                    <td>₱{{ number_format($order['total'], 2) }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($order['status']) }}">
                            {{ $order['status'] }}
                        </span>
                    </td>
                    <td>
                        <form action="/admin/orders/{{ $order['id'] }}/status" method="POST" style="display:flex; gap:10px;">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="status-select">
                                <option value="pending" {{ $order['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order['status'] == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order['status'] == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order['status'] == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order['status'] == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order['status'] == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" style="background:var(--color-info); color:white; border:none; border-radius:4px; padding:4px 10px; cursor:pointer;">Update</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px;">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
