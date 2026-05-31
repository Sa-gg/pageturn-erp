@extends('layouts.admin')

@section('title', 'Manage Books')
@section('page_title', 'Books Management')

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

    .btn-primary {
        background-color: var(--color-forest);
        color: white;
        padding: 8px 16px;
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
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

    .action-btns {
        display: flex;
        gap: 10px;
    }

    .btn-edit {
        color: var(--color-info);
        background: none;
        border: none;
        cursor: pointer;
    }

    .btn-delete {
        color: var(--color-danger);
        background: none;
        border: none;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <div>
            <h1><i class="fas fa-book" style="color: var(--color-brown); margin-right: 10px;"></i>Manage Books</h1>
        </div>
        <button class="btn-primary" onclick="openCreateModal()">+ Add New Book</button>
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
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $bookItems = $books['data'] ?? $books;
                    $authorItems = $authors['data'] ?? $authors;
                    $categoryItems = $categories['categories'] ?? $categories['data'] ?? $categories;
                @endphp
                @forelse($bookItems as $book)
                @php
                    $bookId = data_get($book, 'id');
                    $bookAuthorId = data_get($book, 'author_id');
                    $bookCategoryId = data_get($book, 'category_id');
                    $bookTitle = data_get($book, 'title', 'Untitled');
                    $bookIsbn = data_get($book, 'isbn', 'N/A');
                    $bookPrice = data_get($book, 'price', 0);
                @endphp
                <tr>
                    <td>
                        <img src="{{ data_get($book, 'cover_image_url', 'https://via.placeholder.com/50x70?text=No+Cover') }}" alt="Cover" width="50" style="border-radius:4px;">
                    </td>
                    <td><strong>{{ $bookTitle }}</strong><br><small class="text-muted">ISBN: {{ $bookIsbn }}</small></td>
                    <td>{{ data_get($book, 'author.name', 'Unknown') }}</td>
                    <td>{{ data_get($book, 'category.name', 'Unknown') }}</td>
                    <td>₱{{ number_format((float) $bookPrice, 2) }}</td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-edit" onclick='openEditModal(@json($book))'><i class="fas fa-edit"></i></button>
                            @if($bookId)
                            <form action="/admin/books/{{ $bookId }}" method="POST" id="delete-form-{{ $bookId }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="window.showConfirm('Delete Book', 'Are you sure you want to delete this book?', () => document.getElementById('delete-form-{{ $bookId }}').submit())"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px;">No books found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Simple Modals (Hidden by default) -->
<div id="bookModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:8px; width:500px; max-width:90%;">
        <h2 id="modalTitle" style="margin-bottom:20px;">Add Book</h2>
        <form id="bookForm" method="POST" action="/admin/books">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Title</label>
                <input type="text" name="title" id="bookTitle" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">ISBN</label>
                <input type="text" name="isbn" id="bookIsbn" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="display:flex; gap:15px; margin-bottom:15px;">
                <div style="flex:1;">
                    <label style="display:block; margin-bottom:5px;">Author</label>
                    <select name="author_id" id="bookAuthor" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                        @foreach($authorItems as $author)
                            <option value="{{ data_get($author, 'id') }}">{{ data_get($author, 'name', 'Unknown') }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1;">
                    <label style="display:block; margin-bottom:5px;">Category</label>
                    <select name="category_id" id="bookCategory" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                        @foreach($categoryItems as $category)
                            <option value="{{ data_get($category, 'id') }}">{{ data_get($category, 'name', 'Unknown') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Price (₱)</label>
                <input type="number" name="price" id="bookPrice" step="0.01" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Cover Image URL</label>
                <input type="url" name="cover_image_url" id="bookCover" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px;">Description</label>
                <textarea name="description" id="bookDescription" rows="3" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"></textarea>
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeModal()" style="padding:8px 16px; border:1px solid #ccc; background:#fff; border-radius:4px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-primary">Save Book</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').innerText = 'Add New Book';
    document.getElementById('bookForm').action = '/admin/books';
    document.getElementById('formMethod').value = 'POST';
    
    // Clear form
    document.getElementById('bookTitle').value = '';
    document.getElementById('bookIsbn').value = '';
    document.getElementById('bookPrice').value = '';
    document.getElementById('bookCover').value = '';
    document.getElementById('bookDescription').value = '';
    
    document.getElementById('bookModal').style.display = 'flex';
}

function openEditModal(book) {
    document.getElementById('modalTitle').innerText = 'Edit Book';
    document.getElementById('bookForm').action = '/admin/books/' + book.id;
    document.getElementById('formMethod').value = 'PUT';
    
    // Populate form
    document.getElementById('bookTitle').value = book.title;
    document.getElementById('bookIsbn').value = book.isbn;
    document.getElementById('bookAuthor').value = book.author_id;
    document.getElementById('bookCategory').value = book.category_id;
    document.getElementById('bookPrice').value = book.price;
    document.getElementById('bookCover').value = book.cover_image_url || '';
    document.getElementById('bookDescription').value = book.description || '';
    
    document.getElementById('bookModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('bookModal').style.display = 'none';
}
</script>
@endsection
