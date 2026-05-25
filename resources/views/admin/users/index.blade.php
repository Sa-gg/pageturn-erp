@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page_title', 'Users Management')

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
    .badge-admin { background: #cce5ff; color: #004085; }
    .badge-staff { background: #e2e3e5; color: #383d41; }
    .badge-customer { background: #d4edda; color: #155724; }
    .action-btns { display: flex; gap: 10px; }
    .btn-edit { color: var(--color-info); background: none; border: none; cursor: pointer; }
    .btn-delete { color: var(--color-danger); background: none; border: none; cursor: pointer; }
    .status-active { color: #28a745; font-weight: bold; }
    .status-inactive { color: #dc3545; font-weight: bold; }
</style>
@endsection

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <div>
            <h1><i class="fas fa-users-cog" style="color: var(--color-brown); margin-right: 10px;"></i>Manage Users</h1>
        </div>
        <button class="btn-primary" onclick="openCreateModal()">+ Add New User</button>
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
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Registered On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users['data'] ?? $users as $user)
                <tr>
                    <td><strong>{{ $user['name'] }}</strong></td>
                    <td>{{ $user['email'] }}</td>
                    <td><span class="badge badge-{{ $user['role'] }}">{{ $user['role'] }}</span></td>
                    <td>
                        @if($user['is_active'])
                            <span class="status-active"><i class="fas fa-check-circle"></i> Active</span>
                        @else
                            <span class="status-inactive"><i class="fas fa-times-circle"></i> Inactive</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($user['created_at'])->format('M d, Y') }}</td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-edit" onclick="openEditModal({{ json_encode($user) }})"><i class="fas fa-edit"></i></button>
                            <form action="/admin/users/{{ $user['id'] }}" method="POST" id="delete-form-{{ $user['id'] }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="window.showConfirm('Delete User', 'Are you sure you want to delete this user? This action cannot be undone.', () => document.getElementById('delete-form-{{ $user['id'] }}').submit())"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px;">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="userModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:8px; width:450px; max-width:90%;">
        <h2 id="modalTitle" style="margin-bottom:20px;">Add User</h2>
        <form id="userForm" method="POST" action="/admin/users">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Name</label>
                <input type="text" name="name" id="userName" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Email</label>
                <input type="email" name="email" id="userEmail" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>

            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Password</label>
                <input type="password" name="password" id="userPassword" placeholder="Leave blank to keep current" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                <small id="passwordHelp" style="color:#666; display:none;">Leave blank to keep the current password.</small>
            </div>
            
            <div style="display:flex; gap:15px; margin-bottom:20px;">
                <div style="flex:1;">
                    <label style="display:block; margin-bottom:5px;">Role</label>
                    <select name="role" id="userRole" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                        <option value="customer">Customer</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div style="flex:1;" id="activeToggleContainer" style="display:none;">
                    <label style="display:block; margin-bottom:5px;">Status</label>
                    <select name="is_active" id="userActive" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeModal()" style="padding:8px 16px; border:1px solid #ccc; background:#fff; border-radius:4px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-primary">Save User</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').innerText = 'Add New User';
    document.getElementById('userForm').action = '/admin/users';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').required = true;
    document.getElementById('passwordHelp').style.display = 'none';
    document.getElementById('userRole').value = 'customer';
    document.getElementById('activeToggleContainer').style.display = 'none';
    
    // Add default is_active for create
    let activeInput = document.createElement("input");
    activeInput.setAttribute("type", "hidden");
    activeInput.setAttribute("name", "is_active");
    activeInput.setAttribute("value", "1");
    activeInput.setAttribute("id", "hiddenActive");
    document.getElementById('userForm').appendChild(activeInput);

    document.getElementById('userModal').style.display = 'flex';
}

function openEditModal(user) {
    document.getElementById('modalTitle').innerText = 'Edit User';
    document.getElementById('userForm').action = '/admin/users/' + user.id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('userName').value = user.name;
    document.getElementById('userEmail').value = user.email;
    document.getElementById('userPassword').required = false;
    document.getElementById('passwordHelp').style.display = 'block';
    document.getElementById('userRole').value = user.role;
    
    let hiddenActive = document.getElementById("hiddenActive");
    if (hiddenActive) hiddenActive.remove();

    document.getElementById('activeToggleContainer').style.display = 'block';
    document.getElementById('userActive').value = user.is_active ? '1' : '0';
    
    document.getElementById('userModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('userModal').style.display = 'none';
}
</script>
@endsection
