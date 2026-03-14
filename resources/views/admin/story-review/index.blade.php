@extends('layouts.admin')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-story-review.css') }}">
@endpush

<div class="admin-page">

    <h2 class="page-title">Stories & Reviews Management</h2>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div style="color:green; margin-bottom:15px;">
        {{ session('success') }}
    </div>
    @endif


    <div class="form-row">

        {{-- ADD STORY --}}
        <div class="card-box">
            <h3>Add Story</h3>

            <form action="{{ route('admin.stories.store') }}" method="POST" enctype="multipart/form-data"
                class="admin-form">

                @csrf

                <select name="franchise_id" required>
                    <option value="">Select Franchise</option>

                    @foreach($franchises as $franchise)
                    <option value="{{ $franchise->id }}">
                        {{ $franchise->location }}
                    </option>
                    @endforeach
                </select>

                <input type="file" name="story_img" required>

                <button class="btn-primary">Add Story</button>

            </form>
        </div>


        {{-- ADD REVIEW --}}
        <div class="card-box">
            <h3>Add Review</h3>

            <form action="{{ route('admin.reviews.store') }}" method="POST" class="admin-form">

                @csrf

                <select name="franchise_id" required>
                    <option value="">Select Franchise</option>

                    @foreach($franchises as $franchise)
                    <option value="{{ $franchise->id }}">
                        {{ $franchise->location }}
                    </option>
                    @endforeach
                </select>

                <input type="text" name="cust_name" placeholder="Customer Name" required>

                <textarea name="review_text" placeholder="Write review" required></textarea>

                <input type="number" name="rating" min="1" max="5" placeholder="Rating" required>

                <button class="btn-primary">Add Review</button>

            </form>
        </div>

    </div>


    <hr>


    {{-- ================= FILTER FOR VIEW ================= --}}
    <h3>Filter by Franchise</h3>

    <!-- MOBILE DROPDOWN -->
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


    <!-- DESKTOP BUTTON FILTER -->
    <div class="franchise-filter">

        <a href="{{ route('admin.story-review.index') }}"
            class="franchise-btn {{ request('franchise_id') ? '' : 'active' }}">
            All Franchises
        </a>

        @foreach($franchises as $franchise)

        <a href="{{ route('admin.story-review.index',['franchise_id'=>$franchise->id]) }}"
            class="franchise-btn {{ request('franchise_id')==$franchise->id ? 'active' : '' }}">

            {{ $franchise->location }}

        </a>

        @endforeach

    </div>


    <hr>


    {{-- ================= STORIES LIST ================= --}}
    <h3>Stories</h3>

    <form action="{{ route('admin.stories.bulkDelete') }}" method="POST">

        @csrf
        @method('DELETE')

        <label>
            <input type="checkbox" id="selectAllStories">
            Select All
        </label>

        <div class="story-grid">

            @forelse($stories as $story)

            <div class="story-card" onclick="toggleCardSelection(this)">

                <input type="checkbox" name="story_ids[]" value="{{ $story->id }}" onclick="event.stopPropagation()">

                <img src="{{ asset($story->story_img) }}" loading="lazy">

            </div>

            @empty

            <p>No stories found</p>

            @endforelse

        </div>

        @if(count($stories))
        <button class="delete-btn">Delete Selected Stories</button>
        @endif

    </form>


    <hr>


    {{-- ================= REVIEWS LIST ================= --}}
    <h3>Reviews</h3>

    <form action="{{ route('admin.reviews.bulkDelete') }}" method="POST">

        @csrf
        @method('DELETE')

        <label>
            <input type="checkbox" id="selectAllReviews">
            Select All
        </label>

        <div class="review-list">

            @forelse($reviews as $review)

            <div class="review-card" onclick="toggleCardSelection(this)">

                <input type="checkbox" name="review_ids[]" value="{{ $review->id }}" onclick="event.stopPropagation()">

                <h4>{{ $review->cust_name }}</h4>

                <p>{{ $review->review_text }}</p>

                <div class="rating-stars">

                    @for($i=1;$i<=5;$i++) @if($i <=$review->rating)
                        ⭐
                        @else
                        ☆
                        @endif

                        @endfor

                </div>

            </div>

            @empty

            <p>No reviews found</p>

            @endforelse

        </div>

        @if(count($reviews))
        <button class="delete-btn">Delete Selected Reviews</button>
        @endif

    </form>

</div>


{{-- ================= SELECT ALL SCRIPT ================= --}}
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

    function toggleCardSelection(card) {

        const checkbox = card.querySelector('input[type="checkbox"]');

        checkbox.checked = !checkbox.checked;

        card.classList.toggle('selected', checkbox.checked);
    }


    /* ===== SELECT ALL STORIES ===== */

    document.getElementById('selectAllStories')?.addEventListener('click', function () {

        const checkboxes = document.querySelectorAll('input[name="story_ids[]"]');

        checkboxes.forEach(cb => {

            cb.checked = this.checked;

            const card = cb.closest('.story-card');

            card.classList.toggle('selected', this.checked);

        });

    });


    /* ===== SELECT ALL REVIEWS ===== */

    document.getElementById('selectAllReviews')?.addEventListener('click', function () {

        const checkboxes = document.querySelectorAll('input[name="review_ids[]"]');

        checkboxes.forEach(cb => {

            cb.checked = this.checked;

            const card = cb.closest('.review-card');

            card.classList.toggle('selected', this.checked);

        });

    });


    /* ===== WHEN CHECKBOX CLICKED DIRECTLY ===== */

    document.querySelectorAll('.story-card input[type="checkbox"], .review-card input[type="checkbox"]').forEach(cb => {

        cb.addEventListener('change', function () {

            const card = this.closest('.story-card, .review-card');

            card.classList.toggle('selected', this.checked);

        });

    });

</script>
@endsection