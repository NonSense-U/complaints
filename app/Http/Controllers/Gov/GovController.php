<?php

namespace App\Http\Controllers\Gov;
use App\Http\Controllers\Controller;
use App\Models\Gov;

class GovController extends Controller
{
    public function index()
    {
        return Gov::pluck('name')->toArray();
    }
}
