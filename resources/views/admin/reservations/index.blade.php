@extends('layouts.admin')

@section('title', 'Admin Panel')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-reservation.css') }}">
@endpush

@section('content')

<section class="admin-dashboard">

    <h1 class="dashboard-title">Admin Dashboard</h1>
    <div class="franchise-selection">
        <h2>Select Franchise</h2>

        <div class="franchise-card-container">

            <a href="{{ route('reservations.index') }}"
                class="franchise-card {{ !$selectedFranchise ? 'active' : '' }}">
                All Franchises
            </a>

            @foreach($franchises as $franchise)
            <a href="{{ route('reservations.index', ['franchise_id' => $franchise->id]) }}" class="franchise-card 
                {{ $selectedFranchise && $selectedFranchise->id == $franchise->id ? 'active' : '' }}">

                <h3>{{ $franchise->location }}</h3>
                <p>{{ $franchise->address }}</p>

            </a>
            @endforeach
        </div>
    </div>


    {{-- ================= STATS CARDS ================= --}}
    <div class="stats-grid">

        <div class="card">
            <h3>Total Reservations</h3>
            <p>{{ $totalReservations }}</p>
            <small>
                Pending: {{ $pendingReservations }} |
                Approved: {{ $approvedReservations }} |
                Cancelled: {{ $cancelledReservations }} |
                Completed: {{ $completedReservations }}
            </small>
        </div>

        <div class="card">
            <h3>Total Orders</h3>
            <p>{{ $totalOrders }}</p>
            <small>
                Pending: {{ $pendingOrders }} |
                Completed: {{ $completedOrders }} |
                Cancelled: {{ $cancelledOrders }} |
                Preparing: {{ $preparingOrders }}
            </small>
        </div>

        <div class="card">
            <h3>Total Revenue</h3>
            <p>₹ {{ number_format($totalRevenue, 2) }}</p>
            <small>Today: ₹ {{ number_format($todayRevenue, 2) }}</small>
        </div>

        <div class="card">
            <h3>Total Customers</h3>
            <p>{{ $totalCustomers }}</p>
            <small>New Today: {{ $newCustomersToday }}</small>
        </div>

    </div>


    <div class="reservation-section card-container">

        <div class="reservation-header">
            <h2>Reservation Management</h2>

            <div class="reservation-actions">
                <form method="POST" action="{{ route('admin.reservation.bulk') }}" id="bulkForm">
                    @csrf
                    <input type="hidden" name="reservation_ids" id="selectedIds">

                    <select name="status" required>
                        <option value="">Actions</option>
                        <option value="approved">Approve Selected</option>
                        <option value="completed">Complete Selected</option>
                        <option value="cancelled">Cancel Selected</option>
                        <option value="delete">Delete Selected</option>
                        <option value="export_pdf">Export as PDF</option>
                        <option value="export_excel">Export as Excel</option>
                    </select>

                    <button type="submit" class="btn-dark">
                        Apply
                    </button>
                </form>

                <form method="GET" action="{{ route('reservations.index') }}" class="filter-form" id="filterForm">

                    {{-- KEEP SELECTED FRANCHISE --}}
                    @if($selectedFranchise)
                    <input type="hidden" name="franchise_id" value="{{ $selectedFranchise->id }}">
                    @endif

                    <select name="status" id="statusFilter">
                        <option value="">All Status</option>

                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>
                            Approved
                        </option>

                        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>
                            Cancelled
                        </option>

                        <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>

                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                        placeholder="Search name / phone / date">

                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-wrapper">
            <div class="pagination-info">
                <strong>{{ $reservations->total() }}</strong> reservations |
                Page {{ $reservations->currentPage() }}
                of {{ $reservations->lastPage() }}
            </div>
            <table class="reservation-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>no of people</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Transaction ID</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td>
                            <input type="checkbox" class="rowCheckbox" value="{{ $reservation->id }}">
                        </td>
                        <td>{{ $reservation->id }}</td>
                        <td>{{ $reservation->full_name }}</td>
                        <td>{{ $reservation->phone_no }}</td>
                        <td>{{ $reservation->date }} {{ $reservation->time }}</td>
                        <td>{{ $reservation->no_of_people }}</td>
                        <td>{{ $reservation->franchise->location ?? 'N/A' }}</td>
                        <td>
                            <span class="status-badge status-{{ $reservation->status }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>

                        <td>
                            {{ $reservation->transaction_id ?? 'N/A' }}
                        </td>

                        <td>
                            @if($reservation->payment_status === 'approved')

                            <span class="text-success fw-bold">Approved</span>

                            @else

                            <form method="POST"
                                action="{{ route('admin.reservation.togglePayment', $reservation->id) }}">
                                @csrf
                                @method('PATCH')

                                <select name="payment_status" onchange="this.form.submit()" class="payment-dropdown">

                                    <option value="pending" {{ $reservation->payment_status === 'pending' ? 'selected' :
                                        '' }}>
                                        Pending
                                    </option>

                                    <option value="approved">
                                        Approve Payment
                                    </option>

                                </select>

                            </form>

                            @endif
                        </td>

                        <td class="action-buttons">

                            @if(in_array($reservation->status, ['completed', 'cancelled']))

                            <span class="text-muted">Finalized</span>

                            @else

                            {{-- If Pending --}}
                            @if($reservation->status === 'pending')

                            {{-- APPROVE --}}
                            <form method="POST" action="{{ route('admin.reservation.status', $reservation->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn-success">Approve</button>
                            </form>

                            {{-- CANCEL --}}
                            <form method="POST" action="{{ route('admin.reservation.status', $reservation->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn-danger">Cancel</button>
                            </form>

                            @endif


                            {{-- If Approved --}}
                            @if($reservation->status === 'approved')

                            {{-- COMPLETE --}}
                            <form method="POST" action="{{ route('admin.reservation.status', $reservation->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn-info">Complete</button>
                            </form>

                            {{-- CANCEL --}}
                            <form method="POST" action="{{ route('admin.reservation.status', $reservation->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn-danger">Cancel</button>
                            </form>

                            @endif

                            @endif

                            {{-- VIEW (always allowed) --}}
                            <button type="button" class="btn-secondary view-btn" data-id="{{ $reservation->id }}"
                                data-name="{{ $reservation->full_name }}" data-phone="{{ $reservation->phone_no }}"
                                data-date="{{ $reservation->date }}" data-time="{{ $reservation->time }}"
                                data-no_of_people="{{ $reservation->no_of_people }}">
                                View
                            </button>

                            {{-- DELETE (optional: disable if finalized) --}}

                            <form method="POST" action="{{ route('admin.reservation.delete', $reservation->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-dark delete-btn">Delete</button>
                            </form>


                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No reservations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-wrapper">
                {{ $reservations->withQueryString()->links() }}
            </div>
        </div>

    </div>

    {{-- MODAL --}}
    <div id="reservationModal" class="modal">
        <div class="modal-content">
            <h3>Reservation Details</h3>
            <p><strong>Name:</strong> <span id="modalName"></span></p>
            <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
            <p><strong>Date:</strong> <span id="modalDate"></span></p>
            <p><strong>Time:</strong> <span id="modalTime"></span></p>
            <p><strong>no_of_people:</strong> <span id="modalno_of_people"></span></p>
            <p><strong>Location:</strong> <span id="modalLocation"></span></p>

            <button id="closeModal" class="btn-dark">Close</button>
        </div>
    </div>

