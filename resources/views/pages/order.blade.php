@extends('layouts.app')

@section('title', 'Food Order')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/order.css') }}">
@endpush

@section('content')

<!-- TOAST -->
<div id="toast"></div>

@if(session('success'))
<div id="successModal" class="success-modal">
    <div class="success-box">
        <span class="close-btn" onclick="closeSuccess()">×</span>
        <h3>Order placed successfully!</h3>
    </div>
</div>
@endif

<div class="app">

    <!-- LEFT PANEL -->
    <form method="POST" action="{{ route('order.store') }}">
        @csrf

        <h2 class="order-heading">ORDER NOW</h2>

        <input class="input" name="full_name" id="nameInput" placeholder="Your Name" />
        <input class="input" type="email" name="email" id="emailInput" placeholder="Your Email" required />
        <input class="input" name="phone" id="phoneInput" placeholder="Phone Number" />
        <input class="input" name="address" id="addressInput" placeholder="Delivery Address" />


        <div id="cart">
            <!--<p id="emptyCartMsg" class="empty-cart">Your cart is empty</p>-->
        </div>

        <div class="total">
            Total: ₹<span id="total">0</span>
        </div>

        <!-- Hidden fields -->
        <input type="hidden" name="items" id="itemsInput">
        <input type="hidden" name="total" id="totalInput">
        <input type="hidden" name="franchise_id" id="selectedFranchise" value="{{ session('selected_franchise') }}">

        <button type="submit" class="confirm" id="confirmBtn" disabled>
            Confirm Your Order
        </button>
    </form>

    <!-- RIGHT PANEL -->
    <div class="menu-panel">

        <!-- STEP 1: Franchise Selection Screen -->
        <div id="franchiseScreen" class="franchise-screen">

            <h2>Select The Franchise for Your Order</h2>

            <div class="franchise-grid">
                @foreach($franchises as $franchise)
                <div class="franchise-card"
                    onclick="selectFranchise('{{ $franchise->id }}', '{{ $franchise->location }}')">
                    {{ $franchise->location }}
                </div>
                @endforeach
            </div>

        </div>


        <!-- STEP 2: Menu Content (Hidden Initially) -->
        <div id="menuContent" style="display:none;">

            <div class="menu-header">
                <h2 id="selectedFranchiseTitle">Menu</h2>

                <button type="button" class="change-franchise" onclick="changeFranchise()">
                    Change Franchise
                </button>
            </div>

            <!-- Search -->
            <div class="top-controls">
                <input type="text" id="searchInput" placeholder="Search dish...">
            </div>

            <!-- Category Filters -->
            <div class="filters">

                <button class="filter active" data-category="all" onclick="filterCategory('all', this)">
                    All
                </button>

                @foreach($categories as $category)
                <button class="filter" data-category="{{ strtolower($category) }}"
                    onclick="filterCategory('{{ strtolower($category) }}', this)">
                    {{ ucfirst($category) }}
                </button>
                @endforeach

            </div>

            <!-- Menu Grid -->
            <div class="menu-grid">

                @forelse ($menuItems as $item)

                <div class="menu-item" data-name="{{ $item->dish_name }}" data-category="{{ $item->category }}"
                    data-franchise="{{ $item->franchise_id }}">

                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->dish_name }}" loading="lazy">

                    <div class="price-tag">₹{{ $item->price }}</div>

                    <div class="dish-name">{{ $item->dish_name }}</div>

                    <button class="add-btn" onclick="addToCart('{{ $item->dish_name }}', '{{ $item->price }}', this)">
                        + Add
                    </button>

                    <div class="ingredients">
                        <h4>Ingredients</h4>
                        <ul>
                            @php
                            $ingredients = json_decode($item->ingredients, true);
                            @endphp

                            @if(is_array($ingredients))
                            @foreach($ingredients as $ingredient)
                            <li>{{ $ingredient }}</li>
                            @endforeach
                            @else
                            <li>{{ $item->ingredients }}</li>
                            @endif
                        </ul>
                    </div>

                </div>

                @empty
                <p style="padding:20px;">No menu items available.</p>
                @endforelse

            </div>

        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/order.js') }}"></script>
@endpush