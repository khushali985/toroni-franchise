<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $franchises = Franchise::all();
        $selectedFranchise = null;

        if ($request->franchise_id) {
            $selectedFranchise = Franchise::find($request->franchise_id);
        }


        /*
        |--------------------------------------------------------------------------
        | 1️⃣ RESERVATION STATISTICS
        |--------------------------------------------------------------------------
        */

        $reservationBaseQuery = Reservation::query();

        if ($selectedFranchise) {
            $reservationBaseQuery->where('franchise_id', $selectedFranchise->id);
        }

        $totalReservations = $reservationBaseQuery->count();
        $pendingReservations = Reservation::when($selectedFranchise, function ($q) use ($selectedFranchise) {
            $q->where('franchise_id', $selectedFranchise->id);
        })->where('status', 'pending')->count();

        $approvedReservations = Reservation::when($selectedFranchise, function ($q) use ($selectedFranchise) {
            $q->where('franchise_id', $selectedFranchise->id);
        })->where('status', 'approved')->count();

        $cancelledReservations = Reservation::when($selectedFranchise, function ($q) use ($selectedFranchise) {
            $q->where('franchise_id', $selectedFranchise->id);
        })->where('status', 'cancelled')->count();

        $completedReservations = Reservation::when($selectedFranchise, function ($q) use ($selectedFranchise) {
            $q->where('franchise_id', $selectedFranchise->id);
        })->where('status', 'completed')->count();

       $todayReservations = Reservation::when($selectedFranchise, function ($q) use ($selectedFranchise) {
            $q->where('franchise_id', $selectedFranchise->id);
        })->whereDate('created_at', Carbon::today())->count();


        /*
        |--------------------------------------------------------------------------
        | 2️⃣ ORDER STATISTICS
        |--------------------------------------------------------------------------
        */

        $orderBaseQuery = Order::query();

        if ($selectedFranchise) {
            $orderBaseQuery->where('franchise_id', $selectedFranchise->id);
        }

        $totalOrders = $orderBaseQuery->count();
        $totalRevenue = $orderBaseQuery->sum('total');

        $pendingOrders = Order::when($selectedFranchise, function ($q) use ($selectedFranchise) {
            $q->where('franchise_id', $selectedFranchise->id);
        })->where('status', 'pending')->count();

        $completedOrders = Order::when($selectedFranchise, function ($q) use ($selectedFranchise) {
            $q->where('franchise_id', $selectedFranchise->id);
        })->where('status', 'completed')->count();

        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $preparingOrders = Order::where('status', 'preparing')->count();

        $totalRevenue = Order::sum('total');

        $todayRevenue = Order::whereDate('created_at', Carbon::today())
        ->sum('total');


        /*
        |--------------------------------------------------------------------------
        | 3️⃣ CUSTOMER STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalCustomers = User::count();
        $newCustomersToday = User::whereDate('created_at', Carbon::today())->count();


        /*
        |--------------------------------------------------------------------------
        | 4️⃣ MONTHLY REVENUE FOR CHART
        |--------------------------------------------------------------------------
        */

        $monthlyRevenue = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');


        /*
        |--------------------------------------------------------------------------
        | 5️⃣ MONTHLY RESERVATIONS FOR CHART
        |--------------------------------------------------------------------------
        */

        $monthlyReservations = Reservation::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

         /*
        |--------------------------------------------------------------------------
        | 6️⃣ RESERVATION MANAGEMENT (NEW)
        |--------------------------------------------------------------------------
        */
        $availableTables = RestaurantTable::where('status', 'available')->get();

        $reservationQuery = Reservation::query();

        if ($selectedFranchise) {
            $reservationQuery->where('franchise_id', $selectedFranchise->id);
        }

        // Filter by status
        if ($request->status) {
            $reservationQuery->where('status', $request->status);
        }

        // Search by name / phone / date
        if ($request->search) {
            $reservationQuery->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_no', 'like', '%' . $request->search . '%')
                  ->orWhere('date', 'like', '%' . $request->search . '%');
            });
        }

        $reservations = $reservationQuery->latest()->paginate(10);


        /*
        |--------------------------------------------------------------------------
        | 6️⃣ RECENT ACTIVITY
        |--------------------------------------------------------------------------
        */

        $recentReservations = Reservation::latest()->take(5)->get();
        $recentOrders = Order::latest()->take(5)->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN TO VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'totalReservations',
            'pendingReservations',
            'approvedReservations',
            'cancelledReservations',
            'completedReservations',
            'todayReservations',

            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'preparingOrders',
            'totalRevenue',
            'todayRevenue',

            'totalCustomers',
            'newCustomersToday',

            'monthlyRevenue',
            'monthlyReservations',

            'recentReservations',
            'recentOrders',

            'franchises',
            'reservations',

            'availableTables',
            'selectedFranchise'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE RESERVATION STATUS
    |--------------------------------------------------------------------------
    */

    public function updateReservationStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,cancelled,completed'
        ]);

        $reservation->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Reservation status updated.');
    }

     /*
    |--------------------------------------------------------------------------
    | DELETE RESERVATION
    |--------------------------------------------------------------------------
    */

    public function deleteReservation(Reservation $reservation)
    {
        $reservation->delete();

        return back()->with('success', 'Reservation deleted successfully.');
    }

    public function storeReservation(Request $request)
{
    $request->validate([
        'full_name' => 'required',
        'phone_no' => 'required',
        'date' => 'required|date',
        'time' => 'required',
        'no_of_people' => 'required|integer',
        'franchise_id' => 'required|exists:franchises,id',
        'table_id' => 'required|exists:restaurant_tables,id'
    ]);

    $table = RestaurantTable::find($request->table_id);

    if ($table->status != 'available') {
        return back()->with('error', 'Selected table is not available.');
    }

    $reservation = Reservation::create([
        'full_name' => $request->full_name,
        'phone_no' => $request->phone_no,
        'date' => $request->date,
        'time' => $request->time,
        'no_of_people' => $request->no_of_people,
        'franchise_id' => $request->franchise_id,
        'table_id' => $request->table_id,
        'status' => 'approved',
        'payment_status' => 'pending',
        'amount' => 0
    ]);

    // Mark table as occupied
    $table->update(['status' => 'occupied']);

    return back()->with('success', 'Reservation created successfully.');
}

}
