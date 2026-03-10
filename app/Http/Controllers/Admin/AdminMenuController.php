<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminMenuController extends Controller
{
    /*public function index()
    {
        // $items = MenuItem::with('franchise')->latest()->get();
        $items = MenuItem::with('franchise')
            ->orderBy('category')
            ->orderBy('dish_name')
            ->get()
            ->groupBy(function ($item) {
        return strtolower($item->category);
    });
        $franchises = Franchise::all();
        $categories = MenuItem::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->pluck('category');

        return view('admin.menu.index', compact('items', 'franchises','categories'));
    }*/
       
    public function index(Request $request)
{
    $franchise_id = $request->franchise;

    $query = MenuItem::with('franchise');

    if ($franchise_id) {
        $query->where('franchise_id', $franchise_id);
    }

    $items = $query->orderBy('category')
        ->orderBy('dish_name')
        ->get()
        ->groupBy(function ($item) {
            return strtolower($item->category);
        });

    $franchises = Franchise::all();

    $categories = MenuItem::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->pluck('category');

    return view('admin.menu.index', compact(
        'items',
        'franchises',
        'categories',
        'franchise_id'
    ));
}

    public function store(Request $request)
    {
        $request->validate([
            'franchise_id' => 'required',
            'dish_name' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image',
            'ingredients' => 'required'
        ]);
        $category = $request->category;

            if ($request->category === 'new') {
                $category = $request->new_category;
            }
            $category = trim($category);
            $category = strtolower($category);
            $category = Str::singular($category);
            $category = ucfirst($category);
        $imagePath = $request->file('image')
                             ->store('menu_images', 'public');

        MenuItem::create([
            'franchise_id' => $request->franchise_id,
            'dish_name' => $request->dish_name,
            'ingredients' => $request->ingredients,
            'price' => $request->price,
            'category' => $category,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Menu item added successfully!');
    }

    public function update(Request $request, MenuItem $menu)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'dish_name' => 'required',
            'price' => 'required|numeric'
        ]);

        $data = $request->only([
            'franchise_id',
            'dish_name',
            'ingredients',
            'price',
            'category'
        ]);

        if ($request->hasFile('image')) {

            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }

            $data['image'] = $request->file('image')
                                    ->store('menu_images', 'public');
        }
        if ($request->category === 'new') {
            $data['category'] = $request->new_category;
        } else {
            $data['category'] = $request->category;
        }
        $data['category'] = trim($data['category']);
        $data['category'] = strtolower($data['category']);
        $data['category'] = Str::singular($data['category']);
        $data['category'] = ucfirst($data['category']);
        $menu->update($data);

        return back()->with('success', 'Menu item updated successfully!');
    }

    public function destroy(MenuItem $menu)
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return back()->with('success', 'Menu item deleted successfully!');
    }
}