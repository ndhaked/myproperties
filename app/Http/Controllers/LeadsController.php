<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Budget;
use App\Models\Source;
use App\Models\Medium;
use App\Models\Purpose;
use App\Models\PurposeType;
use App\Models\LeadCategory;
use App\Models\User;
use App\Models\LeadTask;
use App\Models\LeadNote;
use App\Models\LeadLog;
use App\Models\LeadActivityLog;

class LeadsController extends Controller
{
    function __construct(Project $Project, Lead $Lead, Country $Country)
    {
        $this->Project = $Project;
        $this->Lead = $Lead;
        $this->Country = $Country;
    }

    public function index(Request $request)
    {
        $type  = $request->get('type') ?? 'all';

        // 1. Setup Variables
        $isAdmin = auth()->user()->hasRole('adai_admin');
        $isAdmin = auth()->user()->hasRole('adai_admin');
        $userId  = auth()->id();
        $agents = User::all();

        // 2. Helper: Create a fresh query with BASE rules applied
        // We use a closure so we get a NEW query object every time we call it.
        $baseQuery = function () {
            $q = $this->Lead->query();
            return $q;
        };

        // 3. Counts Logic (Call baseQuery() for each line)
        $tab_counts = [
            'all' => $baseQuery()->count(),

            'for_review' => 0,

            'draft'      => 0,

            'approved'   => $baseQuery()->where('status', 1)->count(),


            'new'        => 0, // Or whatever your 'new' logic is

            'rejected'   => $baseQuery()->where('status', 0)->count(),

            // For archived, we need to start fresh because of onlyTrashed()
            'archived'   => 0,
        ];
        // 3. Ajax load
        if ($request->ajax()) {
            // Note: You must also update 'getRecord' to handle the admin check!
            $records = $this->getRecord($request);

            return response()->json([
                'body' => json_encode(
                    View::make('leads.ajax_all_records', compact('type', 'records', 'tab_counts', 'agents'))->render()
                ),
                'tab_counts' => $tab_counts, // Updated variable name to match your previous code
            ]);
        }

        $records = $this->getRecord($request);

        return view('leads.index', compact('type', 'records', 'tab_counts', 'agents'));
    }

    private function getRecord(Request $request)
    {
        // 1. Initialize Query
        // If we are looking for 'archived', we need to include trashed items
        $query = $this->Lead->latest();

        if ($request->get('type') == 'archived') {
            $query->onlyTrashed();
        }

        // 3. Tab Filtering (Status)
        // This matches the logic you used in the 'counts' array
        if ($request->has('type')) {
            switch ($request->get('type')) {
                case 'approved': // Matches "Active" tab
                    $query->where('status', 1);
                    break;
                case 'rejected': // Matches "Active" tab
                    $query->where('status', 0);
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
        // 1. Fetch data for all dropdowns
        // We use 'all()' or 'where' to get active records
        $projects = Project::where('status', 1)->get(); 
        $budgets = config('custom.budgets');
        $sources = Source::where('status', 1)->get();
        $mediums = Medium::where('status', 1)->get();
        $purposes = Purpose::all(); // Add status check if you have it
        $purposeTypes = PurposeType::where('status', 1)->get();
        $leadCategories = LeadCategory::where('status', 1)->get();
        
        // Fetch Agents (Users). You might want to filter by role if you have Spatie Roles
        // Example: User::role('agent')->get();
        $agents = User::all(); 

        $countriesLists = $this->Country
                ->selectRaw('MIN(id) as id, TRIM(country) as country')
                ->whereNotNull('country')
                ->where('country', '!=', '')
                ->groupByRaw('TRIM(country)')
                ->orderBy('country','asc')
                ->get();

        // 2. Return the view with all variables
        return view('leads.create', compact(
            'projects',
            'budgets',
            'sources',
            'mediums',
            'purposes',
            'purposeTypes',
            'leadCategories',
            'agents',
            'countriesLists'
        ));
    }

    public function store(CreateLeadRequest $request): RedirectResponse|JsonResponse
    {
        // You already have validated data here
        $validated = $request->validated();

        $lead = $this->Lead->create($validated);

        // ------------------------------------------------------------------
        // LOGGING START: Add this section
        // ------------------------------------------------------------------

        // Log 1: Record that the Lead was Created
        LeadActivityLog::create([
            'lead_id'     => $lead->id,
            'user_id'     => auth()->id(), // User who created it
            'log_type'    => 'general',    // General activity
            'description' => 'Lead created manually by ' . auth()->user()->name,
            'log_date'    => now(),
            'log_time'    => now(),
        ]);

        // Log 2: If an Agent was assigned during creation, log that too
        if ($request->filled('assigned_agent_id')) {
            $agent = User::find($request->assigned_agent_id);
            
            if($agent) {
                LeadActivityLog::create([
                    'lead_id'          => $lead->id,
                    'user_id'          => auth()->id(),
                    'assigned_user_id' => $agent->id,
                    'log_type'         => 'assignment',
                    'description'      => 'Lead initially assigned to ' . $agent->name,
                    'log_date'         => now(),
                    'log_time'         => now(),
                ]);
            }
        }
        // ------------------------------------------------------------------
        // LOGGING END
        // ---

        $successMessage = "Lead created successfully";
        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'status_code' => 200,
                'type'        => 'success',
                'error'       => false,
                'isDisabledTrue'  => 'true',
                'message'     => $successMessage,
                'url'         => route('leads.index'),
            ], 200);
        }

