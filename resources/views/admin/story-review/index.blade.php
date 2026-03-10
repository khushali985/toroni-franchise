@extends('layouts.admin')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-story-review.css') }}">
@endpush

<div class="admin-page">

    <h2 class="page-title">Stories & Reviews Management</h2>

    {{-- Success Message --}}
    @if(session('success'))
    <div style="color: green; margin-bottom:15px;">
        {{ session('success') }}
    </div>
    @endif


    {{-- ================= Franchise Filter (ONLY ONE) ================= --}}
    <div class="franchise-filter">
        <form method="GET" action="{{ route('admin.story-review.index') }}">
            <label>Select Franchise</label>

            <select name="franchise_id" onchange="this.form.submit()">
                <option value="">All Franchises</option>

                @foreach($franchises as $franchise)
                <option value="{{ $franchise->id }}" {{ $selectedFranchise==$franchise->id ? 'selected' : '' }}>
                    {{ $franchise->location }}
                </option>
                @endforeach

            </select>
        </form>
    </div>

    <hr>

    {{-- ================= STORIES SECTION ================= --}}
    <div class="card-box">

        <h3>Add Story</h3>

        @if($selectedFranchise)
        <form action="{{ route('admin.stories.store') }}" method="POST" enctype="multipart/form-data"
            class="admin-form">
            @csrf

            <input type="hidden" name="franchise_id" value="{{ $selectedFranchise }}">

            <input type="file" name="story_img" required>

            <button class="btn-primary">Upload Story</button>

        </form>
        @endif

    </div>

    <br>

    <h4>Existing Stories</h4>

    <div class="story-grid">

        @foreach($stories as $story)

        <div class="story-card">

            <img src="{{ asset($story->story_img) }}">

            <form action="{{ route('admin.stories.delete',$story->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button class="delete-btn">Delete</button>
            </form>

        </div>

        @endforeach

    </div>

    <hr>
    <hr>


    {{-- ================= REVIEWS SECTION ================= --}}
    <div class="card-box">

        <h3>Add Review</h3>

        @if($selectedFranchise)

        <form action="{{ route('admin.reviews.store') }}" method="POST" class="admin-form">
            @csrf

            <input type="hidden" name="franchise_id" value="{{ $selectedFranchise }}">

            <input type="text" name="cust_name" placeholder="Customer Name">

            <textarea name="review_text" placeholder="Write review"></textarea>

            <input type="number" name="rating" min="1" max="5" placeholder="Rating">

            <button class="btn-primary">Add Review</button>

        </form>

        @endif

    </div>

    <br>

    <h4>Existing Reviews</h4>

    <div class="review-list">

        @foreach($reviews as $review)

        <div class="review-card">

            <h4>{{ $review->cust_name }}</h4>

            <p>{{ $review->review_text }}</p>

            <div class="rating-stars">
                @for($i = 1; $i <= 5; $i++) @if($i <=$review->rating)
                    ⭐
                    @else
                    ☆
                    @endif
                    @endfor
            </div>

            <form action="{{ route('admin.reviews.delete',$review->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button class="delete-btn">Delete</button>
            </form>



        </div>

        @endforeach

    </div>

</div>

@endsection