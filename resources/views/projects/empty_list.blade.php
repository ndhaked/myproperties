@if(auth()->user()->hasRole('adai_admin'))
<div class="content-body">
  <div class="empty-state">
    <h2 class="empty-state-title">No Projects Yet</h2>
    <p class="empty-state-description">
      The currently have no projects added.<br>
      Submitted projects information will appear here once uploaded.
    </p>
    <div class="flex items-center gap-2">
    </div>
  </div>
</div>
@else
<div class="content-body">
  <div class="empty-state">
    <h2 class="empty-state-title">No Projects Yet</h2>
    <p class="empty-state-description">
      You haven’t added any projects.<br>
    </p>
    <div class="flex items-center gap-2">
      <a class="btn btn-primary" href="{{route('projects.create')}}" title="Add Artist">Add an Project</a>
      <!-- <a class="portal-btn-secondary-small" href="" title="Add Artwork">Add Project</a> -->
    </div>
  </div>
@endif