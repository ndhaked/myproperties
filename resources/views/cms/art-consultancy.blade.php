<x-dashboard-layout>
    <main class="main-content add-new-artist-page cms-management-page art-consultancy-page">
        <header class="content-header">
       	<div style="display: flex; align-items: center; gap: 16px;">
       		<button class="mobile-menu-toggle" id="mobileMenuToggle">
       			<div class="hamburger">
       				<span></span>
       				<span></span>
       				<span></span>
       			</div>
       		</button>
       		<div class="flex flex-col gap-1">
       			<h1 class="page-title">ADAI Art Consultancy</h1>
       		</div>
       	</div>
       </header>
        
             <div class="artist-filter-tabs flex items-center space-x-2 overflow-x-auto">
                <button 
                    class="filter-tab tab-btn active text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'hero','page'=>'art_consultancy']) }}">
                    Hero Section
                </button>
                <button 
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'overview','page'=>'art_consultancy']) }}">
                     Overview
                </button>
                <button 
                    class="filter-tab tab-btn  text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'core_services','page'=>'art_consultancy']) }}">
                     Core Services
                </button>
                <button 
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'private_collection_strategy','page'=>'art_consultancy']) }}">
                    Private Collection Strategy
                </button>
                <button data-url="{{ route('cms.section',['section'=>'bespoke_client_services','page'=>'art_consultancy']) }}"
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm">
                    Bespoke Client Services
                </button>
                <button data-url="{{ route('cms.section',['section'=>'philanthropy_cultural_legacy','page'=>'art_consultancy']) }}"
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm">
                    Philanthropy & Cultural Legacy
                </button>
                <button data-url="{{ route('cms.section',['section'=>'get_in_touch','page'=>'art_consultancy']) }}"
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm">
                    Get In Touch
                </button>
                
            </div>



        <div id="tabContent">
            @include('cms.sections.commonSection',compact('content','section','page','fields'))

        </div>

    </main>
    @section('uniquePageScript')

    @endsection
</x-dashboard-layout>