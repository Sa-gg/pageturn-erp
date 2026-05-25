@extends('layouts.admin')

@section('title', 'Manage Invoices')
@section('page_title', 'Invoices Management')

@section('styles')
<style>
    .admin-page { max-width: 1200px; margin: 0 auto; padding: 2.5rem 2rem; }
    .admin-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
    .admin-header h1 { font-family: var(--font-display); font-size: 1.8rem; font-weight: 700; color: var(--color-brown-dark); }
    .admin-card { background: var(--color-white); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.04); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 16px; text-align: left; border-bottom: 1px solid var(--color-gray-200); }
    th { background-color: var(--color-gray-100); color: var(--color-gray-600); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    td { color: var(--color-gray-800); font-size: 0.95rem; }
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-paid { background: #d4edda; color: #155724; }
    .btn-pay { background: var(--color-forest); color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }
    .btn-primary { background-color: var(--color-forest); color: white; padding: 8px 16px; border-radius: var(--radius-sm); text-decoration: none; font-size: 0.9rem; font-weight: 600; border: none; cursor: pointer; }
</style>
@endsection

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <div>
            <h1><i class="fas fa-file-invoice-dollar" style="color: var(--color-brown); margin-right: 10px;"></i>Manage Invoices</h1>
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
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices['data'] ?? $invoices as $invoice)
                <tr>
                    <td><strong>{{ $invoice['invoice_number'] ?? 'INV-'.str_pad($invoice['id'], 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($invoice['created_at'])->format('M d, Y') }}</td>
                    <td>{{ $invoice['customer_name'] ?? 'N/A' }}</td>
                    <td>₱{{ number_format($invoice['total_amount'] ?? 0, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($invoice['status']) }}">{{ $invoice['status'] }}</span>
                    </td>
                    <td>
                        @if($invoice['status'] == 'pending')
                            <button type="button" class="btn-pay" onclick="openPayModal({{ json_encode($invoice) }})">Mark Paid</button>
                        @else
                            <span style="color:var(--color-gray-500);"><i class="fas fa-check"></i> Paid</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px;">No invoices found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="payModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:8px; width:400px; max-width:90%;">
        <h2 id="modalTitle" style="margin-bottom:20px;">Record Payment</h2>
        <form id="payForm" method="POST" action="">
            @csrf
            @method('PATCH')
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Payment Method</label>
                <select name="payment_method" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cash">Cash</option>
                </select>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px;">Reference Number (Optional)</label>
                <input type="text" name="reference_number" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeModal()" style="padding:8px 16px; border:1px solid #ccc; background:#fff; border-radius:4px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-primary">Confirm Payment</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPayModal(invoice) {
    document.getElementById('payForm').action = '/admin/invoices/' + invoice.id + '/pay';
    document.getElementById('payModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('payModal').style.display = 'none';
}
</script>
@endsection
