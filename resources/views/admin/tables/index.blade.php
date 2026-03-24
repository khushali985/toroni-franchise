@extends('layouts.admin')

@section('title', 'Table Management')

@section('content')

<section class="admin-dashboard">

    <h2 class="dashboard-title">Table Management</h2>

    {{-- ================= ADD TABLE ================= --}}
    <div class="card add-table-card">
        <h4>Add New Table</h4>

        <form method="POST" action="{{ route('admin.tables.store') }}">
            @csrf

            <div class="form-grid">

                <select name="franchise_id" required>
                    <option value="">Select Franchise</option>
                    @foreach($franchises as $franchise)
                    <option value="{{ $franchise->id }}">
                        {{ $franchise->location }}
                    </option>
                    @endforeach
                </select>

                <input type="number" name="table_no" placeholder="Table Number" required>
                <input type="number" name="capacity_people" placeholder="Capacity" required>

                <button type="submit" class="btn-success">
                    Add Table
                </button>

            </div>
        </form>
    </div>

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
    {{-- ================= FRANCHISE FILTER ================= --}}

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

    {{-- ================= STATS ================= --}}
    <div class="stats-grid">

        <div class="card">
            <h4>Total Tables</h4>
            <p>{{ $totalTables }}</p>
        </div>

        <div class="card">
            <h4>Available</h4>
            <p>{{ $availableTables }}</p>
        </div>

        <div class="card">
            <h4>Occupied</h4>
            <p>{{ $notAvailableTables }}</p>
        </div>

    </div>

    {{-- ================= TABLE SECTION ================= --}}
    <div class="reservation-section card-container">

        <div class="reservation-header">
            <h3>Manage Tables</h3>

            {{-- FILTER FORM --}}
            <!-- <form method="GET" action="{{ route('admin.tables.index') }}" class="filter-form">

                @if($selectedFranchise)
                <input type="hidden" name="franchise_id" value="{{ $selectedFranchise->id }}">
                @endif

                <select name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status')=='available' ? 'selected' : '' }}>
                        Available
                    </option>
                    <option value="not available" {{ request('status')=='not available' ? 'selected' : '' }}>
                        Not Available
                    </option>
                </select>

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search table no"
                    onkeyup="setTimeout(() => this.form.submit(), 500)">
            </form> -->
            <form method="GET" action="{{ route('admin.tables.index') }}" class="filter-form">

                @if($selectedFranchise)
                <input type="hidden" name="franchise_id" value="{{ $selectedFranchise->id }}">
                @endif

                {{-- ✅ NEW DATE & TIME FILTER --}}
                <input type="date" name="date" value="{{ request('date') }}">
                <select name="time">
                    <option value="">Select Time</option>

                    @php
                    $slots = [
                    "12:00","12:30","13:00","13:30",
                    "14:00","14:30",
                    "18:00","18:30",
                    "19:00","19:30",
                    "20:00","20:30",
                    "21:00","21:30"
                    ];
                    @endphp

                    @foreach($slots as $slot)
                    <option value="{{ $slot }}" {{ request('time')==$slot ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::parse($slot)->format('h:i A') }}
                    </option>
                    @endforeach
                </select>

                {{-- STATUS --}}
                <select name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status')=='available' ? 'selected' : '' }}>
                        Available
                    </option>
                    <option value="not available" {{ request('status')=='not available' ? 'selected' : '' }}>
                        Not Available
                    </option>
                </select>

                {{-- SEARCH --}}
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search table no"
                    onkeyup="setTimeout(() => this.form.submit(), 500)">

                {{-- 🔥 OPTIONAL: ADD BUTTON (better UX) --}}
                <button type="submit" class="btn-dark">Filter</button>

            </form>
        </div>

        {{-- ================= BULK FORM (SEPARATE) ================= --}}
        <form method="POST" action="{{ route('admin.tables.bulk') }}" id="bulkForm">
            @csrf

            <div class="reservation-actions" style="margin-bottom:15px;">
                <select name="action" required>
                    <option value="">Bulk Actions</option>
                    <option value="available">Mark Available</option>
                    <option value="not available">Mark Not Available</option>
                    <option value="delete">Delete</option>
                </select>

                <button type="submit" class="btn-dark">Apply</button>
            </div>

            {{-- Hidden inputs will be appended here --}}
            <div id="selectedTablesContainer"></div>
        </form>

        {{-- ================= TABLE ================= --}}
        <div class="table-wrapper">

            <table class="reservation-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Franchise</th>
                        <th>Table No</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tables as $table)
                    <tr onclick="toggleCheckbox(this)">

                        <td>
                            <input type="checkbox" class="rowCheckbox" value="{{ $table->id }}"
                                onclick="event.stopPropagation()">
                        </td>

                        <td>{{ $table->franchise->location }}</td>
                        <td>{{ $table->table_no }}</td>
                        <td>{{ $table->capacity_people }}</td>

                        <!-- <td>
                            <span
                                class="status-badge {{ $table->status == 'available' ? 'status-available' : 'status-not-available' }}">
                                {{ ucfirst($table->status) }}
                            </span>
                        </td> -->

                        <td>

                            @if(request('date') && request('time'))

                            @if($table->is_booked > 0)
                            <span class="status-badge status-not-available">
                                Booked
                            </span>
                            @else
                            <span class="status-badge status-available">
                                Available
                            </span>
                            @endif

                            @else

                            <span
                                class="status-badge {{ $table->status == 'available' ? 'status-available' : 'status-not-available' }}">
                                {{ ucfirst($table->status) }}
                            </span>

                            @endif

                        </td>

                        <td class="action-buttons">

                            @if(request('date') && request('time'))

                            <form method="POST" action="{{ route('admin.tables.toggleSlot', $table) }}"
                                style="display:inline;">
                                @csrf

                                <input type="hidden" name="date" value="{{ request('date') }}">
                                <input type="hidden" name="time" value="{{ request('time') }}">

                                @if($table->is_booked > 0)

                                {{-- 🔴 Already booked → allow release --}}
                                <button type="submit" class="btn-success">
                                    Release (Make Available)
                                </button>

                                @else

                                {{-- 🟢 Available → allow block --}}
                                <button type="submit" class="btn-dangers">
                                    Block (Mark Not Available)
                                </button>

                                @endif
                            </form>

                            @else

                            {{-- 🔁 NORMAL STATIC ACTIONS --}}
                            <form method="POST" action="{{ route('admin.tables.status', $table) }}"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')

                                @if($table->status == 'available')
                                <button type="submit" class="btn-dangers">
                                    Mark Not Available
                                </button>
                                @else
                                <button type="submit" class="btn-success">
                                    Mark Available
                                </button>
                                @endif
                            </form>

                            @endif

                            {{-- DELETE ALWAYS AVAILABLE --}}
                            <form method="POST" action="{{ route('admin.tables.destroy', $table) }}"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-danger">
                                    Delete
                                </button>
                            </form>

                        </td>

                        <!-- <td class="action-buttons">

                            {{-- TOGGLE STATUS --}}
                            <form method="POST" action="{{ route('admin.tables.status', $table) }}"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')

                                @if($table->status == 'available')
                                <button type="submit" class="btn-dangers">
                                    Mark Not Available
                                </button>
                                @else
                                <button type="submit" class="btn-success">
                                    Mark Available
                                </button>
                                @endif
                            </form>

                            {{-- DELETE --}}
                            <form method="POST" action="{{ route('admin.tables.destroy', $table) }}"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-danger">
                                    Delete
                                </button>
                            </form>

                        </td> -->

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">No tables found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-wrapper">
                {{ $tables->withQueryString()->links() }}
            </div>

        </div>

    </div>

</section>

@endsection


@push('scripts')
<script>
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
    document.addEventListener('DOMContentLoaded', function () {

        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.rowCheckbox');
        const bulkForm = document.getElementById('bulkForm');
        const container = document.getElementById('selectedTablesContainer');

        // Select all
        selectAll.addEventListener('change', function () {

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


        bulkForm.addEventListener('submit', function (e) {

            container.innerHTML = '';

            const checked = document.querySelectorAll('.rowCheckbox:checked');

            if (checked.length === 0) {
                e.preventDefault();
                alert('Please select at least one table.');
                return;
            }

            checked.forEach(cb => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_tables[]';
                input.value = cb.value;
                container.appendChild(input);
            });

        });

    });

    // row click selection
    function toggleCheckbox(row) {

        const checkbox = row.querySelector('.rowCheckbox');

        checkbox.checked = !checkbox.checked;

        row.classList.toggle('selected-row', checkbox.checked);

    }
</script>

<link rel="stylesheet" href="{{ asset('css/admin-table.css') }}">
@endpush