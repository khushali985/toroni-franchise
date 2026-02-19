@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')

<section class="admin-dashboard">

    <h1 class="dashboard-title">Admin Dashboard</h1>
    <div class="franchise-selection">
        <h2>Select Franchise</h2>

        <div class="franchise-card-container">

            <a href="{{ route('admin.dashboard') }}" class="franchise-card {{ !$selectedFranchise ? 'active' : '' }}">
                All Franchises
            </a>

            @foreach($franchises as $franchise)
            <a href="{{ route('admin.dashboard', ['franchise_id' => $franchise->id]) }}" class="franchise-card 
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


    {{-- ================= CHART SECTION ================= --}}
    <!-- <div class="charts-grid">

        <div class="chart-card">
            <h3>Monthly Revenue</h3>
            <canvas id="revenueChart"></canvas>
        </div>

        <div class="chart-card">
            <h3>Monthly Reservations</h3>
            <canvas id="reservationChart"></canvas>
        </div>

    </div> 


    {{-- ================= RECENT RESERVATIONS ================= --}}
    <div class="table-section">
        <h2>Recent Reservations</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($recentReservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->name }}</td>
                    <td>{{ $reservation->date }}</td>
                    <td>{{ ucfirst($reservation->status) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No reservations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    {{-- ================= RECENT ORDERS ================= --}}
    <div class="table-section">
        <h2>Recent Orders</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->full_name ?? 'N/A' }}</td>
                    <td>₹ {{ number_format($order->total ?? 0, 2) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div> -->

    <div class="reservation-section card-container">

        <div class="reservation-header">
            <h2>Reservation Management</h2>

            <div class="reservation-actions">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-form">

                    <select name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                        <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed
                        </option>
                    </select>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search name / phone / date">

                    <button type="submit" class="btn-primary">Filter</button>
                </form>

            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-wrapper">
            <table class="reservation-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>no of people</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
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
                            <span class="payment-badge">
                                {{ ucfirst($reservation->payment_status ?? 'N/A') }}
                            </span>
                        </td>

                        <td class="action-buttons">

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

                            {{-- COMPLETE --}}
                            <form method="POST" action="{{ route('admin.reservation.status', $reservation->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn-info">Complete</button>
                            </form>

                            <button type="button" class="btn-secondary view-btn" data-id="{{ $reservation->id }}"
                                data-name="{{ $reservation->full_name }}" data-phone="{{ $reservation->phone_no }}"
                                data-date="{{ $reservation->date }}" data-time="{{ $reservation->time }}"
                                data-no_of_people="{{ $reservation->no_of_people }}">
                                View
                            </button>

                            {{-- DELETE --}}
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
        </div>

        {{ $reservations->withQueryString()->links() }}

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


@endpush