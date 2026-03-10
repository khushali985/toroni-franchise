@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-franchise.css') }}">
@endpush

@section('content')

<div class="franchise-dashboard">

    <h2 class="dashboard-title">Edit Franchise</h2>

    @if ($errors->any())
    <div class="error-box">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <div class="card">

        <form action="{{ route('admin.franchise.update',$franchise->id) }}" method="POST" enctype="multipart/form-data"
            class="form-grid">

            @csrf
            @method('PUT')

            <input type="text" name="owner_name" value="{{ $franchise->owner_name }}" placeholder="Owner Name">

            <input type="text" name="owner_phone" value="{{ $franchise->owner_phone }}" placeholder="Phone">

            <input type="email" name="owner_email" value="{{ $franchise->owner_email }}" placeholder="Email">

            <input type="text" name="location" value="{{ $franchise->location }}" placeholder="Location">

            <div>
                <strong>Current Image</strong><br>

                @if($franchise->image)
                <img src="{{ asset($franchise->image) }}" width="120">
                @else
                No Image Uploaded
                @endif
            </div>

            <input type="file" name="image">

            <button type="submit" class="btn-primary-custom">
                Update Franchise
            </button>

        </form>

    </div>

</div>

@endsection