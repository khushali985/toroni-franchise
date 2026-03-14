<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Franchise;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentSetting;

class ReservationController extends Controller
{
    // ✅ Load Reservation Page
    public function index()
    {
        $franchises = Franchise::orderBy('location')->get();

        // Default QR → first franchise
        $defaultFranchiseId = $franchises->first()->id ?? null;

        $payment = null;

        if ($defaultFranchiseId) {
            $payment = PaymentSetting::where('franchise_id', $defaultFranchiseId)->first();
        }

        return view('pages.reservation', compact('franchises', 'payment'));
    }

    // ✅ Get Tables by Franchise (Optional)
    public function getTables($franchiseId)
    {
        return RestaurantTable::where('franchise_id', $franchiseId)
            ->orderBy('table_no')
            ->get();
    }

    // ✅ AJAX Availability Check (Count-Based Logic)
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'date'         => 'required|date|after_or_equal:today',
            'time'         => 'required',
            'no_of_people' => 'required|integer|min:1',
        ]);


        try {


            $time = \Carbon\Carbon::parse($validated['time'])->format('H:i:s');


            // 1️⃣ Get all suitable tables for that guest count
            $tables = RestaurantTable::where('franchise_id', $validated['franchise_id'])
                ->whereBetween('capacity_people', [
                $validated['no_of_people'],
                $validated['no_of_people'] + 2
            ])
                ->where('status', 'available')
                ->pluck('id');

            $totalTables = $tables->count();

            if ($totalTables === 0) {
                return response()->json([
                    'success'   => true,
                    'available' => false
                ]);
            }

            // 2️⃣ Count already booked tables for that slot
            $bookedCount = Reservation::whereIn('table_id', $tables)
                ->whereDate('date', $validated['date'])
                ->whereTime('time', $time)
                ->whereIn('status', ['approved', 'pending'])
                ->count();

            // 3️⃣ Allow if at least one table is free
            $available = $bookedCount < $totalTables;

            return response()->json([
                'success'   => true,
                'available' => $available
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Store Reservation (Allocation Logic)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone_no'       => 'required|digits:10',
            'date'           => 'required|date|after_or_equal:today',
            'time'           => 'required',
            'no_of_people'   => 'required|integer|min:1',
            'franchise_id'   => 'required|exists:franchises,id',
            'transaction_id' => 'required|string|max:255',
            'payment_proof'  => 'required|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Normalize time format
        $validated['time'] = date('H:i:s', strtotime($validated['time']));

        // 🔒 Prevent duplicate booking (same phone + same slot)
        $duplicate = Reservation::where('franchise_id', $validated['franchise_id'])
            ->where('phone_no', $validated['phone_no'])
            ->whereDate('date', $validated['date'])
            ->whereTime('time', $validated['time'])
            ->whereIn('status', ['approved', 'pending'])
            ->exists();

        if ($duplicate) {
            return back()->withErrors([
                'phone_no' => 'You already have a reservation for this time in this branch.'
            ])->withInput();
        }

        DB::beginTransaction();

        try {

            // 🔥 Allocate first suitable free table
            $availableTable = RestaurantTable::where('franchise_id', $validated['franchise_id'])
                ->whereBetween('capacity_people', [
                    $validated['no_of_people'],
                    $validated['no_of_people'] + 2
            ])
                ->where('status', 'available')
                ->whereDoesntHave('reservations', function ($query) use ($validated) {
                    $query->whereDate('date', $validated['date'])
                          ->whereTime('time', $validated['time'])
                          ->whereIn('status', ['approved', 'pending']);
                })
                ->orderBy('capacity_people') // smallest suitable first
                ->lockForUpdate()
                ->first();

            if (!$availableTable) {
                DB::rollBack();
                return back()->withErrors([
                    'date' => 'All suitable tables are booked for this slot.'
                ])->withInput();
            }

            $validated['table_id'] = $availableTable->id;

            // 📁 Upload payment proof
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/payments/transaction'), $filename);

            $validated['payment_proof'] = 'images/payments/transaction' . $filename;
            $validated['status'] = 'pending';
            $validated['payment_status'] = 'pending';

            Reservation::create($validated);

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage()
            ])->withInput();
        }

        return redirect()->back()->with(
            'success',
            'Your reservation request has been submitted successfully. Our team will confirm it shortly.'
        );


    }

    public function getPayment($franchise_id)
    {
        $payment = PaymentSetting::where('franchise_id', $franchise_id)->first();

        if (!$payment) {
            return response()->json([
                'success' => false
            ]);
        }

        return response()->json([
            'success' => true,
            'qr_image' => asset($payment->qr_image),
            'upi_name' => $payment->upi_name
        ]);
    }



    /*public function showReservation($franchise_id)
    {
        $payment = PaymentSetting::where('franchise_id', $franchise_id)->first();

        return view('reservation.page', compact('payment'));
    } */

}