@extends('layouts.admin')

@section('title', 'Manage Expenses')
@section('page_title', 'Expenses Management')

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
    .btn-primary { background-color: var(--color-forest); color: white; padding: 8px 16px; border-radius: var(--radius-sm); text-decoration: none; font-size: 0.9rem; font-weight: 600; border: none; cursor: pointer; }
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; background:#e2e3e5; color:#383d41; }
</style>
@endsection

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <div>
            <h1><i class="fas fa-money-bill-wave" style="color: var(--color-brown); margin-right: 10px;"></i>Manage Expenses</h1>
        </div>
        <button class="btn-primary" onclick="openCreateModal()">+ Record Expense</button>
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
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Ref #</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses['data'] ?? $expenses as $expense)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($expense['incurred_at'] ?? $expense['created_at'])->format('M d, Y') }}</td>
                    <td><span class="badge">{{ str_replace('_', ' ', $expense['category']) }}</span></td>
                    <td style="color:var(--color-danger); font-weight:bold;">-₱{{ number_format($expense['amount'] ?? 0, 2) }}</td>
                    <td>{{ $expense['description'] }}</td>
                    <td>{{ $expense['reference_number'] ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:30px;">No expenses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="expenseModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:8px; width:450px; max-width:90%;">
        <h2 style="margin-bottom:20px;">Record Expense</h2>
        <form method="POST" action="/admin/expenses">
            @csrf
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Category</label>
                <select name="category" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                    <option value="shipping">Shipping</option>
                    <option value="marketing">Marketing</option>
                    <option value="supplier_payment">Supplier Payment</option>
                    <option value="utilities">Utilities</option>
                    <option value="salary">Salary</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Amount (₱)</label>
                <input type="number" name="amount" min="0" step="0.01" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>

            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Description</label>
                <input type="text" name="description" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>

            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Reference Number (Optional)</label>
                <input type="text" name="reference_number" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px;">Date Incurred (Optional)</label>
                <input type="date" name="incurred_at" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeModal()" style="padding:8px 16px; border:1px solid #ccc; background:#fff; border-radius:4px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-primary">Record Expense</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('expenseModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('expenseModal').style.display = 'none';
}
</script>
@endsection
