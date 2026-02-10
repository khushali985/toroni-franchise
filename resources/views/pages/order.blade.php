@extends('layouts.app')

@section('title', 'Food Order')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/order.css') }}">
@endpush

@section('content')

<!-- TOAST -->
<div id="toast"></div>

<div class="app">

    <!-- LEFT PANEL -->
    <div class="order-panel">

        <button class="order-now" id="orderNowBtn">ORDER NOW</button>

        <input class="input" id="searchInput" placeholder="Search dish (press /)" />
        <input class="input" id="nameInput" placeholder="Your Name" />
        <input class="input" id="phoneInput" placeholder="Phone Number" />
        <input class="input" id="addressInput" placeholder="Delivery Address" />

        <div id="cart"></div>

        <div class="total">
            Total: ₹<span id="total">0</span>
        </div>

        <button class="confirm" id="confirmBtn" disabled>
            Confirm Your Order
        </button>
    </div>

    <!-- RIGHT PANEL -->
    <div class="menu-panel">
        <h2>Menu</h2>

        <div class="filters">
            <button class="filter active" onclick="filterCategory('all', this)">All</button>
            <button class="filter" onclick="filterCategory('pizza', this)">Pizza</button>
            <button class="filter" onclick="filterCategory('pasta', this)">Pasta</button>
            <button class="filter" onclick="filterCategory('chinese', this)">Chinese</button>
            <button class="filter" onclick="filterCategory('fastfood', this)">Fast Food</button>
            <button class="filter" onclick="filterCategory('dessert', this)">Desserts</button>
        </div>

        <div class="menu-grid">

            @foreach ($menuItems as $item)
            <div class="menu-item" data-name="{{ $item->name }}" data-category="{{ $item->category }}">

                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">

                <div class="price-tag">₹{{ $item->price }}</div>

                <div class="dish-name">{{ $item->name }}</div>

                <button class="add-btn" onclick="addToCart('{{ $item->name }}', '{{ $item->price }}', this)">
                    + Add
                </button>

                <div class="ingredients">
                    <h4>Ingredients</h4>
                    <ul>
                        @foreach (json_decode($item->ingredients) as $ingredient)
                        <li>{{ $ingredient }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach

        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/order.js') }}"></script>
@endpush