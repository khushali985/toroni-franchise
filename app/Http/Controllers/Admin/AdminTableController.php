<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Models\Reservation;
use App\Models\Franchise;
use Illuminate\Http\Request;

class AdminTableController extends Controller
{
    /**
     * Display table management page
    */
    public function index(Request $request)
    {
        $franchises = Franchise::all();
        $selectedFranchise = null;
        $query = RestaurantTable::with('franchise');

        // ✅ Apply Franchise First (Required for other filters)
        if ($request->filled('franchise_id')) {
            $selectedFranchise = Franchise::find($request->franchise_id);

            if ($selectedFranchise) {
                $query->where('franchise_id', $selectedFranchise->id);
            }
        }

        // ✅ Status Filter (within selected franchise automatically)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ Search Filter (within selected franchise automatically)
        if ($request->filled('search')) {
            $query->where('table_no', 'LIKE', "%{$request->search}%");
        }

        $tables = $query->latest()->paginate(10)->withQueryString();
        /*
        |--------------------------------------------------------------------------
        | 🔹 STATS BASE QUERY (IMPORTANT FIX)
        |--------------------------------------------------------------------------
        */

        $statsQuery = RestaurantTable::query();

        if ($selectedFranchise) {
            $statsQuery->where('franchise_id', $selectedFranchise->id);
        }

        $totalTables = $statsQuery->count();

        $availableTables = (clone $statsQuery)
            ->where('status', 'available')
            ->count();

        $notAvailableTables = (clone $statsQuery)
            ->where('status', 'not available')
            ->count();

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