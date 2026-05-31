@extends('layouts.admin')

@section('title', 'Manage Purchase Orders')
@section('page_title', 'Purchase Orders')

@section('styles')
<style>
    .admin-page { max-width: 1200px; margin: 0 auto; padding: 2.5rem 2rem; }
    .admin-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
    .admin-header h1 { font-family: var(--font-display); font-size: 1.8rem; font-weight: 700; color: var(--color-brown-dark); }
    .btn-primary { background-color: var(--color-forest); color: white; padding: 8px 16px; border-radius: var(--radius-sm); text-decoration: none; font-size: 0.9rem; font-weight: 600; border: none; cursor: pointer; }
    .admin-card { background: var(--color-white); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.04); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 16px; text-align: left; border-bottom: 1px solid var(--color-gray-200); }
    th { background-color: var(--color-gray-100); color: var(--color-gray-600); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    td { color: var(--color-gray-800); font-size: 0.95rem; }
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-received { background: #d4edda; color: #155724; }
    .btn-receive { background: var(--color-info); color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }
</style>
@endsection

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <div>
            <h1><i class="fas fa-file-invoice" style="color: var(--color-brown); margin-right: 10px;"></i>Manage Purchase Orders</h1>
        </div>
        <button class="btn-primary" onclick="openCreateModal()">+ Create PO</button>
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
                    <th>PO #</th>
                    <th>Supplier</th>
                    <th>Date Created</th>
                    <th>Expected Date</th>
                    <th>Total Cost</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $poItems = $purchaseOrders['data'] ?? $purchaseOrders;
                @endphp
                @forelse($poItems as $po)
                @if(is_array($po))
                <tr>
                    <td><strong>{{ $po['po_number'] ?? 'PO-'.str_pad(($po['id'] ?? 0), 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $po['supplier']['name'] ?? 'Unknown' }}</td>
                    <td>{{ isset($po['created_at']) ? \Carbon\Carbon::parse($po['created_at'])->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ isset($po['expected_date']) ? \Carbon\Carbon::parse($po['expected_date'])->format('M d, Y') : 'N/A' }}</td>
                    <td>₱{{ number_format($po['total_cost'] ?? 0, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($po['status'] ?? 'pending') }}">{{ $po['status'] ?? 'pending' }}</span>
                    </td>
                    <td>
                        @if(in_array(($po['status'] ?? 'pending'), ['pending', 'submitted']) && isset($po['id']))
                        <form action="/admin/purchase-orders/{{ $po['id'] }}/receive" method="POST" id="receive-form-{{ $po['id'] }}">
                            @csrf
                            @method('PATCH')
                            <button type="button" class="btn-receive" onclick="window.showConfirm('Receive Stock', 'Are you sure you want to mark this PO as received? This will automatically update the inventory.', () => document.getElementById('receive-form-{{ $po['id'] }}').submit())">Receive Stock</button>
                        </form>
                        @else
                        <span style="color:var(--color-gray-500);"><i class="fas fa-check"></i> Completed</span>
                        @endif
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:30px;">No purchase orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="poModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:8px; width:600px; max-width:90%;">
        <h2 id="modalTitle" style="margin-bottom:20px;">Create Purchase Order</h2>
        <form id="poForm" method="POST" action="/admin/purchase-orders">
            @csrf
            
            <div style="display:flex; gap:15px; margin-bottom:15px;">
                <div style="flex:1;">
                    <label style="display:block; margin-bottom:5px;">Supplier</label>
                    <select name="supplier_id" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers['data'] ?? $suppliers as $supplier)
                            <option value="{{ $supplier['id'] }}">{{ $supplier['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1;">
                    <label style="display:block; margin-bottom:5px;">Expected Date</label>
                    <input type="date" name="expected_date" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                </div>
            </div>

            <div style="margin-bottom:20px; border:1px solid #ddd; padding:15px; border-radius:4px;">
                <h4 style="margin-top:0; margin-bottom:10px;">PO Items</h4>
                <div id="poItemsContainer">
                    <div class="po-item" style="display:flex; gap:10px; margin-bottom:10px;">
                        <div style="flex:3;">
                            <select name="items[0][book_id]" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                                <option value="">Select Book</option>
                                @foreach($books['data'] ?? $books as $book)
                                    <option value="{{ $book['id'] }}">{{ $book['title'] }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="items[0][book_title]" value="">
                        </div>
                        <div style="flex:1;">
                            <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                        </div>
                        <div style="flex:1;">
                            <input type="number" name="items[0][unit_cost]" placeholder="Cost" min="0" step="0.01" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeModal()" style="padding:8px 16px; border:1px solid #ccc; background:#fff; border-radius:4px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-primary">Create PO</button>
            </div>
        </form>
    </div>
</div>

<script>
function syncPoBookTitles() {
    const rows = document.querySelectorAll('#poItemsContainer .po-item');
    rows.forEach((row, index) => {
        const select = row.querySelector('select[name="items[' + index + '][book_id]"]') || row.querySelector('select[name$="[book_id]"]');
        const hidden = row.querySelector('input[name="items[' + index + '][book_title]"]') || row.querySelector('input[name$="[book_title]"]');
        if (select && hidden) {
            const selected = select.options[select.selectedIndex];
            hidden.value = selected ? selected.text : '';
        }
    });
}

function openCreateModal() {
    document.getElementById('poModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('poModal').style.display = 'none';
}

document.addEventListener('change', function(event) {
    if (event.target && event.target.name && event.target.name.indexOf('[book_id]') !== -1) {
        syncPoBookTitles();
    }
});

document.getElementById('poForm').addEventListener('submit', function() {
    syncPoBookTitles();
});
</script>
@endsection
