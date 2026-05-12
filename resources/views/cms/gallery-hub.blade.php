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
       			<h1 class="page-title">GalleryHub</h1>
       		</div>
       	</div>
       </header>
      
             <div class="artist-filter-tabs flex items-center space-x-2 overflow-x-auto">
                <button 
                    class="filter-tab tab-btn active text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'hero','page'=>'gallery_hub']) }}">
                    Hero Section
                </button>
                <button 
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'overview','page'=>'gallery_hub']) }}">
                     Overview
                </button>
                <button 
                    class="filter-tab tab-btn text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'gallery_hub_section','page'=>'gallery_hub']) }}">
                     Sections
                </button>
                  <button 
                    class="filter-tab tab-btn  text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'built_core_services','page'=>'gallery_hub']) }}">
                     BUILT FOR DISCOVERY
                </button>
                <button 
                    class="filter-tab tab-btn  text-gray-800 bg-lime-400 border border-lime-500 shadow-sm"
                    data-url="{{ route('cms.section',['section'=>'galleryHub_why_adai','page'=>'gallery_hub']) }}">
                     WHY ADAI
                </button>
               
                <button data-url="{{ route('cms.section',['section'=>'get_in_touch','page'=>'gallery_hub']) }}"
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