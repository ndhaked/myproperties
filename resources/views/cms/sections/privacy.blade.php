      <x-dashboard-layout>
       <main class="main-content add-new-artist-page cms-management-page">
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
           <h1 class="page-title">Privacy Policy Section</h1>
          </div>
         </div>
        </header>

        <input type="hidden" id="coreserviceroute" name="coreserviceroute" value="{{ route('cms.item-block.partial',['type' => $section]) }}">
        <div class="content-body">
         <div class="flex items-center space-x-2 overflow-x-auto mb-6 p-1">
          <form id="F_cmsForm" action="{{ route('cms.section.store', ['section' => $section]) }}" method="POST"
           class="flex flex-col w-[908px] items-start gap-12 top-[162px] left-[312px] cms-form-wrapper" enctype="multipart/form-data">

           @csrf
           <input type="hidden" name="section" value="{{ $section }}">
           <input type="hidden" name="page_title" value="{{ $page }}">

           <section class="flex flex-col items-start gap-5 relative self-stretch w-full flex-[0_0_auto] privacy-section">

            <div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative">
             <div class="flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto] privacy-section-wrapper">

              <div class="flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto] privacy-cms-input-wrap">
               <label for="header_title"
                class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                Title
               </label>
               <div class="flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg cms-form-input-details">
                <div
                 class="flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                 <div class="flex flex-col items-start justify-center relative flex-1 grow">
                  <input type="text" required id="header_title" name="main_content[header_title]" required aria-required="true"
                   placeholder="Overview" value="{{ @$content['main_content']['header_title'] }}" title="Please enter  title"
                   class="w-full text-gray-700 placeholder-gray-400 outline-none" />
                 </div>
                </div>
               </div>

              </div>
              <div class="flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto] privacy-cms-input-wrap">
               <label for="header_title"
                class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                Content
               </label>
               <div class="flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                <div
                 class="flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                 <div class="flex flex-col items-start justify-center relative flex-1 grow">
                  <textarea class="summernote" name="main_content[content_abstract]">{!! $content['main_content']['content_abstract'] ?? '' !!}</textarea>

                 </div>
                </div>
               </div>

              </div>

             </div>
            </div>














           </section>


           @include('cms.partials.footer')
          </form>
         </div>
        </div>
       </main>
       @section('uniquePageScript')

       @endsection
      </x-dashboard-layout>