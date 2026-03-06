@extends('layouts.admin')

@section('title', 'Table Management')

@section('content')

<section class="admin-dashboard">

    <h1 class="dashboard-title">Table Management</h1>

    {{-- ================= ADD TABLE ================= --}}
    <div class="card add-table-card">
        <h3>Add New Table</h3>

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

    {{-- ================= FRANCHISE FILTER ================= --}}
    <div class="franchise-selection">
        <h2>Select Franchise</h2>

        <div class="franchise-card-container">

            <a href="{{ route('admin.tables.index') }}"
                class="franchise-card {{ !$selectedFranchise ? 'active' : '' }}">
                All Franchises
            </a>

            @foreach($franchises as $franchise)
            <a href="{{ route('admin.tables.index', ['franchise_id' => $franchise->id]) }}"
                class="franchise-card {{ $selectedFranchise && $selectedFranchise->id == $franchise->id ? 'active' : '' }}">
                <h3>{{ $franchise->location }}</h3>
            </a>
            @endforeach

        </div>
    </div>

    {{-- ================= STATS ================= --}}
    <div class="stats-grid">

        <div class="card">
            <h3>Total Tables</h3>
            <p>{{ $totalTables }}</p>
        </div>

        <div class="card">
            <h3>Available</h3>
            <p>{{ $availableTables }}</p>
        </div>

        <div class="card">
            <h3>Occupied</h3>
            <p>{{ $notAvailableTables }}</p>
        </div>

    </div>

    {{-- ================= TABLE SECTION ================= --}}
    <div class="reservation-section card-container">

        <div class="reservation-header">
            <h2>Manage Tables</h2>

            {{-- FILTER FORM --}}
            <form method="GET" action="{{ route('admin.tables.index') }}" class="filter-form">

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
                    <tr>

                        <td>
                            <input type="checkbox" class="rowCheckbox" value="{{ $table->id }}">
                        </td>

                        <td>{{ $table->franchise->location }}</td>
                        <td>{{ $table->table_no }}</td>
                        <td>{{ $table->capacity_people }}</td>

                        <td>
                            <span
                                class="status-badge {{ $table->status == 'available' ? 'status-available' : 'status-not-available' }}">
                                {{ ucfirst($table->status) }}
                            </span>
                        </td>

                        <td class="action-buttons">

                            {{-- TOGGLE STATUS --}}
                            <form method="POST" action="{{ route('admin.tables.status', $table) }}"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')

                                @if($table->status == 'available')
                                <button type="submit" class="btn-danger">
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

                        </td>

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
    document.addEventListener('DOMContentLoaded', function () {

        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.rowCheckbox');
        const bulkForm = document.getElementById('bulkForm');
        const container = document.getElementById('selectedTablesContainer');

        // Select all
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
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
</script>

<link rel="stylesheet" href="{{ asset('css/admin-table.css') }}">
@endpush