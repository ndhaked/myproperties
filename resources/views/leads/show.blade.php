<x-dashboard-layout>
    <main class="main-content lead-details-page p-6 details-bkg" id="lead-details-page">
        
        <header class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('leads.index') }}" class="flex items-center gap-2 bg-black text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 transition">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                    Back
                </a>
                
                <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white p-2 rounded-md hover:bg-red-600 transition delete-btn-lead">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    </button>
                </form>
            </div>

            <div class="flex flex-wrap items-center gap-2 bg-white p-1 rounded-lg shadow-sm border border-gray-100">
                <button onclick="filterTimeline('system', this)" class="filter-tab active px-4 py-2 bg-[#155e96] text-white rounded-md text-sm font-medium flex items-center gap-2 shadow-sm whitespace-nowrap transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                    Activity
                </button>
                <button onclick="filterTimeline('note', this)" class="filter-tab px-4 py-2 bg-white text-gray-600 hover:bg-gray-50 rounded-md text-sm font-medium border border-gray-200 flex items-center gap-2 transition whitespace-nowrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Notes
                </button>
                <button onclick="filterTimeline('task', this)" class="filter-tab px-4 py-2 bg-white text-gray-600 hover:bg-gray-50 rounded-md text-sm font-medium border border-gray-200 flex items-center gap-2 transition whitespace-nowrap">
                     <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Tasks
                </button>
                <button onclick="filterTimeline('call', this)" class="filter-tab px-4 py-2 bg-white text-gray-600 hover:bg-gray-50 rounded-md text-sm font-medium border border-gray-200 flex items-center gap-2 transition whitespace-nowrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    Call
                </button>
                <button onclick="filterTimeline('email', this)" class="filter-tab px-4 py-2 bg-white text-gray-600 hover:bg-gray-50 rounded-md text-sm font-medium border border-gray-200 flex items-center gap-2 transition whitespace-nowrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Email
                </button>
                <button onclick="filterTimeline('meeting', this)" class="filter-tab px-4 py-2 bg-white text-gray-600 hover:bg-gray-50 rounded-md text-sm font-medium border border-gray-200 flex items-center gap-2 transition whitespace-nowrap">
                     <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Meeting
                </button>
                <button onclick="filterTimeline('whatsapp', this)" class="filter-tab px-4 py-2 bg-white text-gray-600 hover:bg-gray-50 rounded-md text-sm font-medium border border-gray-200 flex items-center gap-2 transition whitespace-nowrap">
                     <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    Whatsapp
                </button>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-4 flex flex-col gap-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">{{ $lead->full_name }}</h2>
                            <p class="text-gray-500 text-sm">{{ $lead->project->name ?? 'No Project' }}</p>
                        </div>
                        <div class="text-gray-400">
                            <span class="px-2 py-1 bg-gray-100 rounded text-xs text-gray-600 uppercase">{{ $lead->status }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-6 items-center">
                        <span id="openTaskModal" class="px-3 py-1 bg-lime-500 text-white text-xs rounded-full cursor-pointer hover:bg-lime-600 transition">Task</span>
                        <span id="openNoteModal" class="px-3 py-1 bg-lime-500 text-white text-xs rounded-full cursor-pointer hover:bg-lime-600 transition">Note</span>
                        <span onclick="openLogModal('meeting')" class="px-3 py-1 bg-lime-500 text-white text-xs rounded-full cursor-pointer hover:bg-lime-600 transition">Meeting</span>

                        <div class="relative inline-block text-left">
                            <span id="logsDropdownBtn" class="px-3 py-1 bg-lime-500 text-white text-xs rounded-full cursor-pointer hover:bg-lime-600 flex items-center gap-1 transition select-none">
                                Logs
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                            <div id="logsDropdownMenu" class="hidden absolute right-0 mt-2 w-32 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-40">
                                <div class="p-1 flex flex-col gap-1">
                                    <button type="button" onclick="openLogModal('call')" class="w-full text-left px-2 py-1.5 rounded-md hover:bg-lime-50 group transition">
                                        <span class="px-2 py-0.5 rounded bg-cyan-100 text-cyan-800 text-xs font-medium border border-cyan-200 block w-fit">Call</span>
                                    </button>
                                    <div class="border-t border-gray-100"></div>
                                    <button type="button" onclick="openLogModal('email')" class="w-full text-left px-2 py-1.5 rounded-md hover:bg-lime-50 group transition">
                                        <span class="px-2 py-0.5 rounded bg-cyan-100 text-cyan-800 text-xs font-medium border border-cyan-200 block w-fit">Email</span>
                                    </button>
                                    <div class="border-t border-gray-100"></div>
                                    <button type="button" onclick="openLogModal('meeting')" class="w-full text-left px-2 py-1.5 rounded-md hover:bg-lime-50 group transition">
                                        <span class="px-2 py-0.5 rounded bg-[#84cc16] text-white text-xs font-medium border border-lime-600 block w-fit">Meeting</span>
                                    </button>
                                    <div class="border-t border-gray-100"></div>
                                    <button type="button" onclick="openLogModal('whatsapp')" class="w-full text-left px-2 py-1.5 rounded-md hover:bg-lime-50 group transition">
                                        <span class="px-2 py-0.5 rounded bg-cyan-100 text-cyan-800 text-xs font-medium border border-cyan-200 block w-fit">Whatsapp</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Full Name:</span>
                            <span class="text-gray-800 text-sm font-medium">{{ $lead->full_name }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Email:</span>
                            <span class="text-gray-800 text-sm font-medium truncate max-w-[150px]" title="{{ $lead->email }}">{{ $lead->email ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Phone:</span>
                            <span class="text-gray-800 text-sm font-medium">{{ $lead->mobile }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Project Type:</span>
                            <span class="text-gray-800 text-sm font-medium">{{ $lead->purposeType->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Project:</span>
                            <span class="text-gray-800 text-sm font-medium">{{ $lead->project->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Location:</span>
                            <span class="text-gray-800 text-sm font-medium">{{ $lead->city }}, {{ $lead->country }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Contact Owner:</span>
                            <span class="text-gray-800 text-sm font-medium">{{ $lead->agent->name ?? 'Unassigned' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Status:</span>
                            <span class="text-gray-800 text-sm font-medium capitalize">{{ $lead->status }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Campaign:</span>
                            <span class="text-gray-800 text-sm font-medium">{{ $lead->campaign ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Source:</span>
                            <span class="text-gray-800 text-sm font-medium">{{ $lead->source->name ?? '-' }}</span>
                        </div>
                         <div class="flex justify-between items-center pt-2">
                            <span class="text-gray-500 text-sm">Medium:</span>
                            <span class="text-gray-800 text-sm font-medium">{{ $lead->medium->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">

                <div class="bg-white rounded-lg shadow-sm border border-gray-100 h-full p-6">
                    <div class="flex flex-col gap-4" id="timelineContainer">
                        @if($lead->activityLogs && $lead->activityLogs->count() > 0)
                            @foreach($lead->activityLogs as $list)
                                <div class="timeline-item bg-white border border-gray-100 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow mb-3" data-category="system">
                                    
                                    <h4 class="text-[#84cc16] font-semibold text-base mb-2">
                                        {{ $list->description ?? 'Activity Logged' }}
                                    </h4>

                                    <div class="flex flex-wrap items-center gap-6 text-gray-500 text-xs">
                                        
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>
                                                {{ $list->user ? $list->user->email : 'System Auto-Log' }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>
                                                {{ $list->created_at->format('Y-m-d H:i:s') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center p-4 text-gray-400 text-sm">
                                No activity history found.
                            </div>
                        @endif

                        @if($lead->notes && $lead->notes->count() > 0)
                            @foreach($lead->notes as $note)
                            <div class="timeline-item bg-white border border-gray-100 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow" data-category="note">
                                <h4 class="text-[#84cc16] font-semibold text-base mb-2">Note Added</h4>
                                <p class="text-gray-600 mb-3 text-sm">{!! $note->description !!}</p>
                                <div class="flex flex-wrap items-center gap-6 text-gray-500 text-xs">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $note->user->email ?? 'N/A' }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $note->updated_at->format('Y-m-d H:i:s') }}
                                    </div>
                                </div>
                            </div>
                          @endforeach
                        @endif


                        @if($lead->tasks && $lead->tasks->count() > 0)
                            @foreach($lead->tasks as $task)
                            <div class="timeline-item bg-white border border-gray-100 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow" data-category="task">
                                <h4 class="text-[#84cc16] font-semibold text-base mb-2">Task: {{ $task->name }}</h4>
                                <p class="text-gray-600 mb-3 text-sm">{!! $task->description ?? 'No description' !!}</p>
                                <div class="flex flex-wrap items-center gap-6 text-gray-500 text-xs">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $task->date->format('Y-m-d') }} at {{ \Carbon\Carbon::parse($task->time)->format('h:i A') }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ $task->user->name ?? 'User' }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200 capitalize">{{ $task->type }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif  

                        @if($lead->callLogs && $lead->callLogs->count() > 0)
                            @foreach($lead->callLogs as $callLog)
                            <div class="timeline-item bg-white border border-gray-100 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow" data-category="call">
                                
                                <div class="flex justify-between items-start mb-3">
                                   <div class="flex flex-wrap items-center gap-6 text-gray-500 text-xs">
                                    <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $lead->email ?? 'N/A' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $callLog->user->email ?? 'User' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $callLog->created_at->format('Y-m-d H:i:s') }}
                                        </div>
                                    </div>
                                    
                                    <button class="bg-black text-white text-[10px] font-bold px-3 py-1 rounded uppercase tracking-wider hover:bg-gray-800 transition">
                                        Edit
                                    </button>
                                </div>

                                <div class="text-gray-600 text-sm mb-6 font-medium">
                                    {!! $callLog->description ?? 'No description' !!}
                                </div>

                                <div class="border-t border-gray-100 pt-4 flex flex-wrap gap-8 text-gray-500 text-sm">
                                    
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span class="capitalize">{{ $callLog->outcome ?? 'N/A' }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $callLog->log_date->format('d-m-Y') }}
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($callLog->log_time)->format('h:i A') }}
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        @endif

                        @if($lead->emailLogs && $lead->emailLogs->count() > 0)
                            @foreach($lead->emailLogs as $callLog)
                            <div class="timeline-item bg-white border border-gray-100 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow" data-category="email">
                                
                                <div class="flex justify-between items-start mb-3">
                                   <div class="flex flex-wrap items-center gap-6 text-gray-500 text-xs">
                                    <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $lead->email ?? 'N/A' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $callLog->user->email ?? 'User' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $callLog->created_at->format('Y-m-d H:i:s') }}
                                        </div>
                                    </div>
                                    
                                    <button class="bg-black text-white text-[10px] font-bold px-3 py-1 rounded uppercase tracking-wider hover:bg-gray-800 transition">
                                        Edit
                                    </button>
                                </div>

                                <div class="text-gray-600 text-sm mb-6 font-medium">
                                    {!! $callLog->description ?? 'No description' !!}
                                </div>

                                <div class="border-t border-gray-100 pt-4 flex flex-wrap gap-8 text-gray-500 text-sm">
                                    
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span class="capitalize">{{ $callLog->outcome ?? 'N/A' }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $callLog->log_date->format('d-m-Y') }}
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($callLog->log_time)->format('h:i A') }}
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        @endif

                        @if($lead->meetingLogs && $lead->meetingLogs->count() > 0)
                            @foreach($lead->meetingLogs as $callLog)
                            <div class="timeline-item bg-white border border-gray-100 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow" data-category="meeting">
                                
                                <div class="flex justify-between items-start mb-3">
                                   <div class="flex flex-wrap items-center gap-6 text-gray-500 text-xs">
                                    <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $lead->email ?? 'N/A' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $callLog->user->email ?? 'User' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $callLog->created_at->format('Y-m-d H:i:s') }}
                                        </div>
                                    </div>
                                    
                                    <button class="bg-black text-white text-[10px] font-bold px-3 py-1 rounded uppercase tracking-wider hover:bg-gray-800 transition">
                                        Edit
                                    </button>
                                </div>

                                <div class="text-gray-600 text-sm mb-6 font-medium">
                                    {!! $callLog->description ?? 'No description' !!}
                                </div>

                                <div class="border-t border-gray-100 pt-4 flex flex-wrap gap-8 text-gray-500 text-sm">
                                    
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span class="capitalize">{{ $callLog->outcome ?? 'N/A' }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $callLog->log_date->format('d-m-Y') }}
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($callLog->log_time)->format('h:i A') }}
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        @endif

                        @if($lead->whatsappLogs && $lead->whatsappLogs->count() > 0)
                            @foreach($lead->whatsappLogs as $callLog)
                            <div class="timeline-item bg-white border border-gray-100 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow" data-category="whatsapp">
                                
                                <div class="flex justify-between items-start mb-3">
                                   <div class="flex flex-wrap items-center gap-6 text-gray-500 text-xs">
                                    <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $lead->email ?? 'N/A' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $callLog->user->email ?? 'User' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $callLog->created_at->format('Y-m-d H:i:s') }}
                                        </div>
                                    </div>
                                    
                                    <button class="bg-black text-white text-[10px] font-bold px-3 py-1 rounded uppercase tracking-wider hover:bg-gray-800 transition">
                                        Edit
                                    </button>
                                </div>

                                <div class="text-gray-600 text-sm mb-6 font-medium">
                                    {!! $callLog->description ?? 'No description' !!}
                                </div>

                                <div class="border-t border-gray-100 pt-4 flex flex-wrap gap-8 text-gray-500 text-sm">
                                    
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span class="capitalize">{{ $callLog->outcome ?? 'N/A' }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $callLog->log_date->format('d-m-Y') }}
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($callLog->log_time)->format('h:i A') }}
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        @endif
                        <div class="timeline-item bg-white border border-gray-100 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow" data-category="system">
                            <h4 class="text-[#84cc16] font-semibold text-base mb-2">Status Changed To New</h4>
                            <div class="flex flex-wrap items-center gap-6 text-gray-500 text-xs">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $lead->email }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                     {{ $lead->created_at->format('Y-m-d H:i:s') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('leads.models.task')
    @include('leads.models.add_notes')
    @include('leads.models.logsModel')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</x-dashboard-layout>