</section>

@endsection


@push('scripts')

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="{{ asset('js/admin-dashboard.js') }}"></script>

{{-- JSON DATA BLOCKS --}}
<script id="revenue-data" type="application/json">
    {!! json_encode($monthlyRevenue ?? []) !!}
</script>

<script id="reservation-data" type="application/json">
    {!! json_encode($monthlyReservations ?? []) !!}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Parse JSON safely
        const monthlyRevenue = JSON.parse(
            document.getElementById('revenue-data').textContent
        );

        const monthlyReservations = JSON.parse(
            document.getElementById('reservation-data').textContent
        );

        const months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        // ================= REVENUE CHART =================
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenue',
                    data: months.map((_, i) => monthlyRevenue[i + 1] ?? 0),
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // ================= RESERVATION CHART =================
        new Chart(document.getElementById('reservationChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Reservations',
                    data: months.map((_, i) => monthlyReservations[i + 1] ?? 0),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('filterForm');
        const status = document.getElementById('statusFilter');
        const search = document.getElementById('searchInput');

        // Auto submit when status changes
        status.addEventListener('change', function () {
            form.submit();
        });

        // Auto submit while typing (debounced)
        let typingTimer;

        search.addEventListener('keyup', function () {

            clearTimeout(typingTimer);

            typingTimer = setTimeout(function () {
                form.submit();
            }, 400); // 400ms delay
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.rowCheckbox');
        const bulkForm = document.getElementById('bulkForm');
        const selectedIdsInput = document.getElementById('selectedIds');

        // Select All checkbox
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // On form submit
        bulkForm.addEventListener('submit', function (e) {

            let selected = [];

            document.querySelectorAll('.rowCheckbox:checked')
                .forEach(cb => selected.push(cb.value));

            if (selected.length === 0) {
                e.preventDefault();
                alert('Please select at least one reservation.');
                return;
            }

            selectedIdsInput.value = selected.join(',');
        });

    });
</script>

@endpush