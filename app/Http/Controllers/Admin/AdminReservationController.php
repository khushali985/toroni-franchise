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
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ReservationsExport;

class AdminReservationController extends Controller
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

        $totalReservations = (clone $reservationBaseQuery)->count();
        $pendingReservations = (clone $reservationBaseQuery)->where('status', 'pending')->count();
        $approvedReservations = (clone $reservationBaseQuery)->where('status', 'approved')->count();
        $cancelledReservations = (clone $reservationBaseQuery)->where('status', 'cancelled')->count();
        $completedReservations = (clone $reservationBaseQuery)->where('status', 'completed')->count();

        $todayReservations = (clone $reservationBaseQuery)
            ->whereDate('created_at', Carbon::today())
            ->count();
        
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

        $cancelledOrders = (clone $orderBaseQuery)->where('status', 'cancelled')->count();
        $preparingOrders = (clone $orderBaseQuery)->where('status', 'preparing')->count();
        
        /*
        |--------------------------------------------------------------------------
        | TOTAL REVENUE (Approved Reservations + Completed Orders)
        |--------------------------------------------------------------------------
        */

        $totalReservationRevenue = Reservation::when($selectedFranchise, function ($q) use ($selectedFranchise) {
                $q->where('franchise_id', $selectedFranchise->id);
            })
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        $totalOrderRevenue = Order::when($selectedFranchise, function ($q) use ($selectedFranchise) {
                $q->where('franchise_id', $selectedFranchise->id);
            })
            ->where('status', 'completed')
            ->sum('total');

        $totalRevenue = $totalReservationRevenue + $totalOrderRevenue;

        $todayReservationRevenue = Reservation::when($selectedFranchise, function ($q) use ($selectedFranchise) {
                $q->where('franchise_id', $selectedFranchise->id);
            })
            ->where('status', 'approved')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        $todayOrderRevenue = Order::when($selectedFranchise, function ($q) use ($selectedFranchise) {
                $q->where('franchise_id', $selectedFranchise->id);
            })
            ->where('status', 'completed')
            ->whereDate('created_at', Carbon::today())
            ->sum('total');

        $todayRevenue = $todayReservationRevenue + $todayOrderRevenue;


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
        | 6️⃣ RESERVATION MANAGEMENT
        |--------------------------------------------------------------------------
        */
        $availableTables = RestaurantTable::where('status', 'available')->get();

        $reservationQuery = Reservation::query();

        // Franchise filter
        $reservationQuery->when($selectedFranchise, function ($q) use ($selectedFranchise) {
            $q->where('franchise_id', $selectedFranchise->id);
        });

        // Status filter
        $reservationQuery->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        // Search filter
        $reservationQuery->when($request->filled('search'), function ($q) use ($request) {

            $search = $request->search;

            $q->where(function ($subQuery) use ($search) {
                $subQuery->where('full_name', 'LIKE', "%{$search}%")
                        ->orWhere('phone_no', 'LIKE', "%{$search}%")
                        ->orWhere('date', 'LIKE', "%{$search}%");
            });
        });

        $perPage = $request->get('per_page', 100);

        $reservations = $reservationQuery
                            ->latest()
                            ->paginate($perPage)
                            ->withQueryString(); // IMPORTANT

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

        return view('admin.reservations.index', compact(
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

        // ❌ Prevent modifying finalized reservations
        if (in_array($reservation->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'This reservation is already finalized.');
        }

        // ❌ Prevent same status update
        if ($reservation->status === $request->status) {
            return back()->with('info', 'Reservation is already in this status.');
        }

        /**
         * ==========================================
         * ✅ APPROVAL LOGIC (REAL WORLD SYSTEM)
         * ==========================================
         */
        if ($request->status === 'approved') {

            $startTime = Carbon::parse($reservation->time);
            $endTime   = $startTime->copy()->addHours(2); // 2-hour slot

            // Get tables with suitable capacity
            $tables = RestaurantTable::where('franchise_id', $reservation->franchise_id)
                ->where('capacity_people', '>=', $reservation->no_of_people)
                ->orderBy('capacity_people', 'asc') // smallest suitable table first
                ->get();

            $assignedTable = null;

            foreach ($tables as $table) {

                $conflict = Reservation::where('table_id', $table->id)
                    ->where('date', $reservation->date)
                    ->where('status', 'approved')
                    ->get()
                    ->first(function ($existing) use ($startTime, $endTime) {

                        $existingStart = Carbon::parse($existing->time);
                        $existingEnd   = $existingStart->copy()->addHours(2);

                        return $startTime < $existingEnd && $endTime > $existingStart;
                    });

                if (!$conflict) {
                    $assignedTable = $table;
                    break;
                }
            }

            if (!$assignedTable) {
                return back()->with('error', 'No table available for this time slot.');
            }

            $reservation->update([
                'status'   => 'approved',
                'table_id' => $assignedTable->id
            ]);

            return back()->with('success', 'Reservation approved and table assigned.');
        }

        /**
         * ==========================================
         * ❌ CANCEL / COMPLETE
         * ==========================================
         */
        if (in_array($request->status, ['cancelled', 'completed'])) {

            $reservation->update([
                'status' => $request->status
            ]);

            return back()->with('success', 'Reservation updated successfully.');
        }

        return back();
    }
    public function togglePayment(Request $request, Reservation $reservation)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,approved'
        ]);

        // 🚫 If already approved, do not allow reverting
        if ($reservation->payment_status === 'approved') {
            return back()->with('error', 'Payment already approved and cannot be changed.');
        }
        $reservation->update([
            'payment_status' => $request->payment_status
        ]);

        return back()->with('success', 'Payment status updated.');
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
            'table_id' => 'required|exists:restaurant_tables,id',
            'transaction_id' => 'nullable|string',
            'amount' => 'required|numeric|min:0'
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
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'amount' => $request->amount,
            'transaction_id' => $request->transaction_id,

        ]);

        // Mark table as occupied
        $table->update(['status' => 'occupied']);

        return back()->with('success', 'Reservation created successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'reservation_ids' => 'required',
            'status' => 'required|in:approved,completed,cancelled,delete,export_pdf,export_excel'
        ]);

        $ids = explode(',', $request->reservation_ids);

        $reservations = Reservation::whereIn('id', $ids)->get();

        // ==========================
        // EXPORT PDF
        // ==========================
        if ($request->status === 'export_pdf') {

            $pdf = Pdf::loadView('admin.reservations.export_pdf', compact('reservations'));

            return $pdf->download('reservations.pdf');
        }

        // ==========================
        // EXPORT EXCEL
        // ==========================
        if ($request->status === 'export_excel') {

            return Excel::download(new ReservationsExport($ids), 'reservations.xlsx');
        }
        
        foreach ($reservations as $reservation) {

            // If DELETE selected
            if ($request->status === 'delete') {
                $this->deleteReservation($reservation);
                continue;
            }

            // For approved / completed / cancelled
            $fakeRequest = new Request([
                'status' => $request->status
            ]);

            $this->updateReservationStatus($fakeRequest, $reservation);
        }

        return back()->with('success', 'Bulk action applied successfully.');
    }

}
