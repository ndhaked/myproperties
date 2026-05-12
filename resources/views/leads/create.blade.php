<x-dashboard-layout>
   <main class="main-content add-new-artist-page" id="artist_create_update_form">
      <header class="content-header">
        <div style="display: flex; align-items: center; gap: 16px;">
          <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <div class="hamburger">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </button>
          <div class="flex flex-col gap-2">
              @if(isset($lead)) 
                <nav class="text-sm breadcrumbs flex items-center gap-1 mb-1 mt-1 edit_breadcrumb">
                  <a href="{{ route('leads.index') }}" class="text-violet-700 hover:underline breadcrumb edit_breadcrumb_anchor_first">Leads</a>
                  <span class="text-gray-500 mx-1 slash edit_breadcrumb_anchor_slash">/</span>
                  <span class="text-gray-500 breadcrumb edit_breadcrumb_anchor_name">Edit Lead</span>
                </nav>
                <h1 class="page-title">Update Lead</h1>
                <p class="page-description">Update the details below to maintain accurate lead information.</p>
              @else
                <nav class="text-sm breadcrumbs flex items-center gap-1 mb-1 mt-1 edit_breadcrumb">
                  <a href="{{ route('leads.index') }}" class="text-violet-700 hover:underline breadcrumb edit_breadcrumb_anchor_first">Leads</a>
                  <span class="text-gray-500 mx-1 slash edit_breadcrumb_anchor_slash">/</span>
                  <span class="text-gray-500 breadcrumb edit_breadcrumb_anchor_name">Add Lead</span>
                </nav>
                <h1 class="page-title">Add New Lead</h1>
                <p class="page-description">Please complete the fields below to create a new lead.</p>
              @endif
          </div>
        </div>
      </header>
      
      <div class="content-body">
        <div class="artist-update-form flex items-center space-x-2 mb-6 p-1 @if(@$lead->status == 'in-review' && auth()->user()->hasRole('gallery_admin')) form-locked @endif">
          <form 
              action="{{ isset($lead) ? route('leads.update', $lead->id) : route('leads.store') }}" 
              method="POST" 
              class="flex flex-col w-full max-w-4xl items-start gap-12" 
              id="F_saveArtist"
          >
              @csrf
              
              {{-- If we are editing, we spoof the PUT method --}}
              @if(isset($lead))
                  @method('PUT')
              @endif

          <section class="edit-artist-details flex flex-col items-start gap-5 relative self-stretch w-full flex-[0_0_auto]">
            <header
              class="edit-artist-header flex flex-col w-full items-start align-center justify-center gap-1.5 px-3 py-1 relative flex-[0_0_auto]"
            >
              <div class="flex items-center justify-end gap-2 relative self-stretch w-full flex-[0_0_auto]">
                <div class="flex-col items-start flex-1 grow flex relative">
                  <div class="items-center gap-4 self-stretch w-full flex-[0_0_auto] flex relative">
                    <div class="flex items-center gap-2 relative flex-1 self-stretch grow">
                      <h2
                        class="relative w-fit mt-[-1.00px] [font-family:'BR_Hendrix-SemiBold',Helvetica] text-lg tracking-[0] whitespace-nowrap"
                      >
                        LEAD DETAILS
                      </h2>
                    </div>
                  </div>
                </div>
              </div>
            </header>
            
            <div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative remove-extra-space-bottom">
            
            <div class="space-y-6 w-full">
                
                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Name & Email<span class="astric">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        
                        <div class="relative w-full ermsg">
                            <label for="full_name" class="portal-floating-label {{ old('full_name', $lead->full_name ?? '') ? 'active' : '' }}">Full Name</label>
                            <input type="text" id="full_name" name="full_name" required maxlength="50"
                                class="portal-input-field"
                                title="Please enter full name" 
                                value="{{ old('full_name', $lead->full_name ?? '') }}"
                                data-state="{{ old('full_name', $lead->full_name ?? '') ? 'filled' : 'placeholder' }}" />
                        </div>

                        <div class="relative w-full ermsg">
                            <label for="email" class="portal-floating-label {{ old('email', $lead->email ?? '') ? 'active' : '' }}">Email</label>
                            <input type="email" id="email" name="email"
                                class="portal-input-field"
                                value="{{ old('email', $lead->email ?? '') }}"
                                data-state="{{ old('email', $lead->email ?? '') ? 'filled' : 'placeholder' }}" />
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Location<span class="astric">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        
                        <div class="relative w-full ermsg">
                            <label for="country" class="portal-floating-label active">Country</label>
                            <select name="country" id="country" class="portal-input-field h-12" title="Please select country" required>
                                <option value="" >-- Select Country --</option>
                                @foreach($countriesLists ?? [] as $item)
                                    <option value="{{ $item->country }}" 
                                        {{ old('country', $lead->country ?? '') == $item->country ? 'selected' : '' }}>
                                        {{ $item->country }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative w-full ermsg">
                            <label for="city" class="portal-floating-label {{ old('city', $lead->city ?? '') ? 'active' : '' }}">City</label>
                            <input type="text" id="city" name="city"
                                class="portal-input-field"
                                title="Please enter city" 
                                value="{{ old('city', $lead->city ?? '') }}"
                                data-state="{{ old('city', $lead->city ?? '') ? 'filled' : 'placeholder' }}" />
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Mobile Number<span class="astric">*</span></label>
                    <div class="flex flex-col gap-2 w-full ermsg">
                        <div class="relative w-full">
                            <label for="mobile" class="portal-floating-label {{ old('mobile', $lead->mobile ?? '') ? 'active' : '' }}">Mobile</label>
                            <input type="text" id="mobile" name="mobile" required
                                class="portal-input-field numberonly onlynumber"
                                title="Please enter mobile number" 
                                value="{{ old('mobile', $lead->mobile ?? '') }}"
                                data-state="{{ old('mobile', $lead->mobile ?? '') ? 'filled' : 'placeholder' }}" />
                        </div>
                    </div>
                </div>

           

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Marketing Info</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div class="relative w-full">
                            <label for="campaign" class="portal-floating-label {{ old('campaign', $lead->campaign ?? '') ? 'active' : '' }}">Campaign</label>
                            <input type="text" id="campaign" name="campaign"
                                class="portal-input-field"
                                value="{{ old('campaign', $lead->campaign ?? '') }}"
                                data-state="{{ old('campaign', $lead->campaign ?? '') ? 'filled' : 'placeholder' }}" />
                        </div>
                        <div class="relative w-full">
                            <label for="project_id" class="portal-floating-label active">Project</label>
                            <select name="project_id" id="project_id" class="portal-input-field h-12">
                                <option value="">-- Select Project --</option>
                                @foreach($projects ?? [] as $item)
                                    <option value="{{ $item->id }}" {{ old('project_id', $lead->project_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                      <label class="text-gray-600 font-medium">Classification</label>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                          
                          <div class="relative w-full">
                              <label for="budget" class="portal-floating-label active">Budget</label>
                              <select name="budget" id="budget" class="portal-input-field h-12">
                                  <option value="">-- Select Budget --</option>
                                  @foreach($budgets ?? [] as $budgetOption)
                                      <option value="{{ $budgetOption }}" 
                                          {{ old('budget', $lead->budget ?? '') == $budgetOption ? 'selected' : '' }}>
                                          {{ $budgetOption }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="relative w-full">
                              <label for="lead_category_id" class="portal-floating-label active">Category</label>
                              <select name="lead_category_id" id="lead_category_id" class="portal-input-field h-12">
                                  <option value="">-- Select Category --</option>
                                  @foreach($leadCategories ?? [] as $item)
                                      <option value="{{ $item->id }}" {{ old('lead_category_id', $lead->lead_category_id ?? '') == $item->id ? 'selected' : '' }}>
                                          {{ $item->name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                          
                      </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Source & Medium</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div class="relative w-full">
                            <label for="source_id" class="portal-floating-label active">Source</label>
                            <select name="source_id" id="source_id" class="portal-input-field h-12">
                                <option value="">-- Select Source --</option>
                                @foreach($sources ?? [] as $item)
                                    <option value="{{ $item->id }}" {{ old('source_id', $lead->source_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative w-full" style="display: none;">
                            <label for="medium_id" class="portal-floating-label active">Medium</label>
                            <select name="medium_id" id="medium_id" class="portal-input-field h-12">
                                <option value="">-- Select Medium --</option>
                                @foreach($mediums ?? [] as $item)
                                    <option value="{{ $item->id }}" {{ old('medium_id', $lead->medium_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Purpose<span class="astric">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div class="relative w-full ermsg">
                            <label for="purpose_id" class="portal-floating-label active">Purpose</label>
                            <select name="purpose_id" id="purpose_id" class="portal-input-field h-12" required title="Please select purpose">
                                <option value="">-- Select Purpose --</option>
                                @foreach($purposes ?? [] as $item)
                                    <option value="{{ $item->id }}" {{ old('purpose_id', $lead->purpose_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative w-full ermsg">
                            <label for="purpose_type_id" class="portal-floating-label active">Purpose Type</label>
                            <select name="purpose_type_id" id="purpose_type_id" class="portal-input-field h-12" required title="Please select purpose type">
                                <option value="">-- Select Type --</option>
                                @foreach($purposeTypes ?? [] as $item)
                                    <option value="{{ $item->id }}" {{ old('purpose_type_id', $lead->purpose_type_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start mb-20">
                    <label class="text-gray-600 font-medium">Management</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div class="relative w-full">
                            <label for="assigned_agent_id" class="portal-floating-label active">Assigned Agent</label>
                            <select name="assigned_agent_id" id="assigned_agent_id" class="portal-input-field h-12">
                                <option value="">-- Select Agent --</option>
                                @foreach($agents ?? [] as $agent)
                                    <option value="{{ $agent->id }}" {{ old('assigned_agent_id', $lead->assigned_agent_id ?? '') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative w-full ermsg">
                            <label for="status" class="portal-floating-label active">Status</label>
                            <select name="status" id="status" class="portal-input-field h-12" required>
                                @php 
                                    $currentStatus = old('status', $lead->status ?? 'new');
                                    $statuses = ['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'lost' => 'Lost', 'closed' => 'Closed'];
                                @endphp
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ $currentStatus == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            </section>
        </div>
      </div>
        <footer class="artist-edit-footer right-2.5 bottom-0 h-16 flex bg-neutral-50 fixed admin-footer">
            <div class="artist-edit-footer-container flex flex-col gap-3 bg-neutral-50">
              <img
                class="artist-image-footer h-px -mt-px object-cover d-none"
                src="{{ asset('assets/svg/icons/line-40.svg') }}"
                alt=""
                aria-hidden="true"
              />
              <div class="artist-edit-footer-content flex h-10 relative items-center justify-between gap-14">
                @if(auth()->user()->hasRole('adai_admin') && (@$lead->status == 'in-review'))
                  <a href="javascript:;" data-id="{{@$lead->id}}" class="artist-edit-reject-link portal-btn-secondary-small" id="rejectArtist">
                      Reject with a Note
                  </a>
                @endif
                <p
                  class="artist-edit-status-message relative flex-1 [font-family:'BR_Hendrix-Regular',Helvetica] font-normal text-transparent text-sm tracking-[0] leading-[14px]"
                  role="status"
                  aria-live="polite"
                  style="display: none;"
                >
                  <span class="text-[#1c1b1b] leading-5">Not ready to submit for review.</span>
                  <span class="[font-family:'BR_Hendrix-Medium',Helvetica] font-medium text-[#1c1b1b] leading-[0.1px]"
                    >&nbsp;</span
                  >
                  <span class="text-[#ff5a5d] leading-5"></span>
                </p>
                <input type="hidden" name="submit_action" id="submit_action">
                <div class="artist-edit-buttons-container inline-flex items-center gap-4 relative flex-[0_0_auto]">
                    <button  
                      type="submit" name="submit" value="in-review"
                      class="artist-edit-approve-btn portal-btn-primary-small directSubmit"  
                      id="saveArtist"
                    >
                      @if(isset($lead)) 
                        Update
                      @else
                        Save
                      @endif
                    </button>
                </div>
              </div>
            </div>
        </footer>
        </form>
    </main>
@section('uniquePageScript')
@endsection
</x-dashboard-layout>