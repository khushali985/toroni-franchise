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


    {{-- ADD FRANCHISE --}}
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


    {{-- FRANCHISE TABLE --}}
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
                            <img src="{{ asset($franchise->image) }}">
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

</div>

@endsection