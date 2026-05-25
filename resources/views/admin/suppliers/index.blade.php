@extends('layouts.admin')

@section('title', 'Manage Suppliers')
@section('page_title', 'Suppliers Management')

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
    .action-btns { display: flex; gap: 10px; }
    .btn-edit { color: var(--color-info); background: none; border: none; cursor: pointer; }
    .btn-delete { color: var(--color-danger); background: none; border: none; cursor: pointer; }
</style>
@endsection

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <div>
            <h1><i class="fas fa-truck" style="color: var(--color-brown); margin-right: 10px;"></i>Manage Suppliers</h1>
        </div>
        <button class="btn-primary" onclick="openCreateModal()">+ Add New Supplier</button>
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
                    <th>Name</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers['data'] ?? $suppliers as $supplier)
                <tr>
                    <td><strong>{{ $supplier['name'] }}</strong></td>
                    <td>{{ $supplier['contact_person'] ?? '-' }}</td>
                    <td>{{ $supplier['email'] }}</td>
                    <td>{{ $supplier['phone'] ?? '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-edit" onclick="openEditModal({{ json_encode($supplier) }})"><i class="fas fa-edit"></i></button>
                            <form action="/admin/suppliers/{{ $supplier['id'] }}" method="POST" id="delete-form-{{ $supplier['id'] }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="window.showConfirm('Delete Supplier', 'Are you sure you want to delete this supplier?', () => document.getElementById('delete-form-{{ $supplier['id'] }}').submit())"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:30px;">No suppliers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="supplierModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:8px; width:450px; max-width:90%;">
        <h2 id="modalTitle" style="margin-bottom:20px;">Add Supplier</h2>
        <form id="supplierForm" method="POST" action="/admin/suppliers">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Name</label>
                <input type="text" name="name" id="supplierName" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Contact Person</label>
                <input type="text" name="contact_person" id="supplierContactPerson" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>

            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Email</label>
                <input type="email" name="email" id="supplierEmail" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>

            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Phone</label>
                <input type="text" name="phone" id="supplierPhone" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px;">Address</label>
                <textarea name="address" id="supplierAddress" rows="3" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"></textarea>
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeModal()" style="padding:8px 16px; border:1px solid #ccc; background:#fff; border-radius:4px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-primary">Save Supplier</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').innerText = 'Add New Supplier';
    document.getElementById('supplierForm').action = '/admin/suppliers';
    document.getElementById('formMethod').value = 'POST';
    
    document.getElementById('supplierName').value = '';
    document.getElementById('supplierContactPerson').value = '';
    document.getElementById('supplierEmail').value = '';
    document.getElementById('supplierPhone').value = '';
    document.getElementById('supplierAddress').value = '';
    
    document.getElementById('supplierModal').style.display = 'flex';
}

function openEditModal(supplier) {
    document.getElementById('modalTitle').innerText = 'Edit Supplier';
    document.getElementById('supplierForm').action = '/admin/suppliers/' + supplier.id;
    document.getElementById('formMethod').value = 'PUT';
    
    document.getElementById('supplierName').value = supplier.name;
    document.getElementById('supplierContactPerson').value = supplier.contact_person || '';
    document.getElementById('supplierEmail').value = supplier.email;
    document.getElementById('supplierPhone').value = supplier.phone || '';
    document.getElementById('supplierAddress').value = supplier.address || '';
    
    document.getElementById('supplierModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('supplierModal').style.display = 'none';
}
</script>
@endsection
