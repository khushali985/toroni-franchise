@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-team.css') }}">
@endpush

@section('title', 'Team Management')



@section('content')

<section class="admin-dashboard">

    <h1 class="dashboard-title">Team Management</h1>

    {{-- ================= ADD TEAM MEMBER ================= --}}
    <div class="card add-table-card">

        <h3>Add Team Member</h3>

        <form method="POST" action="{{ route('team.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">

                <select name="franchise_id" required>
                    <option value="">Select Franchise</option>

                    @foreach($franchises as $franchise)
                    <option value="{{ $franchise->id }}">
                        {{ $franchise->location ?? $franchise->name }}
                    </option>
                    @endforeach

                </select>

                <input type="text" name="name" placeholder="Member Name" required>

                <select name="role" required>

                    <option value="">Select Role</option>

                    <option value="Manager">Manager</option>
                    <option value="Chef">Chef</option>
                    <option value="Waiter">Waiter</option>
                    <option value="Receptionist">Receptionist</option>
                    <option value="Staff">Staff</option>

                </select>

                <input type="file" name="image" accept="image/*">

                <textarea name="description" placeholder="Description"></textarea>

                <button type="submit" class="btn-success">
                    Add Member
                </button>

            </div>

        </form>

    </div>


    {{-- ================= FRANCHISE FILTER ================= --}}
    <div class="franchise-selection">

        <h2>Select Franchise</h2>

        <div class="franchise-card-container">

            <a href="{{ route('team.index') }}" class="franchise-card {{ !$franchise_id ? 'active' : '' }}">
                All Franchises
            </a>

            @foreach($franchises as $franchise)

            <a href="{{ route('team.index',['franchise_id'=>$franchise->id]) }}"
                class="franchise-card {{ $franchise_id == $franchise->id ? 'active' : '' }}">

                <h3>{{ $franchise->location ?? $franchise->name }}</h3>

            </a>

            @endforeach

        </div>

    </div>


    {{-- ================= TEAM MEMBERS TABLE ================= --}}
    <div class="reservation-section card-container">

        <div class="reservation-header">
            <h2>Manage Team</h2>
        </div>

        <div class="table-wrapper">

            <table class="reservation-table">

                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Franchise</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($team as $member)

                    <tr>

                        <td>
                            @if($member->image)
                            <img src="{{ asset('images/team/'.$member->image) }}" width="60">
                            @endif
                        </td>

                        <td>
                            {{ $member->franchise->location ?? $member->franchise->name ?? '' }}
                        </td>

                        <td>{{ $member->name }}</td>

                        <td>{{ $member->role }}</td>

                        <td>{{ $member->description }}</td>

                        <td class="action-buttons">

                            <button class="btn-dark" data-id="{{ $member->id }}" data-name="{{ $member->name }}"
                                data-role="{{ $member->role }}" data-description="{{ e($member->description) }}"
                                data-franchise="{{ $member->franchise_id }}" onclick="editMember(this)">
                                Edit
                            </button>


                            <form method="POST" action="{{ route('team.delete',$member->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-danger">
                                    Delete
                                </button>
                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6">No Team Members Found</td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ================= EDIT MODAL ================= --}}
    <div id="teamModal" class="modal">

        <div class="modal-content">

            <span class="close" onclick="closeModal()">&times;</span>

            <h3>Edit Team Member</h3>

            <form method="POST" id="editForm" enctype="multipart/form-data">
                @csrf

                <select name="franchise_id" id="franchise_id">

                    @foreach($franchises as $franchise)
                    <option value="{{ $franchise->id }}">
                        {{ $franchise->location ?? $franchise->name }}
                    </option>
                    @endforeach

                </select>

                <input type="text" name="name" id="name" required>

                <select name="role" id="role">

                    <option value="Manager">Manager</option>
                    <option value="Chef">Chef</option>
                    <option value="Waiter">Waiter</option>
                    <option value="Receptionist">Receptionist</option>
                    <option value="Staff">Staff</option>

                </select>

                <textarea name="description" id="description"></textarea>

                <input type="file" name="image">

                <button type="submit" class="btn-success">
                    Update
                </button>

            </form>

        </div>

    </div>

</section>

@endsection


@push('scripts')

<script>

    function editMember(btn) {

        let id = btn.dataset.id

        document.getElementById('teamModal').style.display = 'block'

        document.getElementById('editForm').action = "/admin/team/update/" + id

        document.getElementById('name').value = btn.dataset.name
        document.getElementById('role').value = btn.dataset.role
        document.getElementById('description').value = btn.dataset.description
        document.getElementById('franchise_id').value = btn.dataset.franchise

    }

    function closeModal() {
        document.getElementById('teamModal').style.display = 'none'
    }

</script>

@endpush