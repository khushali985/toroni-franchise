<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
     public function index()
    {
        $menuItems = MenuItem::orderBy('category')->get();
        return view('pages.order', compact('menuItems'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:150',
            'phone'     => 'nullable|digits:10',
            'address'   => 'nullable|string|max:255',
            'items'     => 'required|array',
            'total'     => 'required|numeric'
        ]);

        Order::create([
            'franchise_id' => $request->franchise_id,
            'full_name'    => $request->full_name,
            'phone'        => $request->phone,
            'address'      => $request->address,
            'items'        => json_encode($request->items),
            'email'        => $request->email,
            'total'        => $request->total
        ]);

        return redirect()->back()->with('success', 'Order placed successfully!');
    }
}
