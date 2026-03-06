@extends('layouts.admin')

@section('content')

<h2>Franchise Management</h2>

@if(session('success'))
<div style="color:green; margin:10px 0;">
    {{ session('success') }}
</div>
@endif

<hr>

{{-- ADD NEW FRANCHISE --}}
<h3>Add New Franchise</h3>

<form action="{{ route('admin.franchise.store') }}" method="POST" enctype="multipart/form-data">

    @csrf

    <input type="text" name="owner_name" placeholder="Owner Name" required>
    <input type="text" name="owner_phone" placeholder="Phone" required>
    <input type="email" name="owner_email" placeholder="Email" required>
    <input type="text" name="location" placeholder="Location" required>
    <input type="file" name="image">

    <button type="submit">Add</button>
</form>

<hr>

{{-- FRANCHISE LIST --}}
<h3>All Franchises</h3>

<table border="1" width="100%" cellpadding="10">
    <tr>
        <th>Owner</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Location</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>

    @foreach($franchises as $franchise)
    <tr>
        <td>{{ $franchise->owner_name }}</td>
        <td>{{ $franchise->owner_phone }}</td>
        <td>{{ $franchise->owner_email }}</td>
        <td>{{ $franchise->location }}</td>
        <td>
            @if($franchise->image)
            <img src="{{ asset($franchise->image) }}" width="60">
            @endif
        </td>
        <td>
            <a href="{{ route('admin.franchise.edit', $franchise->id) }}">
                <button>Edit</button>
            </a>

            <form action="{{ route('admin.franchise.destroy',$franchise->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Delete this franchise?')">
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

@endsection