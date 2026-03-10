@extends('layouts.admin')

@section('title', 'Menu Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-menu.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')

<h2>Menu Management</h2>

<h3>Select Franchise</h3>

<div class="franchise-filter">

    <a href="{{ route('menu.index') }}" class="franchise-btn {{ request('franchise') ? '' : 'active' }}">
        All Franchises
    </a>

    @foreach($franchises as $f)

    <a href="{{ route('menu.index', ['franchise' => $f->id]) }}"
        class="franchise-btn {{ request('franchise') == $f->id ? 'active' : '' }}">

        {{ $f->location }}

    </a>

    @endforeach

</div>

@if(session('success'))
<p class="success-msg">{{ session('success') }}</p>
@endif

<button type="button" onclick="openAddModal()" class="add-btn">
    + Add New Item
</button>

@foreach($items as $category => $categoryItems)

<h3 class="category-heading">
    {{ ucfirst($category) }}
</h3>

<div class="menu-grid">

    @foreach($categoryItems as $item)

    <div class="menu-card">
        <img src="{{ asset('storage/'.$item->image) }}" width="120">

        <h4>{{ $item->dish_name }}</h4>
        <p>₹{{ $item->price }}</p>
        <small>{{ $item->ingredients }}</small>

        <div class="card-actions">

            <button type="button" class="edit-btn" data-id="{{ $item->id }}" data-name="{{ $item->dish_name }}"
                data-ingredients="{{ $item->ingredients }}" data-category="{{ $item->category }}"
                data-price="{{ $item->price }}" data-franchise="{{ $item->franchise_id }}">
                Edit
            </button>

            <form action="{{ route('menu.destroy', $item) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this item?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn">
                    Delete
                </button>
            </form>

        </div>
    </div>

    @endforeach

</div>

@endforeach

{{-- ADD MODAL --}}
<div id="addModal" class="modal">
    <div class="modal-content">
        <h3>Add Menu Item</h3>

        <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <select name="franchise_id" required>
                <option value="">Select Franchise</option>
                @foreach($franchises as $f)
                <option value="{{ $f->id }}">
                    {{ $f->location }}
                </option>
                @endforeach
            </select>

            <input type="text" name="dish_name" placeholder="Dish Name" required>
            <textarea name="ingredients" placeholder="Ingredients" required></textarea>
            <select name="category" id="addCategorySelect" class="category-select" required>
                <option value="">Select or Type Category</option>

                @foreach($categories as $category)
                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                @endforeach
            </select>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="file" name="image" required>

            <button type="submit">Save</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

{{-- EDIT MODAL --}}
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3>Edit Menu Item</h3>

        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <select name="franchise_id" id="edit_franchise" required>
                @foreach($franchises as $f)
                <option value="{{ $f->id }}">
                    {{ $f->location }}
                </option>
                @endforeach
            </select>

            <input type="text" name="dish_name" id="edit_name" required>
            <textarea name="ingredients" id="edit_ingredients" required></textarea>
            <select name="category" id="editCategorySelect" class="category-select" required>
                <option value="">Select or Type Category</option>

                @foreach($categories as $category)
                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                @endforeach
            </select>
            <input type="number" step="0.01" name="price" id="edit_price" required>
            <input type="file" name="image">

            <button type="submit">Update</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        $('#addCategorySelect').select2({
            tags: true,
            placeholder: "Select or type category",
            width: '100%',
            dropdownParent: $('#addModal')
        }).on('select2:open', function () {
            $('.select2-search__field').attr('placeholder', 'Add new category...');
        });

        $('#editCategorySelect').select2({
            tags: true,
            placeholder: "Select or type category",
            width: '100%',
            dropdownParent: $('#editModal')
        }).on('select2:open', function () {
            $('.select2-search__field').attr('placeholder', 'Add new category...');
        });

        // Open Add Modal
        window.openAddModal = function () {
            document.getElementById('addModal').style.display = 'flex';
        };

        // Close Modals
        window.closeModal = function () {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('editModal').style.display = 'none';
        };

        // EDIT BUTTON LOGIC
        document.querySelectorAll('.edit-btn').forEach(button => {

            button.addEventListener('click', function () {

                document.getElementById('editModal').style.display = 'flex';

                document.getElementById('edit_name').value = this.dataset.name;
                document.getElementById('edit_ingredients').value = this.dataset.ingredients;
                document.getElementById('edit_price').value = this.dataset.price;
                document.getElementById('edit_franchise').value = this.dataset.franchise;

                let categoryValue = this.dataset.category;

                // 🔥 Set value in Select2 properly
                let editSelect = $('#editCategorySelect');

                // If category not already present, add it dynamically
                if (editSelect.find("option[value='" + categoryValue + "']").length === 0) {
                    let newOption = new Option(categoryValue, categoryValue, true, true);
                    editSelect.append(newOption).trigger('change');
                } else {
                    editSelect.val(categoryValue).trigger('change');
                }

                // Set correct form action
                document.getElementById('editForm').action =
                    "{{ url('admin/menu') }}/" + this.dataset.id;

            });

        });

    });
</script>

@endpush