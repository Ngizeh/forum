<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\Factory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the view in Admin dashboard.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('admin.dashboard.index');
    }
}
