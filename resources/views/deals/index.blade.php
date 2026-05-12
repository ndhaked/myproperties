<x-dashboard-layout>
    @section('title', "Deals | " .config('app.name'))
    <main class="main-content" id="main-artist-list">
        <header class="content-header">
          <div style="display: flex; align-items: center; gap: 16px;">
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
              <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
              </div>
            </button>
            <h1 class="page-title">Deals <span class="totel-counts">({{ $records->firstItem() }} - {{ $records->lastItem() }} of {{ $records->total() }})</span></h1>
          </div>
          <div class="header-actions">
            <a class="btn btn-primary" href="{{route('deals.create')}}">Add Deals</a>
          </div>
        </header>
        @if(count($records) > 0)
            <div class="content-body">
            @php
                $activeTab = $type ?? 'all';
            @endphp
            <input type="hidden" name="search_type" id="search_type" value="{{@$type}}">
            <div class="artist-filter-tabs flex items-center space-x-2 overflow-x-auto mb-6">

                {{-- All Deals --}}
                <button 
                    class="filter-tab active
                    {{ $activeTab == 'all' ? 'text-gray-800 bg-lime-400 border border-lime-500 shadow-sm' : 'text-gray-500 bg-white hover:bg-gray-50 border border-gray-200' }} active_remove_tab"
                    data-tab="all" data-default="all">
                    Deals <span class="numbers-badge" id="count_all">{{ $tab_counts['all'] }}</span>
                </button>
                
<?php /*
                {{-- For Draft --}}
                @if(auth()->user()->hasRole('gallery_admin'))
                <button 
                    class="filter-tab  
                    {{ $activeTab == 'draft' ? 'text-gray-800 bg-lime-400 border border-lime-500 shadow-sm' : 'text-gray-500 bg-white hover:bg-gray-50 border border-gray-200' }} active_remove_tab"
                    data-tab="draft" data-default="draft">
                    Draft <span class="numbers-badge" id="count_draft">{{ $tab_counts['draft'] }}</span>
                </button>
                @endif

                {{-- Active --}}
                <button 
                    class="filter-tab  
                    {{ $activeTab == 'approved' ? 'text-gray-800 bg-lime-400 border border-lime-500 shadow-sm' : 'text-gray-500 bg-white hover:bg-gray-50 border border-gray-200' }} active_remove_tab"
                    data-tab="approved" data-default="approved">
                    Active <span class="numbers-badge" id="count_approved">{{ $tab_counts['approved'] }}</span>
                </button>
                @if(auth()->user()->hasRole('gallery_admin'))
                    {{-- New --}}
                    <button 
                        class="filter-tab  
                        {{ $activeTab == 'new' ? 'text-gray-800 bg-lime-400 border border-lime-500 shadow-sm' : 'text-gray-500 bg-white hover:bg-gray-50 border border-gray-200' }} active_remove_tab"
                        data-tab="new" data-default="new">
                        New <span class="numbers-badge" id="count_new">{{ $tab_counts['new'] }}</span>
                    </button>
                @endif
                  {{-- Rejected --}}
                <button 
                    class="filter-tab  
                    {{ $activeTab == 'rejected' ? 'text-gray-800 bg-lime-400 border border-lime-500 shadow-sm' : 'text-gray-500 bg-white hover:bg-gray-50 border border-gray-200' }} active_remove_tab"
                    data-tab="rejected" data-default="rejected">
                    In-Active <span class="numbers-badge" id="count_rejected">{{ $tab_counts['rejected'] }}</span>
                </button>

                @if(auth()->user()->hasRole('gallery_admin'))
                {{-- Archived --}}
                <button 
                    class="filter-tab  
                    {{ $activeTab == 'archived' ? 'text-gray-800 bg-lime-400 border border-lime-500 shadow-sm' : 'text-gray-500 bg-white hover:bg-gray-50 border border-gray-200' }} active_remove_tab"
                    data-tab="archived" data-default="archived">
                    Archived <span class="numbers-badge" id="count_archived">{{ $tab_counts['archived'] }}</span>
                </button>
                @endif
            */ ?>
            </div>
            <div id="result">
                @include('deals.ajax_all_records')
            </div>
        </div>
        @include('deals._delete_popup')
        @else
            @include('deals.empty_list')
        @endif
    </main> 
</x-dashboard-layout>