@extends('layouts.admin')

@section('title', 'Manage Inventory')
@section('page_title', 'Stock Management')

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
        margin-bottom: 2rem;
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

    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .status-good { background: #28a745; }
    .status-low { background: #ffc107; }
    .status-out { background: #dc3545; }

    .btn-adjust {
        background: var(--color-brown);
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <div>
            <h1><i class="fas fa-boxes" style="color: var(--color-brown); margin-right: 10px;"></i>Inventory Management</h1>
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

    @php
        $alertItems = $alerts['data'] ?? $alerts;
        $inventoryItems = $inventory['data'] ?? $inventory;
    @endphp

    @if(!empty($alertItems))
    <div class="admin-card" style="border-left: 4px solid #ffc107;">
        <div style="padding:16px; background:#fff3cd; color:#856404; font-weight:bold;">
            <i class="fas fa-exclamation-triangle"></i> Low Stock Alerts
        </div>
        <table>
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Current Stock</th>
                    <th>Reorder Level</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alertItems as $alert)
                @if(is_array($alert))
                <tr>
                    <td>{{ $alert['book_title'] ?? 'Unknown' }}</td>
                    <td style="color:#dc3545; font-weight:bold;">{{ $alert['quantity_on_hand'] ?? 0 }}</td>
                    <td>{{ $alert['reorder_level'] ?? 0 }}</td>
                    <td>
                        <button class="btn-adjust" onclick="openAdjustModal({{ json_encode($alert) }})">Adjust Stock</button>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="admin-card">
        <table>
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Location</th>
                    <th>Stock Level</th>
                    <th>Reorder Level</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventoryItems as $item)
                @if(is_array($item))
                <tr>
                    <td><strong>{{ $item['book_title'] ?? 'Unknown' }}</strong></td>
                    <td>{{ $item['location'] ?? 'Unassigned' }}</td>
                    <td>
                        @if(($item['quantity_on_hand'] ?? 0) == 0)
                            <span class="status-indicator status-out"></span> <span style="color:#dc3545; font-weight:bold;">Out of Stock</span>
                        @elseif(($item['quantity_on_hand'] ?? 0) <= ($item['reorder_level'] ?? 0))
                            <span class="status-indicator status-low"></span> <span style="color:#ffc107; font-weight:bold;">Low Stock ({{ $item['quantity_on_hand'] ?? 0 }})</span>
                        @else
                            <span class="status-indicator status-good"></span> {{ $item['quantity_on_hand'] ?? 0 }}
                        @endif
                    </td>
                    <td>{{ $item['reorder_level'] ?? 0 }}</td>
                    <td>
                        <button class="btn-adjust" onclick="openAdjustModal({{ json_encode($item) }})">Adjust</button>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:30px;">No inventory items found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Adjust Stock Modal -->
<div id="adjustModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:8px; width:400px; max-width:90%;">
        <h2 style="margin-bottom:20px;">Adjust Stock</h2>
        <p id="adjustBookTitle" style="margin-bottom:20px; font-weight:bold;"></p>
        
        <form method="POST" action="/admin/inventory/adjust">
            @csrf
            <input type="hidden" name="inventory_item_id" id="adjustItemId">
            <input type="hidden" name="book_id" id="adjustBookId">
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Adjustment Type</label>
                <select name="type" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                    <option value="in">Stock In (Add)</option>
                    <option value="out">Stock Out (Deduct)</option>
                </select>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Quantity</label>
                <input type="number" name="quantity" min="1" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px;">Reason</label>
                <input type="text" name="reason" required placeholder="e.g. Manual count, Damaged, Restock" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeAdjustModal()" style="padding:8px 16px; border:1px solid #ccc; background:#fff; border-radius:4px; cursor:pointer;">Cancel</button>
                <button type="submit" style="background:var(--color-forest); color:white; border:none; padding:8px 16px; border-radius:4px; cursor:pointer;">Confirm Adjustment</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAdjustModal(item) {
    document.getElementById('adjustBookTitle').innerText = item.book_title || 'Unknown';
    document.getElementById('adjustItemId').value = item.id || '';
    document.getElementById('adjustBookId').value = item.book_id || '';
    document.getElementById('adjustModal').style.display = 'flex';
}

function closeAdjustModal() {
    document.getElementById('adjustModal').style.display = 'none';
}
</script>
@endsection
