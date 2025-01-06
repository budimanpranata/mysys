<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlController extends Controller
{
    public function index()
    {
        return view('al.index');
    }
}
