<?php

namespace App\Http\Controllers;
use App\Models\Franchise;
use App\Models\Order;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
     public function index()
    {
        $menuItems = MenuItem::orderBy('category')->get();
        $categories = MenuItem::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $franchises = Franchise::all();

        return view('pages.order', compact( 'franchises', 'menuItems', 'categories'));
    }
     function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email',
            'phone'     => 'nullable|digits:10',
            'address'   => 'nullable|string|max:255',
            'franchise_id' => 'required|exists:franchises,id',
            'items'     => 'required|string',
            'total'     => 'required|numeric'
        ]);

        Order::create([
            'franchise_id' => $request->franchise_id,
            'full_name'    => $request->full_name,
            'phone'        => $request->phone,
            'address'      => $request->address,
            'items'        => $request->items,
            'email'        => $request->email,
            'total'        => $request->total
        ]);

        return redirect()->back()->with('success', 'Order placed successfully!'); 

    }
}

