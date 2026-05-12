<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\CreateProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\AssetFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProjectsController extends Controller
{
    function __construct(Project $Project)
    {
        $this->Project = $Project;
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
            $q = $this->Project->query();
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
                    View::make('projects.ajax_all_projects', compact('type', 'records', 'tab_counts'))->render()
                ),
                'tab_counts' => $tab_counts, // Updated variable name to match your previous code
            ]);
        }

        $records = $this->getRecord($request);

        return view('projects.index', compact('type', 'records', 'tab_counts'));
    }

    private function getRecord(Request $request)
    {
        // 1. Initialize Query
        // If we are looking for 'archived', we need to include trashed items
        $query = $this->Project->latest();

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

    public function create(Request $request)
    {
        return view('projects.create');
    }

    public function store(CreateProjectRequest $request): RedirectResponse|JsonResponse
    {
        // You already have validated data here
        $validated = $request->validated();

        $project = Project::create($validated);

        $successMessage = "Project created successfully";
        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'status_code' => 200,
                'type'        => 'success',
                'error'       => false,
                'isDisabledTrue'  => 'true',
                'message'     => $successMessage,
                'url'         => route('projects.index'),
            ], 200);
        }

        // Normal redirect
        return redirect()
            ->route('projects.index')
            ->with('success', $successMessage);
    }

    public function edit($id)
    {
        $project = $this->Project->findOrFail($id);
        return view('projects.create', compact( 'project'));
    }

    public function update(CreateProjectRequest $request, $id): RedirectResponse|JsonResponse
    {
        // 1. Find the Artist
        $project = Project::findOrFail($id);

        // 2. Validate Data
        $validated = $request->validated();
        // Prepare Response Messages
        $successMessage = "Project updated successfully.";
        $project->update($validated);

        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'status_code' => 200,
                'type'        => 'success',
                'error'       => false,
                'isDisabledTrue'  => 'true',
                'message'     => $successMessage,
                'url'         => route('projects.index'),
            ], 200);
        }

        // Normal redirect
        return redirect()
            ->route('projects.index')
            ->with('success', $successMessage);
    }

    public function destroy(Request $request, Project $project)
    {
        try {

            $project->delete();

            // If AJAX delete
            if ($request->ajax()) {
                return response()->json([
                    'status_code' => 200,
                    'error'       => false,
                    'message'     => 'Project deleted successfully',
                    'url'         => route('projects.index')
                ]);
            }

            // Normal delete
            return redirect()
                ->route('projects.index')
                ->with('success', 'Project deleted successfully');
        } catch (\Exception $e) {

            if ($request->ajax()) {
                return response()->json([
                    'status_code' => 500,
                    'error'       => true,
                    'message'     => 'Unable to delete project. Please try again.'
                ], 500);
            }

            return back()->with('error', 'Unable to delete project. Please try again.');
        }
    }

    public function approvedArtist(Request $request)
    {
        try {
            $id = $request->get('id');
            $project = $this->Project->find($id);
            if ($project) {
                if ($project->status == 1) {
                    return response()->json([
                        'status_code' => 200,
                        'type'        => 'error',
                        'message'     => 'Sorry! Project has been already active.',
                    ]);
                }
                if ($project->status == 0) {
                    $project->status = 1;
                    $project->save();
                    return response()->json([
                        'status_code' => 200,
                        'type'        => 'success',
                        'message'     => 'Project has been activated successfully.',
                        'modelCloseSide' => 'close-profile-btn',
                        'closeModel' => 'closeDeleteModal',
                        'isPageListRefreshByAjax' => 'true',
                    ]);
                }
                return response()->json([
                    'status_code' => 200,
                    'type'        => 'error',
                    'message'     => 'Project has been not been activated.',
                    //'reload'      => true,
                ]);
            }
            return response()->json([
                'status_code' => 200,
                'type'        => 'error',
                'message'     => 'Sorry! Project not available or may be activated.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'type'        => 'error',
                'message'     => 'Something went wrong: ' . $e->getMessage(),
            ]);
        }
    }

    public function rejectArtist(Request $request)
    {
        try {
            $id = $request->get('id');
            $artist = $this->Project->find($id);
            if ($artist) {
                if ($artist->status == 'rejected') {
                    return response()->json([
                        'status_code' => 200,
                        'type'        => 'error',
                        'message'     => 'Sorry! Artist has been already rejected.',
                    ]);
                }
                if ($artist->status == 'in-review' || $artist->status == 'approved') {
                    $artist->status = 'rejected';
                    $artist->approved_at = null;
                    $artist->rejected_at = now();
                    $artist->reject_reason = $request->reject_reason;
                    $artist->save();
                    //event(new RejectArtistNotification($artist));
                    $data = [
                        'status_code' => 200,
                        'type'        => 'success',
                        'message'     => 'Artist has been rejected successfully.',
                        'rejectCloseSide' => 'closeRejectModal',
                        'modelCloseSide' => 'close-profile-btn',
                        'isPageListRefreshByAjax' => 'true',
                        'reset' => 'true',
                    ];

                    // 2. Conditionally add the 'url' key
                    if ($request->reject_type == 'editPage') {
                        $data['url'] = route('artist.index');
                    }

                    // 3. Return the JSON
                    return response()->json($data);
                }
                return response()->json([
                    'status_code' => 200,
                    'type'        => 'error',
                    'modelCloseSide' => 'closeRejectModal',
                    'message'     => 'Artist has been not been rejected.',
                ]);
            }
            return response()->json([
                'status_code' => 200,
                'type'        => 'error',
                'message'     => 'Sorry! Artist not available or may be deleted.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'type'        => 'error',
                'message'     => 'Something went wrong: ' . $e->getMessage(),
            ]);
        }
    }
}
