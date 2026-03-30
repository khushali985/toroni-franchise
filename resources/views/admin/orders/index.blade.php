@extends('layouts.admin')

@section('title', 'Order Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-orders.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-orders-modal.css') }}">
@endpush

@section('content')

<div class="main">

    <h2>Order Management</h2>

    {{-- MOBILE DROPDOWN --}}
    <div class="franchise-dropdown-mobile">

        <select id="franchiseMobileSelect">

            <option value="">All Franchises</option>

            @foreach($franchises as $franchise)
            <option value="{{ $franchise->id }}" {{ request('franchise_id')==$franchise->id ? 'selected' : '' }}>
                {{ $franchise->location }}
            </option>
            @endforeach

        </select>

    </div>
    <div class="franchise-filter">
        <button class="franchise-btn active" data-id="">
            All Franchises
        </button>

        @foreach($franchises as $franchise)
        <button class="franchise-btn" data-id="{{ $franchise->id }}">
            {{ $franchise->location }}
        </button>
        @endforeach
    </div>

    <div class="filters">

        <input type="text" id="search" placeholder="Search by ID or Customer">

        <select id="status">
            <option value="">All Status</option>
            <option value="Pending">Pending</option>
            <option value="Preparing">Preparing</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
        </select>

        <input type="date" id="date">

        <!--<button onclick="fetchOrders()">Filter</button> -->

        <a href="{{ route('admin.orders.export') }}" class="export-btn">
            Export
        </a>
    </div>

    <div class="bulk-actions">
        <select id="bulkStatus">
            <option value="">Bulk Change Status</option>
            <option value="pending">Pending</option>
            <option value="preparing">Preparing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <button onclick="applyBulkStatus()">Apply</button>
    </div>
    <div class="table-wrapper">
        <table class="order-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Location</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="orderBody"></tbody>
        </table>
    </div>

    <div id="itemsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ordered Items</h2>
            </div>
            <div class="modal-body">
                <div id="itemsModalBody" class="items-list"></div>
                <button type="button" class="modal-close-btn" id="closeItemsModal">Close</button>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    let selectedFranchise = '';
    const itemsModal = document.getElementById('itemsModal');
    const itemsModalBody = document.getElementById('itemsModalBody');

    function parseOrderItems(rawItems) {
        let parsedItems = rawItems;

        // Some records are double-encoded JSON strings, so parse up to 2 times.
        for (let i = 0; i < 2; i++) {
            if (typeof parsedItems !== 'string') {
                break;
            }

            try {
                parsedItems = JSON.parse(parsedItems);
            } catch (e) {
                return null;
            }
        }

        if (parsedItems && typeof parsedItems === 'object') {
            return parsedItems;
        }

        return null;
    }

    function openItemsModal(rawItems) {
        const parsedItems = parseOrderItems(rawItems);

        if (!parsedItems || Object.keys(parsedItems).length === 0) {
            itemsModalBody.innerHTML = '<p class="empty-items">No items found</p>';
            itemsModal.style.display = 'block';
            return;
        }

        const escapeHtml = (value) => String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');

        const rows = Object.values(parsedItems).map(item => {
            const itemName = item?.name || 'Item';
            const itemQty = item?.qty ?? '-';
            return `
                <div class="item-row">
                    <p><strong>Item:</strong> ${escapeHtml(itemName)}</p>
                    <p><strong>Quantity:</strong> ${escapeHtml(itemQty)}</p>
                </div>
            `;
        }).join('');

        itemsModalBody.innerHTML = rows;
        itemsModal.style.display = 'block';
    }

    function closeItemsModal() {
        itemsModal.style.display = 'none';
    }

    document.getElementById('closeItemsModal').addEventListener('click', closeItemsModal);

    window.addEventListener('click', function (e) {
        if (e.target === itemsModal) {
            closeItemsModal();
        }
    });

    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('view-items-btn')) {
            const rawItems = e.target.dataset.items
                ? decodeURIComponent(e.target.dataset.items)
                : null;

            openItemsModal(rawItems);
            return;
        }

        if (e.target.classList.contains('franchise-btn')) {

            document.querySelectorAll('.franchise-btn')
                .forEach(btn => btn.classList.remove('active'));

            e.target.classList.add('active');

            // ✅ get franchise from button
            selectedFranchise = e.target.dataset.id;

            // sync mobile dropdown
            const mobileSelect = document.getElementById("franchiseMobileSelect");
            if (mobileSelect) {
                mobileSelect.value = selectedFranchise;
            }

            fetchOrders();
        }

    });

    const mobileSelect = document.getElementById("franchiseMobileSelect");

    if (mobileSelect) {

        mobileSelect.addEventListener("change", function () {

            selectedFranchise = this.value;

            fetchOrders();

        });

    }
    document.addEventListener('change', function (e) {

        if (e.target.id === 'selectAll') {

            let checkboxes = document.querySelectorAll('.orderCheckbox');

            checkboxes.forEach(cb => {

                cb.checked = e.target.checked;

                const row = cb.closest('tr');

                if (e.target.checked) {
                    row.classList.add('selected');
                } else {
                    row.classList.remove('selected');
                }

            });
        }
    });

    // Auto filter when typing in search
    document.getElementById('search').addEventListener('keyup', function () {
        fetchOrders();
    });

    // Auto filter when status changes
    document.getElementById('status').addEventListener('change', function () {
        fetchOrders();
    });

    // Auto filter when date changes
    document.getElementById('date').addEventListener('change', function () {
        fetchOrders();
    });

    function fetchOrders() {

        let search = document.getElementById('search').value;
        let status = document.getElementById('status').value;
        let date = document.getElementById('date').value;

        fetch(`{{ route('admin.orders.fetch') }}?search=${search}&status=${status}&date=${date}&franchise=${selectedFranchise}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Server error");
                }
                return response.json();
            })
            .then(data => {

                let rows = '';

                if (!data || data.length === 0) {
                    rows = `<tr><td colspan="8">No Orders Found</td></tr>`;
                }

                data.forEach(order => {
                    let orderStatus = (order.status ?? 'pending').toLowerCase();

                    let options = '';

                    switch (orderStatus) {

                        case 'pending':
                            options = `
                                <option value="pending" selected>Pending</option>
                                <option value="preparing">Preparing</option>
                                <option value="cancelled">Cancelled</option>
                            `;
                            break;

                        case 'preparing':
                            options = `
                                <option value="preparing" selected>Preparing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            `;
                            break;

                        case 'completed':
                            options = `
                                <option value="completed" selected>Completed</option>
                            `;
                            break;

                        case 'cancelled':
                            options = `
                                <option value="cancelled" selected>Cancelled</option>
                            `;
                            break;

                        default:
                            options = `
                                <option value="pending" selected>Pending</option>
                            `;
                    }

                    const encodedItems = encodeURIComponent(JSON.stringify(order.items ?? null));

                    rows += `
                    <tr class="orderRow">
                        <td>
                            <input type="checkbox" class="orderCheckbox" value="${order.id}">
                        </td>
                        <td>${order.id}</td>
                        <td>${order.full_name}</td>
                        <td>
                            <button type="button" class="view-items-btn" data-items="${encodedItems}">
                                View Items
                            </button>
                        </td>
                        <td>${order.franchise ? order.franchise.location : '-'}</td>
                        <td>₹${order.total}</td>
                        <td>
                            <select onchange="updateStatus(${order.id}, this.value)">
                                ${options}
                            </select>
                        </td>
                        <td>${order.created_at.substring(0, 10)}</td>
                    </tr>
                    `;

                });

                document.getElementById('orderBody').innerHTML = rows;
                // ================= ROW CLICK SELECTION =================

                document.querySelectorAll('.orderRow').forEach(row => {

                    row.addEventListener('click', function (e) {

                        // prevent clicking checkbox or dropdown
                        if (
                            e.target.tagName === 'INPUT' ||
                            e.target.tagName === 'SELECT' ||
                            e.target.tagName === 'BUTTON'
                        ) {
                            return;
                        }

                        const checkbox = row.querySelector('.orderCheckbox');

                        checkbox.checked = !checkbox.checked;

                        row.classList.toggle('selected', checkbox.checked);

                    });

                });

                // ================= UPDATE ROW STYLE IF CHECKBOX USED =================

                document.querySelectorAll('.orderCheckbox').forEach(cb => {

                    cb.addEventListener('change', function () {

                        const row = cb.closest('tr');

                        row.classList.toggle('selected', cb.checked);

                    });

                });
            })
            .catch(error => {
                console.error(error);
                alert("Something went wrong. Check Laravel log.");
            });
    }

    function updateStatus(id, status) {

        fetch(`/admin/orders/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
                }
                fetchOrders(); // always refresh UI
            })
            .catch(() => {
                alert("Status update failed");
                fetchOrders();
            });


    }


    function applyBulkStatus() {

        let status = document.getElementById('bulkStatus').value;

        if (!status) {
            alert("Please select a status");
            return;
        }

        let selected = [];

        document.querySelectorAll('.orderCheckbox:checked')
            .forEach(cb => selected.push(cb.value));

        if (selected.length === 0) {
            alert("Please select at least one order");
            return;
        }

        fetch(`{{ route('admin.orders.bulk') }}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                ids: selected,
                status: status
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchOrders();
                    document.getElementById('bulkStatus').value = '';
                    document.getElementById('selectAll').checked = false;
                } else {
                    alert(data.message);
                }
            })
            .catch(() => {
                alert("Bulk update failed");
            });
    }

    // Auto load on page start
    fetchOrders();
</script>
@endpush