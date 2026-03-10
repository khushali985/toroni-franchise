<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\Franchise;
use Illuminate\Http\Request;

class AdminTeamController extends Controller
{

    public function index(Request $request)
    {
        $franchise_id = $request->franchise_id;

        $franchises = Franchise::all();

        $team = TeamMember::when($franchise_id, function ($query) use ($franchise_id) {
            $query->where('franchise_id', $franchise_id);
        })->latest()->get();

        return view('admin.team.index', compact('team','franchises','franchise_id'));
    }


    public function store(Request $request)
{
    $request->validate([
        'franchise_id' => 'required|exists:franchises,id',
        'name' => 'required|string|max:255',
        'role' => 'required|in:Manager,Chef,Waiter,Receptionist,Staff',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    $imageName = null;

    if($request->hasFile('image')){
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images/team'), $imageName);
    }

    TeamMember::create([
        'franchise_id' => $request->franchise_id,
        'name' => $request->name,
        'role' => $request->role,
        'description' => $request->description,
        'image' => $imageName
    ]);

    return redirect()->back()->with('success','Team Member Added');
}


    public function update(Request $request,$id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'role' => 'required|in:Manager,Chef,Waiter,Receptionist,Staff',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    $team = TeamMember::findOrFail($id);

    if($request->hasFile('image')){
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images/team'),$imageName);
        $team->image = $imageName;
    }

    $team->update([
        'name'=>$request->name,
        'role'=>$request->role,
        'description'=>$request->description
    ]);

    return redirect()->back()->with('success','Team Member Updated');
}


    public function delete($id)
    {
        TeamMember::findOrFail($id)->delete();

        return redirect()->back()->with('success','Team Member Deleted');
    }

}