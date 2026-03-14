@extends('layouts.admin')

@section('title','Team Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-team.css') }}">
@endpush


@section('content')

<section class="admin-dashboard">

    <h2 class="dashboard-title">Team Management</h2>


    {{-- ADD MEMBER BUTTON --}}
    <div style="margin-bottom:20px;">
        <button class="btn-success" onclick="toggleAddForm()">
            + Add Team Member
        </button>
    </div>


    {{-- ADD MEMBER FORM --}}
    <div class="card add-table-card" id="addMemberCard" style="display:none;">

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

                <input type="file" name="image">

                <textarea name="description" placeholder="Description"></textarea>

                <button type="submit" class="btn-success">
                    Add Member
                </button>

                <button type="button" class="btn-dark" onclick="closeAddForm()">
                    Cancel
                </button>


            </div>

        </form>

    </div>

    {{-- MOBILE DROPDOWN --}}
    <div class="franchise-dropdown-mobile">

        <select id="franchiseMobileSelect">

            <option value="">Select Franchises</option>

            @foreach($franchises as $franchise)
            <option value="{{ $franchise->id }}" {{ request('franchise_id')==$franchise->id ? 'selected' : '' }}>
                {{ $franchise->location }}
            </option>
            @endforeach

        </select>

    </div>

    {{-- FRANCHISE FILTER --}}

    <div class="franchise-filter">
        <button class="franchise-btn {{ request('franchise_id') ? '' : 'active' }}" data-id="">
            All Franchises
        </button>

        @foreach($franchises as $franchise)
        <button class="franchise-btn {{ request('franchise_id') == $franchise->id ? 'active' : '' }}"
            data-id="{{ $franchise->id }}">
            {{ $franchise->location }}
        </button>
        @endforeach
    </div>

    {{-- TEAM TABLE --}}
    <div class="reservation-section card-container">

        <div class="reservation-header">

            <h3>Manage Team</h3>

            <form method="POST" action="{{ route('admin.team.bulk') }}" id="bulkForm" class="bulk-actions">

                @csrf

                <input type="hidden" name="team_ids" id="selectedIds">

                <select name="action" required>

                    <option value="">Actions</option>
                    <option value="delete">Delete Selected</option>

                </select>

                <button type="submit" class="btn-dark">
                    Apply
                </button>

            </form>

        </div>



        <div class="table-wrapper">

            <table class="reservation-table">

                <thead>

                    <tr>

                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>

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

                    <tr class="team-row" onclick="toggleRowSelection(this)">

                        <td>
                            <input type="checkbox" class="rowCheckbox" value="{{ $member->id }}"
                                onclick="event.stopPropagation()">
                        </td>

                        <td>
                            @if($member->image)
                            <img src="{{ asset('images/team/'.$member->image) }}" width="60" loading="lazy">
                            @endif
                        </td>

                        <td>
                            {{ $member->franchise->location ?? '' }}
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

                            <form method="POST" action="{{ route('team.delete',$member->id) }}">

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
                        <td colspan="7">No Team Members Found</td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- EDIT MODAL --}}
    <div id="teamModal" class="modal">

        <div class="modal-content">

            <span class="close" onclick="closeModal()">
                &times;
            </span>

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

                <input type="text" name="name" id="name">

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
    const mobileSelect = document.getElementById("franchiseMobileSelect");

    if (mobileSelect) {

        mobileSelect.addEventListener("change", function () {

            let franchise = this.value;

            let url = new URL(window.location.href);

            if (franchise) {
                url.searchParams.set("franchise_id", franchise);
            } else {
                url.searchParams.delete("franchise_id");
            }

            window.location.href = url.toString();

        });

    }
    let selectedFranchise = '';

    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('franchise-btn')) {

            document.querySelectorAll('.franchise-btn')
                .forEach(btn => btn.classList.remove('active'));

            e.target.classList.add('active');

            selectedFranchise = e.target.dataset.id;

            let url = new URL(window.location.href);

            if (selectedFranchise) {
                url.searchParams.set('franchise_id', selectedFranchise);
            } else {
                url.searchParams.delete('franchise_id');
            }

            window.location.href = url.toString();
        }
    });

    function toggleAddForm() {

        const form = document.getElementById('addMemberCard')

        if (form.style.display === 'none') {
            form.style.display = 'block'
        } else {
            form.style.display = 'none'
        }

    }


    function editMember(btn) {

        let id = btn.dataset.id

        document.getElementById('teamModal').style.display = 'block'

        document.getElementById('editForm').action =
            "/admin/team/update/" + id

        document.getElementById('name').value =
            btn.dataset.name

        document.getElementById('role').value =
            btn.dataset.role

        document.getElementById('description').value =
            btn.dataset.description

        document.getElementById('franchise_id').value =
            btn.dataset.franchise

    }

    function closeModal() {

        document.getElementById('teamModal').style.display = 'none'

    }


    document.addEventListener('DOMContentLoaded', function () {

        const selectAll =
            document.getElementById('selectAll')

        const bulkForm =
            document.getElementById('bulkForm')

        const selectedIdsInput =
            document.getElementById('selectedIds')


        selectAll.addEventListener('change', function () {

            const checkboxes = document.querySelectorAll('.rowCheckbox');

            checkboxes.forEach(cb => {

                cb.checked = this.checked;

                const row = cb.closest('tr');

                if (this.checked) {
                    row.classList.add('selected-row');
                } else {
                    row.classList.remove('selected-row');
                }

            });

        });

        document.querySelectorAll('.rowCheckbox').forEach(cb => {

            cb.addEventListener('change', function () {

                const row = cb.closest('tr');

                row.classList.toggle('selected-row', cb.checked);

            });

        });


        bulkForm.addEventListener('submit', function (e) {

            let selected = []

            document.querySelectorAll('.rowCheckbox:checked')
                .forEach(cb => selected.push(cb.value))

            if (selected.length === 0) {

                e.preventDefault()

                alert('Please select at least one member')

                return

            }

            selectedIdsInput.value = selected.join(',')

        })

    })

    function closeAddForm() {

        const formCard = document.getElementById('addMemberCard')

        formCard.style.display = 'none'

        formCard.querySelector('form').reset()

    }

    function toggleRowSelection(row) {

        const checkbox = row.querySelector('.rowCheckbox');

        checkbox.checked = !checkbox.checked;

        row.classList.toggle('selected-row', checkbox.checked);

    }

</script>

@endpush