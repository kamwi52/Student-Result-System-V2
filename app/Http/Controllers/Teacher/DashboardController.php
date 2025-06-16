<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
       
        $teacher = Auth::user();

    
        $classes = $teacher->taughtClasses()->with('subject')->get();

        return view('teacher.dashboard', compact('classes'));
    }
}