        // Normal redirect
        return redirect()
            ->route('leads.index')
            ->with('success', $successMessage);
    }

    public function edit($id)
    {
        $lead = $this->Lead::findOrFail($id);

        $projects = Project::where('status', 1)->get();
        // Fetch Budget from Config File
        $budgets = config('custom.budgets'); 
        
        $sources = Source::where('status', 1)->get();
        $mediums = Medium::where('status', 1)->get();
        $purposes = Purpose::all();
        $purposeTypes = PurposeType::where('status', 1)->get();
        $leadCategories = LeadCategory::where('status', 1)->get();
        $agents = User::all();

        $countriesLists = $this->Country
                ->selectRaw('MIN(id) as id, TRIM(country) as country')
                ->whereNotNull('country')
                ->where('country', '!=', '')
                ->groupByRaw('TRIM(country)')
                ->orderBy('country','asc')
                ->get();

        return view('leads.create', compact(
            'lead',
            'projects',
            'budgets',
            'sources',
            'mediums',
            'purposes',
            'purposeTypes',
            'leadCategories',
            'agents',
            'countriesLists',
        ));
    }


    public function update(CreateLeadRequest $request, $id): RedirectResponse|JsonResponse
    {
        // 1. Find the Lead (Variable name is $project in your code)
        $project = $this->Lead::findOrFail($id);

        // 2. Validate Data
        $validated = $request->validated();

        // 3. CAPTURE OLD VALUES (Before Update)
        $oldStatus = $project->status;
        $oldAgentId = $project->assigned_agent_id;

        // 4. FILL & DETECT CHANGES
        // We use fill() instead of update() so we can check getDirty() before saving
        $project->fill($validated);
        
        // Get the list of changed fields (e.g., ['status' => 'new', 'email' => '...'])
        $changes = $project->getDirty();
        
        // 5. SAVE TO DATABASE
        $project->save();

        // ------------------------------------------------------------------
        // LOGGING LOGIC START
        // ------------------------------------------------------------------
        $user = auth()->user();

        // A. Log Status Change
        if (array_key_exists('status', $changes)) {
            LeadActivityLog::create([
                'lead_id'     => $project->id,
                'user_id'     => $user->id,
                'log_type'    => 'status_change',
                'description' => "Status changed from '{$oldStatus}' to '{$project->status}'",
                'log_date'    => now(),
                'log_time'    => now(),
            ]);
        }

        // B. Log Agent Assignment Change
        if (array_key_exists('assigned_agent_id', $changes)) {
            // Find the new agent's name
            $newAgent = User::find($project->assigned_agent_id);
            $agentName = $newAgent ? $newAgent->name : 'Unassigned';

            LeadActivityLog::create([
                'lead_id'          => $project->id,
                'user_id'          => $user->id,
                'assigned_user_id' => $project->assigned_agent_id,
                'log_type'         => 'assignment',
                'description'      => "Lead re-assigned to agent {$agentName}",
                'log_date'         => now(),
                'log_time'         => now(),
            ]);
        }

        // C. Log General Updates (excluding status/agent/timestamps)
        $otherChanges = collect($changes)->except(['status', 'assigned_agent_id', 'updated_at']);
        
        if ($otherChanges->isNotEmpty()) {
            $changedFields = $otherChanges->keys()->implode(', ');
            
            LeadActivityLog::create([
                'lead_id'     => $project->id,
                'user_id'     => $user->id,
                'log_type'    => 'general',
                'description' => "Updated fields: {$changedFields}",
                'log_date'    => now(),
                'log_time'    => now(),
            ]);
        }
        // ------------------------------------------------------------------
        // LOGGING LOGIC END
        // ------------------------------------------------------------------

        // Prepare Response Messages
        $successMessage = "Lead updated successfully.";

        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'status_code' => 200,
                'type'        => 'success',
                'error'       => false,
                'isDisabledTrue'  => 'true',
                'message'     => $successMessage,
                'url'         => route('leads.index'),
            ], 200);
        }

        // Normal redirect
        return redirect()
            ->route('leads.index')
            ->with('success', $successMessage);
    }

    public function show($id)
    {
        $lead = \App\Models\Lead::with([
            'project', 'purposeType', 'source', 'medium', 'agent', 'leadCategory'
        ])->findOrFail($id);
        $durations = [];
        // Generate up to 5 hours (300 minutes)
        for ($i = 15; $i <= 300; $i += 15) {
            $h = floor($i / 60);
            $m = $i % 60;
            
            $text = [];
            if ($h > 0) $text[] = "$h hours";
            if ($m > 0) $text[] = "$m minutes";
            
            // Key is minutes (int), Value is label (string)
            $durations[$i] = implode(' ', $text);
        }
        //dd($durations);
        return view('leads.show', compact('lead','durations'));
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
                    'message'     => 'Lead deleted successfully',
                    'url'         => route('leads.index')
                ]);
            }

            // Normal delete
            return redirect()
                ->route('projects.index')
                ->with('success', 'Lead deleted successfully');
        } catch (\Exception $e) {

            if ($request->ajax()) {
                return response()->json([
                    'status_code' => 500,
                    'error'       => true,
                    'message'     => 'Unable to delete lead. Please try again.'
                ], 500);
            }

            return back()->with('error', 'Unable to delete lead. Please try again.');
        }
    }

    public function taskStore(Request $request)
    {
        // 1. Validate using the specific names from your form
        $request->validate([
            'lead_id'     => 'required|exists:leads,id',
            'name'        => 'required|string|max:255', // Matches name="name"
            'date'        => 'required|date',           // Matches name="date"
            'time'        => 'required',                // Matches name="time"
            'description' => 'nullable|string',         // Matches name="description"
            'type'        => 'required|string',         // Matches name="type"
        ]);

        // 2. Create Task using the form data
        LeadTask::create([
            'lead_id'     => $request->lead_id,
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'date'        => $request->date,
            'time'        => $request->time,
            'description' => $request->description,
            'type'        => $request->type,
            'status'      => 'pending',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status_code' => 200,
                'type'       => 'success',
                'message'     => 'Task Created Successfully',
                'modelCloseSide' => 'closeTaskModalBtn',
                //'isPageListRefreshByAjax' => 'true',
            ]);
        }


        // 3. Return success
        return redirect()->back()->with('success', 'Task Created Successfully');
    }

    public function noteStore(Request $request)
    {
        // 1. Basic Validation (Note Description is always required)
        $rules = [
            'lead_id'     => 'required|exists:leads,id',
            'description' => 'required|string', // Note content
        ];

        // 2. Add Conditional Validation for Task fields if "with_task" is checked
        if ($request->has('with_task')) {
            $rules['task_date'] = 'required|date';
            $rules['task_time'] = 'required';
            $rules['task_type'] = 'required|string';
            // Note: 'name' seems missing from your note form screenshot, 
            // usually tasks have a name. If your form doesn't have it, remove this line or use description as name.
            // $rules['name'] = 'required|string|max:255'; 
        }

        $request->validate($rules);

        // 3. Create the Note
        LeadNote::create([
            'lead_id'     => $request->lead_id,
            'user_id'     => Auth::id(),
            'description' => $request->description,
        ]);

        // 4. Create the Task (Only if checked)
        if ($request->has('with_task')) {
            LeadTask::create([
                'lead_id'     => $request->lead_id,
                'name'        => 'Follow up - ' . $request->task_type, // Auto-generate name or add input field
                'date'        => $request->task_date,
                'time'        => $request->task_time,
                'description' => $request->description, // Use note desc for task desc
                'type'        => $request->task_type,
                'status'      => 'pending',
            ]);
        }

        // 5. Return Response
        if ($request->ajax()) {
            return response()->json([
                'status_code'    => 200,
                'type'           => 'success',
                'message'        => 'Note Added Successfully',
                'modelCloseSide' => 'closeNoteModalBtn', // Use the ID of your Note modal close button
            ]);
        }

        return redirect()->back()->with('success', 'Note Added Successfully');
    }

    /**
     * Store a new Activity Log (Call/Meeting/etc) (and optionally a Task)
     */
    public function logsStore(Request $request)
    {
        // 1. Validation
        $rules = [
            'lead_id'  => 'required|exists:leads,id',
            'log_type' => 'required|string',
            'log_date' => 'required|date',
            'log_time' => 'required',
        ];

        if ($request->has('with_task')) {
            $rules['task_date'] = 'required|date';
            $rules['task_time'] = 'required';
            $rules['task_type'] = 'required|string';
        }

        $request->validate($rules);

        // 2. Create Log
        // Determine outcome based on type (meeting has specific field, call has another)
        $outcome = $request->log_type == 'meeting' ? $request->meeting_outcome : $request->outcome; // Adjust form field names if needed

        LeadLog::create([
            'lead_id'     => $request->lead_id,
            'user_id'     => Auth::id(),
            'log_type'    => $request->log_type,
            'log_date'    => $request->log_date,
            'log_time'    => $request->log_time,
            'description' => $request->description,
            'duration'    => $request->duration, // Only for meetings
            'outcome'     => $outcome,
            'status'      => $request->status,
            'lead_type'   => $request->lead_type,
        ]);

        // 3. Update Lead Status (If changed in log)
        if ($request->status) {
            $lead = Lead::find($request->lead_id);
            $lead->status = $request->status;
            $lead->save();
        }

        // 4. Create Task (Optional)
        if ($request->has('with_task')) {
            LeadTask::create([
                'lead_id'     => $request->lead_id,
                'user_id'     => Auth::id(),
                'name'        => 'Follow up - ' . ucfirst($request->task_type),
                'date'        => $request->task_date,
                'time'        => $request->task_time,
                'description' => 'Follow up on previous ' . $request->log_type,
                'type'        => $request->task_type,
                'status'      => 'pending',
            ]);
        }

             // 5. Return Response
        if ($request->ajax()) {
            return response()->json([
                'status_code'    => 200,
                'type'           => 'success',
                'message'        => 'Log Added Successfully',
                'modelCloseSide' => 'closeLogCallModalBtn', // Use the ID of your Note modal close button
            ]);
        }


        return redirect()->back()->with('success', 'Activity Logged Successfully');
    }

    public function assignLeads(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'agent_id' => 'required|exists:users,id',
        ]);

        // 1. Perform the Bulk Update
        $this->Lead->whereIn('id', $request->ids)->update(['assigned_agent_id' => $request->agent_id]);

        // ------------------------------------------------------------------
        // 2. LOGGING LOGIC (Start)
        // ------------------------------------------------------------------
        
        // Fetch agent details once to use in the description
        $agent = User::find($request->agent_id);
        $agentName = $agent ? $agent->name : 'Unknown';
        $currentUser = auth()->id();
        $timestamp = now();

        // Loop through each Lead ID and create a log
        foreach ($request->ids as $leadId) {
            LeadActivityLog::create([
                'lead_id'          => $leadId,
                'user_id'          => $currentUser,       // Admin/Manager who clicked "Assign"
                'assigned_user_id' => $request->agent_id, // The Agent who received the lead
                'log_type'         => 'assignment',
                'description'      => "Lead assigned to agent {$agentName} (Bulk Action)",
                'log_date'         => $timestamp,
                'log_time'         => $timestamp,
            ]);
        }
        // ------------------------------------------------------------------
        // LOGGING LOGIC (End)
        // ------------------------------------------------------------------

        return response()->json([
            'status_code' => 200,
            'message' => 'Leads assigned successfully',
            'type' => 'success',
            'isPageListRefreshByAjax' => 'true',
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
        ]);

        try {
            $this->Lead->whereIn('id', $request->ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Leads deleted successfully',
                'type' => 'success',
                'isPageListRefreshByAjax' => 'true',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Failed to delete leads',
                'type' => 'error',
            ]);
        }
    }
}
