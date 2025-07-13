<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the teacher's main dashboard.
     * This now serves as a simple landing page with links to key actions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // No complex data is needed here anymore.
        // We just return the view, which contains the link to the bulk grade entry feature.
        return view('teacher.dashboard');
    }
}