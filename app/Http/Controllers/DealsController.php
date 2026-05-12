<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDealRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Country;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Purpose;
use App\Models\PurposeType;
use App\Models\Source;
use App\Models\Medium;
use App\Models\User;
use App\Models\DealStatus;
use App\Models\LeadCategory;

class DealsController extends Controller
{
    function __construct(Project $Project, Lead $Lead, Country $Country, Deal $Deal)
    {
        $this->Project = $Project;
        $this->Lead = $Lead;
        $this->Country = $Country;
        $this->Deal = $Deal;
    }

    public function index(Request $request)
    {
        $type  = $request->get('type') ?? 'all';

        // 1. Setup Variables
        $isAdmin = auth()->user()->hasRole('adai_admin');
        $userId  = auth()->id();

        // 2. Helper: Create a fresh query with BASE rules applied
        // We use a closure so we get a NEW query object every time we call it.
        $baseQuery = function () {
            $q = $this->Deal->query();
            return $q;
        };

        // 3. Counts Logic (Call baseQuery() for each line)
        $tab_counts = [
            'all' => $baseQuery()->count(),

            'for_review' => 0,

            'draft'      => 0,

            'approved'   => $baseQuery()->where('deal_status_id', 1)->count(),


            'new'        => 0, // Or whatever your 'new' logic is

            'rejected'   => $baseQuery()->where('deal_status_id', 0)->count(),

            // For archived, we need to start fresh because of onlyTrashed()
            'archived'   => 0,
        ];
        // 3. Ajax load
        if ($request->ajax()) {
            // Note: You must also update 'getRecord' to handle the admin check!
            $records = $this->getRecord($request);

            return response()->json([
                'body' => json_encode(
                    View::make('deals.ajax_all_records', compact('type', 'records', 'tab_counts'))->render()
                ),
                'tab_counts' => $tab_counts, // Updated variable name to match your previous code
            ]);
        }

        $records = $this->getRecord($request);

        return view('deals.index', compact('type', 'records', 'tab_counts'));
    }

    private function getRecord(Request $request)
    {
        // 1. Initialize Query
        // If we are looking for 'archived', we need to include trashed items
        $query = $this->Deal->latest();

        if ($request->get('type') == 'archived') {
            $query->onlyTrashed();
        }

        // 3. Tab Filtering (Status)
        // This matches the logic you used in the 'counts' array
        if ($request->has('type')) {
            switch ($request->get('type')) {
                case 'approved': // Matches "Active" tab
                    $query->where('deal_status_id', 1);
                    break;
                case 'rejected': // Matches "Active" tab
                    $query->where('deal_status_id', 0);
                    break;
                case 'all':
                default:
                    // 'all' and 'archived' don't need extra status filters here
                    break;
            }
        }

        // 4. Search Filters (From your JS logic)
        if ($request->filled('search')) {
            $term = $request->get('search');
            $query->where(function ($q) use ($term) {
                $q->where('firstname', 'LIKE', "%{$term}%")
                    ->orWhere('lastname', 'LIKE', "%{$term}%");
            });
        }


        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // 5. Sorting
        $sortColumn = 'created_at';
        $sortOrder  = 'desc';

        if ($request->filled('sortby')) {
            $sortColumn = $request->get('sortby');
        }
        if ($request->filled('status')) { // Your JS sends 'status' as 'order_by'
            $sortOrder = $request->get('status'); // asc or desc
        }

        //$query->orderBy($sortColumn, $sortOrder);

        // 6. Return Pagination
        return $query->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 1. Fetch Countries (Using your specific query)
        $countriesLists = Country::selectRaw('MIN(id) as id, TRIM(country) as country')
                ->whereNotNull('country')
                ->where('country', '!=', '')
                ->groupByRaw('TRIM(country)')
                ->orderBy('country','asc')
                ->get();

        // 2. Fetch Dropdown Data (Active records only)
        $projects = Project::where('status', 1)->get();
        $projectTypes = ProjectType::where('status', 1)->get();
        
        $purposes = Purpose::all(); // Add status check if column exists
        $purposeTypes = PurposeType::where('status', 1)->get();
        
        $sources = Source::where('status', 1)->get();
        $mediums = Medium::where('status', 1)->get(); 
        
        // Users for Agent, Leader, Sales Director
        // You might want to filter this by role (e.g., User::role('agent')->get())
        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->get();

        // 2. Get Agents
        $agents = User::whereHas('roles', function($query) {
            $query->where('name', 'agent');
        })->get();

        // 3. Get Leaders
        $leaders = User::whereHas('roles', function($query) {
            $query->where('name', 'leader');
        })->get(); 
        
        $dealStatuses = DealStatus::where('status', 1)->get();
        
        // If your view uses categories/budgets (reusing Lead logic):
        $dealCategories = LeadCategory::where('status', 1)->get(); 
        $budgets = config('custom.budgets'); 

        // 3. Return the view with all variables
        return view('deals.create', compact(
            'countriesLists',
            'projects',
            'projectTypes',
            'purposes',
            'purposeTypes',
            'sources',
            'mediums',
            'agents',
            'leaders',
            'admins',
            'dealStatuses',
            'dealCategories',
            'budgets'
        ));
    }
    
