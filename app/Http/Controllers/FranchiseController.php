<?php

namespace App\Http\Controllers;

use App\Models\Franchise;

class FranchiseController extends Controller
{
    public function index()
    {
        $franchises = Franchise::all();

        return view('pages.franchise', compact('franchises'));
    }
}
