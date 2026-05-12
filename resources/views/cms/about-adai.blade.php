<x-dashboard-layout>
    <main class="main-content add-new-artist-page cms-management-page about-adai-page">
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
       			<h1 class="page-title">ABOUT ADAI</h1>
       		</div>
       	</div>
       </header>
             <div class="artist-filter-tabs flex items-center space-x-2 overflow-x-auto">
                <button 
                    class="filter-tab tab-btn active text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'hero','page'=>'about_adai']) }}">
                    Hero Section
                </button>
                <button 
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'overview','page'=>'about_adai']) }}">
                     Overview
                </button>
                <button 
                    class="filter-tab tab-btn  text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'mission_vision_technology','page'=>'about_adai']) }}">
                     Mission/Vision/Technology
                </button>
                <button 
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'adai_core_service','page'=>'about_adai']) }}">
                     OUR SERVICES
                </button>
                <button data-url="{{ route('cms.section',['section'=>'why_adai','page'=>'about_adai']) }}"
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm">
                    Why ADAI
                </button>
                <button data-url="{{ route('cms.section',['section'=>'get_in_touch','page'=>'about_adai']) }}"
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm">
                    Get IN Touch
                </button>
                
                
            </div>



        <div id="tabContent">
            @include('cms.sections.commonSection',compact('content','section','page','fields'))

        </div>

    </main>

    @section('uniquePageScript')

    @endsection

</x-dashboard-layout>