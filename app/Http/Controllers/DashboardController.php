<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Project;
use App\Models\Lead;
use App\Models\Deal;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    function __construct(Project $Project, Lead $Lead, Deal $Deal)
    {
        $this->Project = $Project;
        $this->Lead = $Lead;
        $this->Deal = $Deal;
    }

    public function dashboard(Request $request): View
    {
        $roles = auth()->user()->getRoleNames();
        // Check if user is Admin (has roles)
        $totalProjects = $this->Project->count();
        $totalDeals = $this->Deal->count();
        $totalLeads = $this->Lead->count();
        
        // Pass all variables to the view
        return view('adai_dashboard', compact(
            'totalDeals',
            'totalLeads',
            'totalProjects',
        ));
        if (auth()->user()->hasRole('admin')) {
            // --- 2. Artists Stats ---
            $totalProjects = $this->Project->count();
            $totalDeals = $this->Deal->count();
            $totalLeads = $this->Lead->count();

            // Pass all variables to the view
            return view('adai_dashboard', compact(
                'totalDeals',
                'totalLeads',
                'totalProjects',
            ));
        }
    }
}
