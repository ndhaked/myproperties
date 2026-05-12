       <!-- <header class="content-header">
       	<div style="display: flex; align-items: center; gap: 16px;">
       		<button class="mobile-menu-toggle" id="mobileMenuToggle">
       			<div class="hamburger">
       				<span></span>
       				<span></span>
       				<span></span>
       			</div>
       		</button>
       		<div class="flex flex-col gap-1">
       			<h1 class="page-title">Why Adai Section</h1>
       		</div>
       	</div>
       </header> -->
	   
       <input type="hidden" id="coreserviceroute" name="coreserviceroute" value="{{ route('cms.item-block.partial',['type' => $section]) }}">
       <div class="content-body">
       	<div class="flex items-center space-x-2 overflow-x-auto mb-6 p-1 cms-from-wrap">
       		<form id="F_cmsForm" action="{{ route('cms.section.store', ['section' => $section]) }}" method="POST"
       			class="flex flex-col w-[908px] items-start gap-12 top-[162px] left-[312px] cms-form-wrapper" enctype="multipart/form-data">

       			@csrf
            <input type="hidden" name="section" value="{{ $section }}">
			<input type="hidden" name="page_title" value="{{ $page }}">

       			<section class="flex flex-col items-start gap-5 relative self-stretch w-full flex-[0_0_auto]">

       				<div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative">
       					<div class="flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
       						
							<div class="flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto] cms-input-wrap">
       							<label for="header_title"
       								class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
       								Main Body Text Block
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

       @section('uniquePageScript')

       @endsection