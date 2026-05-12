@if(auth()->user()->hasRole('adai_admin') && $tab_counts['for_review'] > 0)
<div class="flex notification-banner">
    <div>
        <p class="text-sm text-gray-500">
            Added since your last session on 
            {{ Auth::user()->last_login_at 
                ? Auth::user()->last_login_at->format('d M Y \a\t H:i') 
                : 'First Session' 
            }}
        </p>
    </div>
    <button class="popup-close-btn text-gray-400 hover:text-gray-600">
        <span class="material-icons-outlined text-xl cancelNotPopHtml">close</span>
    </button>
</div>
@endif

<!-- Bulk Actions Toolbar (Hidden by default) -->
<div id="bulk-actions" class="hidden flex items-center gap-4 mb-4 bg-lime-100 p-2 rounded border border-lime-300">
    <span class="text-sm font-semibold text-lime-800"><span id="selected-count">0</span> Leads Selected</span>
    <button id="openAssignModal" class="btn btn-sm btn-primary bg-lime-600 hover:bg-lime-700 text-white px-3 py-1 rounded">
        Assign Leads
    </button>
    <button id="bulkDeleteLeads" class="btn btn-sm btn-danger bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded ml-2" data-leaddeleteroute="{{ route('leads.bulk_delete') }}">
        Delete Leads
    </button>
</div>

<div class="bg-white rounded-lg border border-gray-100">
    <div class="lead-grid artist-list-grid artist-list-header-grid grid items-center px-2 border-gray-100 bg-[#FAFAFA] text-xs font-semibold text-gray-500 uppercase tracking-wider">
        <input type="checkbox" id="selectAllLeads" class="text-violet-600 border-gray-300 rounded focus:ring-violet-500">
        <span class="text-left list-heading-text">ID </span>
        <span class="text-left list-heading-text">NAME </span>
        <span class="text-left list-heading-text">COUNTRY</span>
        <span class="text-left list-heading-text">PROJECT</span>
        <span class="text-left list-heading-text">ASSIGN USER</span>
        <!-- <span class="text-left list-heading-text">MOBILE</span> -->
        <span class="text-center list-heading-text">CREATED AT</span>
        <span class="text-center list-heading-text">STATUS</span>
        <span class="text-right list-heading-text">Action</span>
    </div>

    <!-- List Rows -->
    <div id="artwork-list">
       @forelse($records as $key => $recordList)
           <div class="lead-grid artist-list-grid artist-list-header-grid grid items-center px-2 border-t border-gray-100 transition duration-100 hover:bg-[#FAFAFA]" data-gallery-id="modern-art">
               <input type="checkbox" value="{{$recordList->id}}" class="lead-checkbox text-violet-600 border-gray-300 rounded focus:ring-violet-500">
               <span class="list-row-text text-left art-gallery-name">{{$recordList->id}}</span>
               <span class="list-row-text text-left art-gallery-name">{{ucwords($recordList->full_name)}}</span>
               <span class="list-row-text text-left art-gallery-name">{{$recordList->country}}</span>
               <span class="list-row-text text-left art-gallery-name">
                    {{ ucwords($recordList->project->name ?? 'N/A') }}
               </span>
               <span class="list-row-text text-left art-gallery-name text-gray-600">
                    @if($recordList->agent)
                           {{ ucwords($recordList->agent->name) }}
                    @else
                       <span class="text-gray-400 italic text-[10px]">Unassigned</span>
                    @endif
               </span>
               <!-- <span class="list-row-text text-left art-gallery-name">{{$recordList->mobile}}</span> -->
               
               <span class="list-row-text text-center">{{$recordList->created_at->format('d/m/y')}}</span>
               <span class="list-row-text text-center">
                   <span class="list-status-published status_{{$recordList->status}}">
                       <span class="dot"></span>
                       {{ucfirst($recordList->status_label)}}
                   </span>
               </span>
               <div class="relative flex justify-end">
                    <button class="action-menu-btn p-1 rounded-full row-button">
                         <img src="{{ asset('assets/svg/three-dots.svg') }}" class="object-cover three-dots-menu"/>
                   </button>
                        <div class="action-menu-dropdown absolute right-4 w-full top-full bg-white shadow-xl z-10  hidden">
                            <a href="{{route('leads.edit',$recordList->id)}}" class="dot-menu-button">Edit Lead</a>
                            <a href="{{route('leads.show',$recordList->id)}}" class="dot-menu-button">View Lead</a>
                            <a href="javascript:;" class="dot-menu-button deleteArtist" data-id="{{ $recordList->id }}">Delete Lead</a>
                   </div>
               </div>
           </div>
       @empty
          <div class="grid items-center p-4 border-b border-gray-100 hover:bg-gray-50 transition duration-100 text-center" data-gallery-id="modern-art">
                No Leads Found
             </td>
          </div>
       @endforelse
    </div>
    <div class="pull-right" id="front_dash_pagination">
       {!! $records->appends(array('type' => $type))->links('front_dash_pagination') !!}
    </div>
</div>