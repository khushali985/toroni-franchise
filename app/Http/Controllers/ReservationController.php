<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'full_name'     => 'required|string|max:150',
            'phone_no'      => 'required|string|max:15',
            'date'          => 'required|date',
            'time'          => 'required',
            'no_of_people'  => 'required|integer',
            'franchise_id'  => 'required|integer',
            'table_id'      => 'required|integer'
        ]);

        Reservation::create($request->all());

        return redirect()->back()->with('success', 'Reservation confirmed!');
    }
}
