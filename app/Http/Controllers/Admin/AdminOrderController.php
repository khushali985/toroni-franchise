<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Franchise;

class AdminOrderController extends Controller
{
    public function index()
    {
        $franchises = Franchise::all();
        return view('admin.orders.index', compact('franchises'));
    }

    // AJAX fetch
    public function fetch(Request $request)
    {
        // Load franchise relationship also
        $query = Order::with('franchise');

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                ->orWhere('id', $request->search);
            });
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        // 🔥 ADD THIS (Franchise Filter)
        if ($request->franchise) {
            $query->where('franchise_id', $request->franchise);
        }

        return response()->json($query->latest()->get());
    }

    /*
    |--------------------------------------------------------------------------
    | SINGLE STATUS UPDATE
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request, Order $order)
    {
        try {

            $currentStatus = strtolower($order->status);
            $newStatus     = strtolower($request->status);

            if (!$this->canChangeStatus($currentStatus, $newStatus)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status transition'
                ], 400);
            }

            $order->update([
                'status' => $newStatus
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BULK STATUS UPDATE (Uses Same Logic)
    |--------------------------------------------------------------------------
    */
    public function bulkUpdate(Request $request)
    {
        try {

            $newStatus = strtolower($request->status);

            $orders = Order::whereIn('id', $request->ids)->get();

            foreach ($orders as $order) {

                $currentStatus = strtolower($order->status);

                if (!$this->canChangeStatus($currentStatus, $newStatus)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid transition for Order ID {$order->id}"
                    ], 400);
                }
            }

            // If all transitions valid → update all
            Order::whereIn('id', $request->ids)
                ->update(['status' => $newStatus]);

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CENTRALIZED STATUS LOGIC (Single Source of Truth)
    |--------------------------------------------------------------------------
    */
    private function canChangeStatus($currentStatus, $newStatus)
    {
        $allowedTransitions = [
            'pending'   => ['preparing', 'cancelled'],
            'preparing' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => []
        ];

        return in_array($newStatus, $allowedTransitions[$currentStatus] ?? []);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT CSV
    |--------------------------------------------------------------------------
    */
    public function export()
    {
        $orders = Order::all();

        $filename = "orders.csv";
        $handle = fopen($filename, 'w+');

        fputcsv($handle, ['ID', 'Customer', 'Total', 'Status', 'Date']);

        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->id,
                $order->full_name,
                $order->total,
                $order->status,
                $order->created_at
            ]);
        }

        fclose($handle);

        return response()->download($filename);
    }
}