    public function store(CreateDealRequest $request): RedirectResponse|JsonResponse
    {
        // You already have validated data here
        $validated = $request->validated();

        $deal = $this->Deal->create($validated);

        $successMessage = "Deal created successfully";
        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'status_code' => 200,
                'type'        => 'success',
                'error'       => false,
                'isDisabledTrue'  => 'true',
                'message'     => $successMessage,
                'url'         => route('deals.index'),
            ], 200);
        }

        // Normal redirect
        return redirect()
            ->route('deals.index')
            ->with('success', $successMessage);
    }

    public function edit($id)
    {
        $deal = $this->Deal::findOrFail($id);

        // 1. Fetch Countries (Using your specific query)
        $countriesLists = Country::selectRaw('MIN(id) as id, TRIM(country) as country')
                ->whereNotNull('country')
                ->where('country', '!=', '')
                ->groupByRaw('TRIM(country)')
                ->orderBy('country','asc')
                ->get();

        // 2. Fetch Dropdown Data (Active records only)
        $projects = Project::where('status', 1)->get();
        $projectTypes = ProjectType::where('status', 1)->get();
        
        $purposes = Purpose::all(); // Add status check if column exists
        $purposeTypes = PurposeType::where('status', 1)->get();
        
        $sources = Source::where('status', 1)->get();
        $mediums = Medium::where('status', 1)->get(); 
        
        // Users for Agent, Leader, Sales Director
        // You might want to filter this by role (e.g., User::role('agent')->get())
        // 1. Get Admins
        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->get();

        // 2. Get Agents
        $agents = User::whereHas('roles', function($query) {
            $query->where('name', 'agent');
        })->get();

        // 3. Get Leaders
        $leaders = User::whereHas('roles', function($query) {
            $query->where('name', 'leader');
        })->get();
        
        $dealStatuses = DealStatus::where('status', 1)->get();
        
        // If your view uses categories/budgets (reusing Lead logic):
        $dealCategories = LeadCategory::where('status', 1)->get(); 
        $budgets = config('custom.budgets'); 

        return view('deals.create', compact(
            'deal',
            'countriesLists',
            'projects',
            'projectTypes',
            'purposes',
            'purposeTypes',
            'sources',
            'mediums',
            'agents',
            'leaders',
            'admins',
            'dealStatuses',
            'dealCategories',
            'budgets'
        ));
    }

    public function update(CreateDealRequest $request, $id): RedirectResponse|JsonResponse
    {
        // 1. Find the Artist
        $project = $this->Deal::findOrFail($id);

        // 2. Validate Data
        $validated = $request->validated();
        // Prepare Response Messages
        $successMessage = "Deal updated successfully.";
        $project->update($validated);

        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'status_code' => 200,
                'type'        => 'success',
                'error'       => false,
                'isDisabledTrue'  => 'true',
                'message'     => $successMessage,
                'url'         => route('deals.index'),
            ], 200);
        }

        // Normal redirect
        return redirect()
            ->route('deals.index')
            ->with('success', $successMessage);
    }

    public function destroy(Request $request, Lead $lead)
    {
        try {

            $lead->delete();

            // If AJAX delete
            if ($request->ajax()) {
                return response()->json([
                    'status_code' => 200,
                    'error'       => false,
                    'message'     => 'Deal deleted successfully',
                    'url'         => route('deals.index')
                ]);
            }

            // Normal delete
            return redirect()
                ->route('deals.index')
                ->with('success', 'Deal deleted successfully');
        } catch (\Exception $e) {

            if ($request->ajax()) {
                return response()->json([
                    'status_code' => 500,
                    'error'       => true,
                    'message'     => 'Unable to delete deal. Please try again.'
                ], 500);
            }

            return back()->with('error', 'Unable to delete deal. Please try again.');
        }
    }
}
