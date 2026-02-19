<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Franchise;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ReservationController extends Controller
{
    // ✅ Load Reservation Page
    public function index()
    {
        $franchises = Franchise::orderBy('location')->get();
        return view('pages.reservation', compact('franchises'));
    }

    // ✅ Get Tables by Franchise (AJAX)
    public function getTables($franchiseId)
    {
        return RestaurantTable::where('franchise_id', $franchiseId)
            ->orderBy('table_no')
            ->get();
    }

    // Check table availability (AJAX)
    public function checkAvailability(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'franchise_id' => 'required|exists:franchises,id',
            'date'         => 'required|date',
            'time'         => 'required',
            'no_of_people' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $availableTable = RestaurantTable::where('franchise_id', $request->franchise_id)
            ->where('capacity_people', '>=', $request->no_of_people)
            ->where('status', 'available')
            ->whereDoesntHave('reservations', function ($query) use ($request) {
                $query->where('date', $request->date)
                    ->where('time', $request->time)
                    ->whereIn('status', ['approved', 'pending']);
            })
            ->orderBy('capacity_people')
            ->first();

        return response()->json([
            'available' => $availableTable ? true : false,
        ]);
    }

    // ✅ Store Reservation
    public function store(Request $request)
    {
        // 1️⃣ Validate
        $validated = $request->validate([
            'full_name'     => 'required|string|max:255',
            'phone_no'      => 'required|digits:10',
            'date'          => 'required|date|after_or_equal:today',
            'time'          => 'required',
            'no_of_people'  => 'required|integer|min:1',
            'franchise_id'  => 'required|exists:franchises,id',
            'transaction_id'=> 'required|string|max:255',
            'payment_proof' => 'required|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // 2️⃣ Normalize Time
        $validated['time'] = date('H:i:s', strtotime($validated['time']));

        // 3️⃣ Prevent Same Phone Double Booking FIRST
        $duplicate = Reservation::where('franchise_id', $validated['franchise_id'])
            ->where('phone_no', $validated['phone_no'])
            ->where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->whereIn('status', ['approved','pending'])
            ->exists();

        if ($duplicate) {
            return back()->withErrors([
                'phone_no' => 'You already have a reservation for this time in this branch.'
            ])->withInput();
        }

        // 4️⃣ Find Available Table Automatically
        $availableTable = RestaurantTable::where('franchise_id', $validated['franchise_id'])
            ->where('capacity_people', '>=', $validated['no_of_people'])
            ->where('status', 'available')
            ->whereDoesntHave('reservations', function ($query) use ($validated) {
                $query->where('date', $validated['date'])
                    ->where('time', $validated['time'])
                    ->whereIn('status', ['approved','pending']);
            })
            ->orderBy('capacity_people')
            ->first();

        if (!$availableTable) {
            return back()->withErrors([
                'date' => 'No available tables for selected date and time.'
            ])->withInput();
        }

        $validated['table_id'] = $availableTable->id;

        // 5️⃣ Handle Everything Inside Transaction
        DB::beginTransaction();

        try {

            // Upload Payment Proof
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/payments'), $filename);

            $validated['payment_proof'] = 'images/payments/' . $filename;

            // Set Default Status
            $validated['status'] = 'pending';
            $validated['payment_status'] = 'pending';

            Reservation::create($validated);

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->withErrors([
                'error' => 'Something went wrong. Please try again.'
            ])->withInput();
        }

        return redirect()->back()->with('success', 'Your reservation request has been submitted successfully. Our team will confirm it shortly.'
        );
    }

}
