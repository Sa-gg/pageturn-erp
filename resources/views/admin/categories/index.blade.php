@extends('layouts.admin')

@section('title', 'Manage Categories')
@section('page_title', 'Categories Management')

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
            <h1><i class="fas fa-tags" style="color: var(--color-brown); margin-right: 10px;"></i>Manage Categories</h1>
        </div>
        <button class="btn-primary" onclick="openCreateModal()">+ Add New Category</button>
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
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $categoryItems = $categories['data'] ?? $categories;
                @endphp
                @forelse($categoryItems as $category)
                @if(is_array($category) && isset($category['id']))
                <tr>
                    <td>{{ $category['id'] }}</td>
                    <td><strong>{{ $category['name'] ?? 'Unknown' }}</strong></td>
                    <td>{{ \Illuminate\Support\Str::limit($category['description'] ?? '', 100) }}</td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-edit" onclick="openEditModal({{ json_encode($category) }})"><i class="fas fa-edit"></i></button>
                            <form action="/admin/categories/{{ $category['id'] }}" method="POST" id="delete-form-{{ $category['id'] }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="window.showConfirm('Delete Category', 'Are you sure you want to delete this category?', () => document.getElementById('delete-form-{{ $category['id'] }}').submit())"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding:30px;">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="categoryModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:8px; width:400px; max-width:90%;">
        <h2 id="modalTitle" style="margin-bottom:20px;">Add Category</h2>
        <form id="categoryForm" method="POST" action="/admin/categories">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Name</label>
                <input type="text" name="name" id="categoryName" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px;">Description</label>
                <textarea name="description" id="categoryDescription" rows="4" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"></textarea>
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeModal()" style="padding:8px 16px; border:1px solid #ccc; background:#fff; border-radius:4px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').innerText = 'Add New Category';
    document.getElementById('categoryForm').action = '/admin/categories';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('categoryName').value = '';
    document.getElementById('categoryDescription').value = '';
    document.getElementById('categoryModal').style.display = 'flex';
}

function openEditModal(category) {
    document.getElementById('modalTitle').innerText = 'Edit Category';
    document.getElementById('categoryForm').action = '/admin/categories/' + category.id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('categoryName').value = category.name;
    document.getElementById('categoryDescription').value = category.description || '';
    document.getElementById('categoryModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('categoryModal').style.display = 'none';
}
</script>
@endsection
