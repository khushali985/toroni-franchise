@extends('layouts.admin')

@section('content')

<h2>Edit Franchise</h2>

@if ($errors->any())
<div style="color:red; margin-bottom:15px;">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.franchise.update',$franchise->id) }}" method="POST" enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <table cellpadding="10">

        <tr>
            <td><strong>Owner Name:</strong></td>
            <td>
                <input type="text" name="owner_name" value="{{ $franchise->owner_name }}" required>
            </td>
        </tr>

        <tr>
            <td><strong>Owner Phone:</strong></td>
            <td>
                <input type="text" name="owner_phone" value="{{ $franchise->owner_phone }}" required>
            </td>
        </tr>

        <tr>
            <td><strong>Owner Email:</strong></td>
            <td>
                <input type="email" name="owner_email" value="{{ $franchise->owner_email }}" required>
            </td>
        </tr>

        <tr>
            <td><strong>Location:</strong></td>
            <td>
                <input type="text" name="location" value="{{ $franchise->location }}" required>
            </td>
        </tr>

        <tr>
            <td><strong>Current Image:</strong></td>
            <td>
                @if($franchise->image)
                <img src="{{ asset($franchise->image) }}" width="120">
                @else
                No Image Uploaded
                @endif
            </td>
        </tr>

        <tr>
            <td><strong>Change Image:</strong></td>
            <td>
                <input type="file" name="image">
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <button type="submit">Update Franchise</button>
            </td>
        </tr>

    </table>

</form>

@endsection