@extends('layouts.admin')

@section('content')

<h2>Table Layout</h2>

<div class="layout-grid">
    @foreach($tables as $table)
    <div class="seat {{ $table->status == 'available' ? 'available' : 'occupied' }}">
        {{ $table->table_no }}
    </div>
    @endforeach
</div>

@endsection