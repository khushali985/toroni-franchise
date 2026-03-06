@extends('layouts.admin')

@section('content')

<div class="container">

    <h2>Stories & Reviews Management</h2>

    {{-- Success Message --}}
    @if(session('success'))
    <div style="color: green; margin-bottom:15px;">
        {{ session('success') }}
    </div>
    @endif


    {{-- ================= Franchise Filter (ONLY ONE) ================= --}}
    <div style="margin-bottom:20px;">
        <form method="GET" action="{{ route('admin.story-review.index') }}">
            <label><strong>Select Franchise:</strong></label>
            <select name="franchise_id" onchange="this.form.submit()" required>
                <option value="">-- Select Franchise --</option>
                @foreach($franchises as $franchise)
                <option value="{{ $franchise->id }}" {{ $selectedFranchise==$franchise->id ? 'selected' : '' }}>
                    {{ $franchise->location }} ({{ $franchise->owner_name }})
                </option>
                @endforeach
            </select>
        </form>
    </div>

    <hr>

    {{-- ================= STORIES SECTION ================= --}}
    <h3>Add Story</h3>

    @if($selectedFranchise)
    <form action="{{ route('admin.stories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Hidden Franchise ID --}}
        <input type="hidden" name="franchise_id" value="{{ $selectedFranchise }}">

        <input type="file" name="story_img" required>
        <button type="submit">Add Story</button>
    </form>
    @else
    <p style="color:red;">Please select a franchise first to manage stories.</p>
    @endif

    <br>

    <h4>Existing Stories</h4>

    @if($stories->count())
    @foreach($stories as $story)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <img src="{{ asset($story->story_img) }}" width="150">
    </div>
    @endforeach
    @else
    <p>No stories found.</p>
    @endif

    <hr>
    <hr>


    {{-- ================= REVIEWS SECTION ================= --}}
    <h3>Add Review</h3>

    @if($selectedFranchise)
    <form action="{{ route('admin.reviews.store') }}" method="POST">
        @csrf

        {{-- Hidden Franchise ID --}}
        <input type="hidden" name="franchise_id" value="{{ $selectedFranchise }}">

        <input type="text" name="cust_name" placeholder="Customer Name" required>
        <br><br>

        <textarea name="review_text" placeholder="Write review..." required></textarea>
        <br><br>

        <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required>
        <br><br>

        <button type="submit">Add Review</button>
    </form>
    @else
    <p style="color:red;">Please select a franchise first to manage reviews.</p>
    @endif

    <br>

    <h4>Existing Reviews</h4>

    @if($reviews->count())
    @foreach($reviews as $review)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <strong>{{ $review->cust_name }}</strong>
        <p>{{ $review->review_text }}</p>
        <p>Rating: ⭐ {{ $review->rating }}/5</p>
    </div>
    @endforeach
    @else
    <p>No reviews found.</p>
    @endif

</div>

@endsection