@extends('layouts.admin')

@section('title', 'Menu Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-menu.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')

<div class="main">

    <h2>Menu Management</h2>

    {{-- MOBILE DROPDOWN --}}
    <div class="franchise-dropdown-mobile">

        <select id="franchiseMobileSelect">

            <option value="">All Franchises</option>

            @foreach($franchises as $franchise)
            <option value="{{ $franchise->id }}" {{ request('franchise')==$franchise->id ? 'selected' : '' }}>
                {{ $franchise->location }}
            </option>
            @endforeach

        </select>

    </div>

    <div class="franchise-filter">

        <!-- <a href="{{ route('menu.index') }}" class="franchise-btn {{ request('franchise') ? '' : 'active' }}"> -->
        <a href="{{ route('menu.index', request()->except('franchise')) }}"
            class="franchise-btn {{ request('franchise') ? '' : 'active' }}">
            All Franchises
        </a>

        @foreach($franchises as $f)

        <a href="{{ route('menu.index', array_merge(request()->query(), ['franchise' => $f->id])) }}"
            class="franchise-btn {{ request('franchise') == $f->id ? 'active' : '' }}">

            {{ $f->location }}

        </a>

        @endforeach

    </div>

    <h3>Filter by Category</h3>

    <div class="category-filter">

        <!-- <a href="{{ route('menu.index') }}" class="category-btn {{ request('category') ? '' : 'active' }}"> -->
        <a href="{{ route('menu.index', request()->except('category')) }}"
            class="category-btn {{ request('category') ? '' : 'active' }}">
            All
        </a>

        @foreach($categories as $cat)

        <!-- <a href="{{ route('menu.index', ['category' => $cat]) }}"
        class="category-btn {{ request('category') == $cat ? 'active' : '' }}"> -->
        <a href="{{ route('menu.index', array_merge(request()->query(), ['category' => $cat])) }}"
            class="category-btn {{ request('category') == $cat ? 'active' : '' }}">

            {{ ucfirst($cat) }}

        </a>

        @endforeach

    </div>

    <!-- @if(session('success'))
<p class="success-msg">{{ session('success') }}</p>
@endif -->

    <button type="button" onclick="openAddModal()" class="add-btn">
        + Add New Item
    </button>

    @foreach($items as $category => $categoryItems)

    <!-- <h3 class="category-heading">
    {{ ucfirst($category) }}
</h3> -->

    <div class="category-header">

        <h3>{{ ucfirst($category) }}</h3>

        <div class="category-actions">

            <button onclick="renameCategory('{{ $category }}')">
                Rename
            </button>

            <form action="{{ route('menu.category.delete') }}" method="POST"
                onsubmit="return confirm('Delete this entire category?')">

                @csrf
                <input type="hidden" name="category" value="{{ $category }}">

                <button type="submit" class="delete-btn">
                    Delete
                </button>

            </form>

        </div>

    </div>

    <div class="menu-grid">

        @foreach($categoryItems as $item)

        <div class="menu-card">
            <img src="{{ asset($item->image) }}" width="120" loading="lazy">

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

    @if(session('success'))
    <div id="successPopup" class="success-popup">
        <div class="success-box">
            <span class="close-popup" onclick="closeSuccessPopup()">×</span>
            <p>{{ session('success') }}</p>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const mobileSelect = document.getElementById("franchiseMobileSelect");

    if (mobileSelect) {

        mobileSelect.addEventListener("change", function () {

            let franchise = this.value;

            let url = new URL(window.location.href);

            if (franchise) {
                url.searchParams.set("franchise", franchise);
            } else {
                url.searchParams.delete("franchise");
            }

            window.location.href = url.toString();

        });

    }

    function closeSuccessPopup() {
        document.getElementById("successPopup").style.display = "none";
    }

    window.onload = function () {
        let popup = document.getElementById("successPopup");

        if (popup) {
            setTimeout(() => {
                popup.style.display = "none";
            }, 3000);
        }
    }

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

    function renameCategory(oldCategory) {
        let newCategory = prompt("Enter new category name", oldCategory);

        if (!newCategory) return;

        let form = document.createElement('form');

        form.method = "POST";
        form.action = "{{ route('menu.category.rename') }}";

        let csrf = document.createElement('input');
        csrf.type = "hidden";
        csrf.name = "_token";
        csrf.value = "{{ csrf_token() }}";

        let oldInput = document.createElement('input');
        oldInput.type = "hidden";
        oldInput.name = "old_category";
        oldInput.value = oldCategory;

        let newInput = document.createElement('input');
        newInput.type = "hidden";
        newInput.name = "new_category";
        newInput.value = newCategory;

        form.appendChild(csrf);
        form.appendChild(oldInput);
        form.appendChild(newInput);

        document.body.appendChild(form);

        form.submit();
    }
</script>

@endpush