<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Franchise;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ReservationController extends Controller
{
    public function index()
    {
        $franchises = Franchise::orderBy('location')->get();
         $tables = RestaurantTable::orderBy('table_no')->get();

        return view('pages.reservation', compact('franchises', 'tables'));
    }

    public function store(Request $request)
    {
        // ✅ 1️⃣ Validate Input
        $validated = $request->validate([
            'full_name'     => 'required|string|max:150',
            'phone_no'      => 'required|digits:10',
            'date'          => 'required|date|after_or_equal:today',
            'time'          => 'required',
            'no_of_people'  => 'required|integer|min:1',
            'franchise_id'  => 'required|exists:franchises,id',
            'table_id'      => 'required|exists:restaurant_tables,id',
        ]);

        // ✅ 2️⃣ Make sure table belongs to selected franchise
        $table = RestaurantTable::where('id', $validated['table_id'])
            ->where('franchise_id', $validated['franchise_id'])
            ->first();

        if (!$table) {
            return back()->withErrors([
                'table_id' => 'Invalid table selection for this branch.'
            ])->withInput();
        }

        // ✅ 3️⃣ Check capacity
        if ($validated['no_of_people'] > $table->capacity_people) {
            return back()->withErrors([
                'no_of_people' => 'Selected table cannot accommodate this many people.'
            ])->withInput();
        }

        // ✅ 4️⃣ Check if table already booked
        $alreadyBooked = Reservation::where('franchise_id', $validated['franchise_id'])
            ->where('table_id', $validated['table_id'])
            ->where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->exists();

        if ($alreadyBooked) {
            return back()->withErrors([
                'table_id' => 'This table is already reserved for the selected date and time.'
            ])->withInput();
        }

        // ✅ 5️⃣ Prevent same phone booking at same time in same franchise
        $duplicate = Reservation::where('franchise_id', $validated['franchise_id'])
            ->where('phone_no', $validated['phone_no'])
            ->where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->exists();

        if ($duplicate) {
            return back()->withErrors([
                'phone_no' => 'You already have a reservation for this time in this branch.'
            ])->withInput();
        }

        // ✅ 6️⃣ Final Insert (Race-condition safe if UNIQUE constraint exists)
        try {
            Reservation::create($validated);
        } catch (QueryException $e) {
            return back()->withErrors([
                'table_id' => 'This table has just been booked by someone else. Please choose another.'
            ])->withInput();
        }

        return redirect()->back()->with('success', 'Reservation confirmed successfully!');
    }
}
