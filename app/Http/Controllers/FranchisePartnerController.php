<?php

namespace App\Http\Controllers;

use App\Models\FranchisePartner;
use Illuminate\Http\Request;

class FranchisePartnerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:15',
            'city'  => 'required|string|max:150'
        ]);

        FranchisePartner::create($request->all());

        return redirect()->back()->with('success', 'We will contact you soon!');
    }
}
