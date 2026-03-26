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
    public function index(Request $request)
{
    $franchise_id = $request->franchise;
    $category = $request->category;

    $query = MenuItem::with('franchise');

    if ($franchise_id) {
        $query->where('franchise_id', $franchise_id);
    }

    if ($category) {
        $query->whereRaw('LOWER(category) = ?', [strtolower($category)]);
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
        'franchise_id',
        'category'
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
            $image = $request->file('image');

            // generate unique name
            $imageName = time() . '_' . $image->getClientOriginalName();

            // move to public/images/menu
            $image->move(public_path('images/menu'), $imageName);

            // save path in DB
            $imagePath = 'images/menu/' . $imageName;
            
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
            $image = $request->file('image');

            // delete old image
            if ($menu->image && file_exists(public_path($menu->image))) {
                unlink(public_path($menu->image));
            }

            // new image name
            $imageName = time() . '_' . $image->getClientOriginalName();

            // move image
            $image->move(public_path('images/menu'), $imageName);

            // store path
            $data['image'] = 'images/menu/' . $imageName;
                 
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
            if ($menu->image && file_exists(public_path($menu->image))) {
                unlink(public_path($menu->image));
            }
        }

        $menu->delete();

        return back()->with('success', 'Menu item deleted successfully!');
    }

    public function renameCategory(Request $request)
    {
        $request->validate([
            'old_category' => 'required',
            'new_category' => 'required'
        ]);

        $old = strtolower(trim($request->old_category));
        $new = ucfirst(strtolower(trim($request->new_category)));

        MenuItem::whereRaw('LOWER(category) = ?', [$old])
            ->update(['category' => $new]);

        return back()->with('success', 'Category renamed successfully');
    }

    public function deleteCategory(Request $request)
    {
        $request->validate([
            'category' => 'required'
        ]);

        $category = strtolower(trim($request->category));

        $items = MenuItem::whereRaw('LOWER(category) = ?', [$category])->get();

        foreach ($items as $item) {

            if ($item->image) {
                if ($item->image && file_exists(public_path($item->image))) {
                    unlink(public_path($item->image));
                }
            }

            $item->delete();
        }

        return back()->with('success', 'Category deleted successfully');
    }
}