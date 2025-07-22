<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassSection;

class ReportingController extends Controller
{
    /**
     * Display the main reporting dashboard.
     */
    public function index()
    {
        // Fetch all classes to be used in the dropdown selector.
        $classes = ClassSection::with('academicSession')->orderBy('name')->get();

        return view('admin.reports.index', compact('classes'));
    }
}