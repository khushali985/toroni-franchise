<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FranchisePartner;
use App\Models\Franchise;
use Illuminate\Http\Request;

class AdminFranchiseController extends Controller
{
    // ===============================
    // SHOW ALL FRANCHISES
    // ===============================
    public function index()
    {
        $franchises = Franchise::latest()->get();
        $franchisePartners = FranchisePartner::latest()->get();
        return view('admin.franchise.index', compact('franchises','franchisePartners'));
    }

    // ===============================
    // STORE NEW FRANCHISE
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'owner_name'  => 'required',
            'owner_phone' => 'required',
            'owner_email' => 'required|email',
            'location'    => 'required',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('images/franchise'), $imageName);
            $data['image'] = 'images/franchise/'.$imageName;
        }

        Franchise::create($data);

        return redirect()->route('admin.franchise.index')
            ->with('success', 'Franchise Added Successfully');
    }

    // ===============================
    // SHOW EDIT PAGE
    // ===============================
    public function edit($id)
    {
        $franchise = Franchise::findOrFail($id);
        return view('admin.franchise.edit', compact('franchise'));
    }

    // ===============================
    // UPDATE FRANCHISE
    // ===============================
    public function update(Request $request, $id)
    {
        $franchise = Franchise::findOrFail($id);

        $request->validate([
            'owner_name'  => 'required',
            'owner_phone' => 'required',
            'owner_email' => 'required|email',
            'location'    => 'required',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {

            // Delete old image
            if ($franchise->image && file_exists(public_path($franchise->image))) {
                unlink(public_path($franchise->image));
            }

            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('images/franchise'), $imageName);
            $data['image'] = 'images/franchise/'.$imageName;
        }

        $franchise->update($data);

        return redirect()->route('admin.franchise.index')
            ->with('success', 'Franchise Updated Successfully');
    }

    // ===============================
    // DELETE FRANCHISE
    // ===============================
    public function destroy($id)
    {
        $franchise = Franchise::findOrFail($id);

        if ($franchise->image && file_exists(public_path($franchise->image))) {
            unlink(public_path($franchise->image));
        }

        $franchise->delete();

        return redirect()->route('admin.franchise.index')
            ->with('success', 'Franchise Deleted Successfully');
    }

    public function deletePartner($id)
    {
        $partner = FranchisePartner::findOrFail($id);

        $partner->delete();

        return redirect()->route('admin.franchise.index')
            ->with('success', 'Franchise Query Deleted Successfully');
    }

    public function bulk(Request $request)
    {
        if (!$request->has('selected_partners')) {
            return back()->with('error', 'No queries selected.');
        }

        $partners = FranchisePartner::whereIn('id', $request->selected_partners);

        switch ($request->action) {

            case 'delete':
                $partners->delete();
                return back()->with('success', 'Selected franchise queries deleted.');

            default:
                return back()->with('error', 'Invalid action.');
        }
    }
}

