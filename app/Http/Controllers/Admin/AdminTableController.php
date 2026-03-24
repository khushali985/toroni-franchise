<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Models\Reservation;
use App\Models\Franchise;
use Illuminate\Http\Request;

class AdminTableController extends Controller
{
    public function index(Request $request)
{
    $franchises = Franchise::all();
    $selectedFranchise = null;

    $date = $request->date;
    $time = $request->time;
    $status = $request->status;

    $query = RestaurantTable::with('franchise');

    // ✅ Franchise filter
    if ($request->filled('franchise_id')) {
        $selectedFranchise = Franchise::find($request->franchise_id);

        if ($selectedFranchise) {
            $query->where('franchise_id', $selectedFranchise->id);
        }
    }

    // ✅ Search filter
    if ($request->filled('search')) {
        $query->where('table_no', 'LIKE', "%{$request->search}%");
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 DYNAMIC AVAILABILITY LOGIC (MAIN PART)
    |--------------------------------------------------------------------------
    */

    if ($date && $time) {

        // Add booking count
        $query->withCount(['reservations as is_booked' => function ($q) use ($date, $time) {
            $q->whereDate('date', $date)
              ->whereTime('time', $time)
              ->whereIn('status', ['approved', 'pending']);
        }]);

        // Apply dynamic status filter
        if ($status === 'available') {
            $query->having('is_booked', 0);
        }

        if ($status === 'not available') {
            $query->having('is_booked', '>', 0);
        }

    } else {

        // fallback static status
        if ($status) {
            $query->where('status', $status);
        }
    }

    $tables = $query->latest()->paginate(10)->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | 🔥 STATS (UPDATED FOR DATE + TIME)
    |--------------------------------------------------------------------------
    */

    $statsQuery = RestaurantTable::query();

    if ($selectedFranchise) {
        $statsQuery->where('franchise_id', $selectedFranchise->id);
    }

    $totalTables = $statsQuery->count();

    if ($date && $time) {

        $bookedTableIds = Reservation::whereDate('date', $date)
            ->whereTime('time', $time)
            ->whereIn('status', ['approved', 'pending'])
            ->pluck('table_id')
            ->unique();

        $notAvailableTables = $bookedTableIds->count();

        $availableTables = $totalTables - $notAvailableTables;

    } else {

        $availableTables = (clone $statsQuery)
            ->where('status', 'available')
            ->count();

        $notAvailableTables = (clone $statsQuery)
            ->where('status', 'not available')
            ->count();
    }

    return view('admin.tables.index', compact(
        'tables',
        'franchises',
        'selectedFranchise',
        'totalTables',
        'availableTables',
        'notAvailableTables'
    ));
}
    /**
     * Store new table
     */
    public function store(Request $request)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'table_no' => 'required|integer|min:1',
            'capacity_people' => 'required|integer|min:1',
        ]);

        // 🚫 Prevent duplicate table number in same franchise
        $exists = RestaurantTable::where('franchise_id', $request->franchise_id)
                    ->where('table_no', $request->table_no)
                    ->exists();

        if ($exists) {
            return back()->with('error', 'Table number already exists for this franchise.');
        }

        RestaurantTable::create([
            'franchise_id' => $request->franchise_id,
            'table_no' => $request->table_no,
            'capacity_people' => $request->capacity_people,
            'status' => 'available'
        ]);

        return back()->with('success', 'Table added successfully.');
    }

    /**
     * Update capacity
     */
    public function update(Request $request, RestaurantTable $table)
    {
        $request->validate([
            'capacity_people' => 'required|integer|min:1'
        ]);

        $table->update([
            'capacity_people' => $request->capacity_people
        ]);

        return back()->with('success', 'Capacity updated.');
    }

    /**
     * Toggle status
     */
    public function toggleSlot(Request $request, RestaurantTable $table)
{
    $request->validate([
        'date' => 'required|date',
        'time' => 'required'
    ]);

    $date = $request->date;
    $time = $request->time;

    // 🔍 Check if already booked
    $existing = Reservation::where('table_id', $table->id)
        ->whereDate('date', $date)
        ->whereTime('time', $time)
        ->whereIn('status', ['approved', 'pending'])
        ->first();

    if ($existing) {
        // 🔥 RELEASE TABLE (DELETE BOOKING)
        $existing->delete();

        return back()->with('success', 'Table released for this slot.');
    } else {
        // 🔥 BLOCK TABLE (CREATE FAKE RESERVATION)
        Reservation::create([
            'franchise_id' => $table->franchise_id,
            'table_id' => $table->id,
            'date' => $date,
            'time' => $time,
            'no_of_people' => $table->capacity_people,
            'full_name' => 'Admin Block',
            'phone_no' => '0000000000',
            'status' => 'approved',
            'payment_status' => 'approved'
        ]);

        return back()->with('success', 'Table blocked for this slot.');
    }
}


    public function status(RestaurantTable $table)
    {
        $newStatus = $table->status === 'available'
                        ? 'not available'
                        : 'available';

        $table->update([
            'status' => $newStatus
        ]);

        return back()->with('success', 'Status updated.');
    }

    /**
     * Assign table to reservation
     */
    public function assign(Request $request, RestaurantTable $table)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id'
        ]);

        if ($table->status !== 'available') {
            return back()->with('error', 'Table is not available.');
        }

        $reservation = Reservation::findOrFail($request->reservation_id);

        $reservation->update([
            'table_id' => $table->id
        ]);

        $table->update([
            'status' => 'not available'
        ]);

        return back()->with('success', 'Table assigned successfully.');
    }

    /**
     * Bulk actions
     */
    public function bulk(Request $request)
    {
        if (!$request->has('selected_tables')) {
            return back()->with('error', 'No tables selected.');
        }

        $tables = RestaurantTable::whereIn('id', $request->selected_tables);

        switch ($request->action) {

            case 'delete':
                $tables->delete();
                return back()->with('success', 'Tables deleted.');

            case 'available':
                $tables->update(['status' => 'available']);
                return back()->with('success', 'Tables marked available.');

            case 'not available':
                $tables->update(['status' => 'not available']);
                return back()->with('success', 'Tables marked not available.');

            default:
                return back()->with('error', 'Invalid action.');
        }
    }

    /**
     * Delete single table
     */
    public function destroy(RestaurantTable $table)
    {
        $table->delete();

        return back()->with('success', 'Table deleted successfully.');
    }
}