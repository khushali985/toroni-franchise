<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::orderBy('category')->get();

        return view('pages.menu', compact('menuItems'));
    }
}
