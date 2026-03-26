@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-franchise.css') }}">
@endpush

@section('content')

<div class="franchise-dashboard">

    <h2 class="dashboard-title">Franchise Management</h2>

    @if(session('success'))
    <div class="success-box">
        {{ session('success') }}
    </div>
    @endif


    {{-- =========================================
    ADD FRANCHISE
    ========================================= --}}
    <div class="card add-franchise-card">

        <h3>Add New Franchise</h3>

        <form action="{{ route('admin.franchise.store') }}" method="POST" enctype="multipart/form-data"
            class="form-grid">

            @csrf

            <input type="text" name="owner_name" placeholder="Owner Name" required>

            <input type="text" name="owner_phone" placeholder="Phone" required>

            <input type="email" name="owner_email" placeholder="Email" required>

            <input type="text" name="location" placeholder="Location" required>

            <input type="file" name="image">

            <button type="submit" class="btn-primary-custom">
                Add Franchise
            </button>

        </form>

    </div>


    {{-- =========================================
    FRANCHISE TABLE
    ========================================= --}}
    <div class="card">

        <h3>All Franchises</h3>

        <div class="table-wrapper">

            <table class="franchise-table">

                <thead>
                    <tr>
                        <th>Owner</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($franchises as $franchise)

                    <tr>

                        <td>{{ $franchise->owner_name }}</td>

                        <td>{{ $franchise->owner_phone }}</td>

                        <td>{{ $franchise->owner_email }}</td>

                        <td>{{ $franchise->location }}</td>

                        <td>
                            @if($franchise->image)
                            <img src="{{ asset($franchise->image) }}" loading="lazy">
                            @endif
                        </td>

                        <td>

                            <a href="{{ route('admin.franchise.edit',$franchise->id) }}">
                                <button class="btn-edit">Edit</button>
                            </a>

                            <form action="{{ route('admin.franchise.destroy',$franchise->id) }}" method="POST"
                                style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button class="btn-delete" onclick="return confirm('Delete this franchise?')">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================
    FRANCHISE PARTNER QUERIES
    ========================================= --}}
    <div class="card">

        <h3>Franchise Partner Queries</h3>


        {{-- BULK DELETE FORM --}}
        <form method="POST" action="{{ route('admin.franchise.bulk') }}">
            @csrf

            <div style="margin-bottom:10px; display:flex; gap:10px;">

                <input type="hidden" name="action" value="delete">

                <button type="submit" class="btn-delete" onclick="return confirm('Delete selected queries?')">

                    Delete Selected

                </button>

            </div>


            <div class="table-wrapper">

                <table class="franchise-table">

                    <thead>

                        <tr>

                            <th>
                                <input type="checkbox" id="selectAll">
                            </th>

                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Date</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($franchisePartners as $partner)

                        <tr class="clickable-row">

                            <td>
                                <input type="checkbox" class="row-checkbox" name="selected_partners[]"
                                    value="{{ $partner->id }}">
                            </td>

                            <td>{{ $partner->name }}</td>

                            <td>{{ $partner->email }}</td>

                            <td>{{ $partner->phone }}</td>

                            <td>{{ $partner->city }}</td>

                            <td>{{ $partner->created_at->format('d M Y') }}</td>

                            <td>
                                <button type="button" class="btn-delete" onclick="deletePartner('{{ $partner->id }}')">

                                    Delete

                                </button>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="7">No Franchise Queries Found</td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </form>

        <form id="deletePartnerForm" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

    </div>

</div>

{{-- =========================================
JS FOR ROW CLICK SELECT
========================================= --}}
<script>

    document.querySelectorAll(".clickable-row").forEach(row => {

        row.addEventListener("click", function (e) {

            if (e.target.type !== "checkbox") {

                let checkbox = this.querySelector(".row-checkbox");

                checkbox.checked = !checkbox.checked;

                this.classList.toggle("selected-row", checkbox.checked);

            }

        });

    });

    function deletePartner(id) {

        if (!confirm('Delete this query?')) return;

        let form = document.getElementById('deletePartnerForm');

        form.action = "/admin/franchise/partner/" + id;

        form.submit();
    }

    document.getElementById("selectAll").addEventListener("change", function () {

        let checkboxes = document.querySelectorAll(".row-checkbox");

        checkboxes.forEach(cb => {

            cb.checked = this.checked;

            cb.closest("tr").classList.toggle("selected-row", this.checked);

        });

    });

    document.querySelectorAll(".row-checkbox").forEach(cb => {
        cb.addEventListener("change", function () {
            this.closest("tr").classList.toggle("selected-row", this.checked);
        });
    });

</script>

@endsection