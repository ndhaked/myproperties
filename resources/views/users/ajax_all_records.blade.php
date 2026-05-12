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
<div class="bg-white rounded-lg border border-gray-100">
    <div class="users-grid artist-list-grid artist-list-header-grid grid items-center px-2 border-gray-100 bg-[#FAFAFA] text-xs font-semibold text-gray-500 uppercase tracking-wider">
        <input type="checkbox" class="w-0 h-0 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
        <span class="text-left list-heading-text">ID </span>
        <span class="text-left list-heading-text">Name</span>
        <span class="px-4 list-heading-text">EMAIL</span>
        <span class="text-left list-heading-text">ROLE</span>
        <span class="text-center list-heading-text">CREATED AT</span>
        <span class="text-center list-heading-text">STATUS</span>
        <span class="text-right list-heading-text">Action</span>
    </div>

    <!-- List Rows -->
    <div id="artwork-list">
       @forelse($records as $key => $recordList)
           <div class="users-grid artist-list-grid artist-list-header-grid grid items-center px-2 border-t border-gray-100 transition duration-100 hover:bg-[#FAFAFA]" data-gallery-id="modern-art">
               <input type="checkbox" class="w-0 h-0 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
               <span class="list-row-text text-left art-gallery-name">{{$recordList->id}}</span>
                <span class="list-row-text text-left art-gallery-name">{{$recordList->name}}</span>
               <span class="list-row-text text-left art-gallery-name">{{$recordList->email}}</span>
               <span class="list-row-text text-left art-gallery-name">{{ ucwords($recordList->roles->pluck('name')->implode(', ')) }}</span>
               <span class="list-row-text text-center">{{$recordList->created_at->format('d/m/y')}}</span>
                <span class="list-row-text text-center">
                    <label class="switch">
                        <input type="checkbox" class="user-status-toggle" 
                               data-id="{{ $recordList->id }}"
                               data-route="{{ route('users.toggle_status') }}"
                               {{ $recordList->status === 'active' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-xs ml-2 font-medium text-gray-500 status-text-{{$recordList->id}}">
                        {{ ucfirst($recordList->status) }}
                    </span>
                </span>
               <div class="relative flex justify-end">
                    <button class="action-menu-btn p-1 rounded-full row-button">
                         <img src="{{ asset('assets/svg/three-dots.svg') }}" class="object-cover three-dots-menu"/>
                   </button>
                    <div class="action-menu-dropdown absolute right-4 w-full top-full bg-white shadow-xl z-10  hidden">
                        <a href="{{route('users.edit',$recordList->id)}}" class="dot-menu-button">Edit User</a>
                        <a href="javascript:;" class="dot-menu-button deleteArtist" data-id="{{ $recordList->id }}">Delete User</a>
                   </div>
               </div>
           </div>
       @empty
          <div class="grid items-center p-4 border-b border-gray-100 hover:bg-gray-50 transition duration-100 text-center" data-gallery-id="modern-art">
                No Project Found
             </td>
          </div>
       @endforelse
    </div>
    <div class="pull-right" id="front_dash_pagination">
       {!! $records->appends(array('type' => $type))->links('front_dash_pagination') !!}
    </div>
</div>