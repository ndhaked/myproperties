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
              @if(isset($deal)) 
                <nav class="text-sm breadcrumbs flex items-center gap-1 mb-1 mt-1 edit_breadcrumb">
                  <a href="{{ route('deals.index') }}" class="text-violet-700 hover:underline breadcrumb edit_breadcrumb_anchor_first">Deals</a>
                  <span class="text-gray-500 mx-1 slash edit_breadcrumb_anchor_slash">/</span>
                  <span class="text-gray-500 breadcrumb edit_breadcrumb_anchor_name">Edit Deal</span>
                </nav>
                <h1 class="page-title">Update Deal</h1>
                <p class="page-description">Update the details below to maintain accurate deal information.</p>
              @else
                <nav class="text-sm breadcrumbs flex items-center gap-1 mb-1 mt-1 edit_breadcrumb">
                  <a href="{{ route('deals.index') }}" class="text-violet-700 hover:underline breadcrumb edit_breadcrumb_anchor_first">Deals</a>
                  <span class="text-gray-500 mx-1 slash edit_breadcrumb_anchor_slash">/</span>
                  <span class="text-gray-500 breadcrumb edit_breadcrumb_anchor_name">Add Deal</span>
                </nav>
                <h1 class="page-title">Add New Deal</h1>
                <p class="page-description">Please complete the fields below to create a new deal.</p>
              @endif
          </div>
        </div>
      </header>
      
      <div class="content-body">
        <div class="artist-update-form flex items-center space-x-2 mb-6 p-1">
          <form 
              action="{{ isset($deal) ? route('deals.update', $deal->id) : route('deals.store') }}" 
              method="POST" 
              class="flex flex-col w-full max-w-4xl items-start gap-12" 
              id="F_saveArtist"
          >
              @csrf
              
              {{-- If we are editing, we spoof the PUT method --}}
              @if(isset($deal))
                  @method('PUT')
              @endif

          <section class="edit-artist-details flex flex-col items-start gap-5 relative self-stretch w-full flex-[0_0_auto]">
            <header class="edit-artist-header flex flex-col w-full items-start align-center justify-center gap-1.5 px-3 py-1 relative flex-[0_0_auto]">
              <div class="flex items-center justify-end gap-2 relative self-stretch w-full flex-[0_0_auto]">
                <div class="flex-col items-start flex-1 grow flex relative">
                  <div class="items-center gap-4 self-stretch w-full flex-[0_0_auto] flex relative">
                    <div class="flex items-center gap-2 relative flex-1 self-stretch grow">
                      <h2 class="relative w-fit mt-[-1.00px] [font-family:'BR_Hendrix-SemiBold',Helvetica] text-lg tracking-[0] whitespace-nowrap">
                        DEAL DETAILS
                      </h2>
                    </div>
                  </div>
                </div>
              </div>
            </header>
            
            <div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative remove-extra-space-bottom">
            
            <div class="space-y-6 w-full">
                
                <h3 class="text-gray-800 font-semibold border-b pb-2">Client Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Client Name<span class="astric">*</span></label>
                    <div class="flex flex-col gap-2 w-full ermsg">
                        <div class="relative w-full">
                            <label for="client_name" class="portal-floating-label {{ old('client_name', $deal->client_name ?? '') ? 'active' : '' }}">Client Name</label>
                            <input type="text" id="client_name" name="client_name" class="portal-input-field"
                                value="{{ old('client_name', $deal->client_name ?? '') }}" required  title="Please enter client name" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Contact Details<span class="astric">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div class="relative w-full ermsg">
                            <label for="client_mobile_no" class="portal-floating-label {{ old('client_mobile_no', $deal->client_mobile_no ?? '') ? 'active' : '' }}">Mobile</label>
                            <input type="text" id="client_mobile_no" name="client_mobile_no" class="portal-input-field numberonly" required  title="Please enter mobile"
                                value="{{ old('client_mobile_no', $deal->client_mobile_no ?? '') }}" />
                        </div>
                        <div class="relative w-full ermsg">
                            <label for="client_email" class="portal-floating-label {{ old('client_email', $deal->client_email ?? '') ? 'active' : '' }}">Email</label>
                            <input type="email" id="client_email" name="client_email" class="portal-input-field"
                                value="{{ old('client_email', $deal->client_email ?? '') }}" required  title="Please enter email" />
                        </div>
                    </div>
                </div>

                <h3 class="text-gray-800 font-semibold border-b pb-2 mt-6">Project & Classification</h3>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Project Details<span class="astric">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div class="relative w-full ermsg">
                            <label for="project_id" class="portal-floating-label active">Project<span class="astric">*</span></label>
                            <select name="project_id" id="project_id" class="portal-input-field h-12" required title="Please select project">
                                <option value="">-- Select Project --</option>
                                @foreach($projects ?? [] as $item)
                                    <option value="{{ $item->id }}" {{ old('project_id', $deal->project_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative w-full ermsg">
                            <label for="project_type_id" class="portal-floating-label active">Project Type<span class="astric">*</span></label>
                            <select name="project_type_id" id="project_type_id" class="portal-input-field h-12" required title="Please select project type">
                                <option value="">-- Select Type --</option>
                                @foreach($projectTypes ?? [] as $item)
                                    <option value="{{ $item->id }}" {{ old('project_type_id', $deal->project_type_id ?? '') == $item->id ? 'selected' : '' }}>
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
                                    <option value="{{ $item->id }}" {{ old('purpose_id', $deal->purpose_id ?? '') == $item->id ? 'selected' : '' }}>
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
                                    <option value="{{ $item->id }}" {{ old('purpose_type_id', $deal->purpose_type_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <h3 class="text-gray-800 font-semibold border-b pb-2 mt-6">General Deal Info</h3>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Details<span class="astric">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div class="relative w-full ermsg">
                            <label for="developer_name" class="portal-floating-label {{ old('developer_name', $deal->developer_name ?? '') ? 'active' : '' }}">Developer Name</label>
                            <input type="text" id="developer_name" name="developer_name" class="portal-input-field"
                                value="{{ old('developer_name', $deal->developer_name ?? '') }}" required title="Please enter developer date" />
                        </div>
                        <div class="relative w-full ermsg">
                            <label for="deal_date" class="portal-floating-label active">Deal Date</label>
                            <input type="date" id="deal_date" name="deal_date" class="portal-input-field"
                                value="{{ old('deal_date', $deal->deal_date ?? '') }}" required title="Please enter deal date" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Invoice & Source<span class="astric">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div class="relative w-full ermsg">
                            <label for="invoice_no" class="portal-floating-label {{ old('invoice_no', $deal->invoice_no ?? '') ? 'active' : '' }}">Invoice No</label>
                            <input type="text" id="invoice_no" name="invoice_no" class="portal-input-field"
                                value="{{ old('invoice_no', $deal->invoice_no ?? '') }}" required title="Please enter invoice number" />
                        </div>
                        <div class="relative w-full ermsg">
                            <label for="source_id" class="portal-floating-label active">Source</label>
                            <select name="source_id" id="source_id" class="portal-input-field h-12" required title="Please select sourse">
                                <option value="">-- Select Source --</option>
                                @foreach($sources ?? [] as $item)
                                    <option value="{{ $item->id }}" {{ old('source_id', $deal->source_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <h3 class="text-gray-800 font-semibold border-b pb-2 mt-6">Financials</h3>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Price & Commission</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="relative w-full">
                            <label for="price" class="portal-floating-label {{ old('price', $deal->price ?? '') ? 'active' : '' }}">Total Price</label>
                            <input type="number" step="0.01" id="price" name="price" class="portal-input-field"
                                value="{{ old('price', $deal->price ?? '') }}" />
                        </div>
                        <div class="relative w-full">
                            <label for="commission_percentage" class="portal-floating-label {{ old('commission_percentage', $deal->commission_percentage ?? '') ? 'active' : '' }}">Comm. %</label>
                            <input type="number" step="0.01" id="commission_percentage" name="commission_percentage" class="portal-input-field"
                                value="{{ old('commission_percentage', $deal->commission_percentage ?? '') }}" />
                        </div>
                        <div class="relative w-full">
                            <label for="commission_amount" class="portal-floating-label {{ old('commission_amount', $deal->commission_amount ?? '') ? 'active' : '' }}">Comm. Amount</label>
                            <input type="number" step="0.01" id="commission_amount" name="commission_amount" class="portal-input-field"
                                value="{{ old('commission_amount', $deal->commission_amount ?? '') }}" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">VAT Details</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="relative w-full">
                            <label for="vat_percentage" class="portal-floating-label {{ old('vat_percentage', $deal->vat_percentage ?? '') ? 'active' : '' }}">VAT %</label>
                            <input type="number" step="0.01" id="vat_percentage" name="vat_percentage" class="portal-input-field"
                                value="{{ old('vat_percentage', $deal->vat_percentage ?? '') }}" />
                        </div>
                        <div class="relative w-full">
                            <label for="vat_amount" class="portal-floating-label {{ old('vat_amount', $deal->vat_amount ?? '') ? 'active' : '' }}">VAT Amount</label>
                            <input type="number" step="0.01" id="vat_amount" name="vat_amount" class="portal-input-field"
                                value="{{ old('vat_amount', $deal->vat_amount ?? '') }}" />
                        </div>
                        <div class="relative w-full">
                            <label for="total_invoice" class="portal-floating-label {{ old('total_invoice', $deal->total_invoice ?? '') ? 'active' : '' }}">Total Invoice</label>
                            <input type="number" step="0.01" id="total_invoice" name="total_invoice" class="portal-input-field"
                                value="{{ old('total_invoice', $deal->total_invoice ?? '') }}" />
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">VAT Status</label>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="vat_paid" name="vat_paid" value="1" 
                            class="h-5 w-5 text-violet-600 focus:ring-violet-500 border-gray-300 rounded"
                            {{ old('vat_paid', $deal->vat_paid ?? 0) ? 'checked' : '' }}>
                        <label for="vat_paid" class="text-gray-700">VAT Paid</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Down Payment</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="relative w-full">
                            <label for="down_payment_percentage" class="portal-floating-label {{ old('down_payment_percentage', $deal->down_payment_percentage ?? '') ? 'active' : '' }}">DP %</label>
                            <input type="number" step="0.01" id="down_payment_percentage" name="down_payment_percentage" class="portal-input-field"
                                value="{{ old('down_payment_percentage', $deal->down_payment_percentage ?? '') }}" />
                        </div>
                        <div class="relative w-full">
                            <label for="down_payment_amount" class="portal-floating-label {{ old('down_payment_amount', $deal->down_payment_amount ?? '') ? 'active' : '' }}">DP Amount</label>
                            <input type="number" step="0.01" id="down_payment_amount" name="down_payment_amount" class="portal-input-field"
                                value="{{ old('down_payment_amount', $deal->down_payment_amount ?? '') }}" />
                        </div>
                        <div class="relative w-full">
                            <label for="remaining_down_payment" class="portal-floating-label {{ old('remaining_down_payment', $deal->remaining_down_payment ?? '') ? 'active' : '' }}">Remaining DP</label>
                            <input type="number" step="0.01" id="remaining_down_payment" name="remaining_down_payment" class="portal-input-field"
                                value="{{ old('remaining_down_payment', $deal->remaining_down_payment ?? '') }}" />
                        </div>
                    </div>
                </div>

                <h3 class="text-gray-800 font-semibold border-b pb-2 mt-6">Internal Commissions</h3>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Agent</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="relative w-full">
                            <label for="agent_id" class="portal-floating-label active">Select Agent</label>
                            <select name="agent_id" id="agent_id" class="portal-input-field h-12">
                                <option value="">-- Agent --</option>
                                @foreach($agents ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ old('agent_id', $deal->agent_id ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative w-full">
                            <label for="agent_commission_percentage" class="portal-floating-label active">Agent %</label>
                            <input type="number" step="0.01" name="agent_commission_percentage" class="portal-input-field"
                                value="{{ old('agent_commission_percentage', $deal->agent_commission_percentage ?? '') }}" />
                        </div>
                        <div class="relative w-full">
                            <label for="agent_commission_amount" class="portal-floating-label active">Agent Amount</label>
                            <input type="number" step="0.01" name="agent_commission_amount" class="portal-input-field"
                                value="{{ old('agent_commission_amount', $deal->agent_commission_amount ?? '') }}" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Leader</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="relative w-full">
                            <label for="leader_id" class="portal-floating-label active">Select Leader</label>
                            <select name="leader_id" id="leader_id" class="portal-input-field h-12">
                                <option value="">-- Leader --</option>
                                @foreach($leaders ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ old('leader_id', $deal->leader_id ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative w-full">
                            <label for="leader_commission_percentage" class="portal-floating-label active">Leader %</label>
                            <input type="number" step="0.01" name="leader_commission_percentage" class="portal-input-field"
                                value="{{ old('leader_commission_percentage', $deal->leader_commission_percentage ?? '') }}" />
                        </div>
                        <div class="relative w-full">
                            <label for="leader_commission_amount" class="portal-floating-label active">Leader Amount</label>
                            <input type="number" step="0.01" name="leader_commission_amount" class="portal-input-field"
                                value="{{ old('leader_commission_amount', $deal->leader_commission_amount ?? '') }}" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Sales Director</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="relative w-full">
                            <label for="sales_director_id" class="portal-floating-label active">Select Director</label>
                            <select name="sales_director_id" id="sales_director_id" class="portal-input-field h-12">
                                <option value="">-- Director --</option>
                                @foreach($admins ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ old('sales_director_id', $deal->sales_director_id ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative w-full">
                            <label for="sales_director_commission_percentage" class="portal-floating-label active">Director %</label>
                            <input type="number" step="0.01" name="sales_director_commission_percentage" class="portal-input-field"
                                value="{{ old('sales_director_commission_percentage', $deal->sales_director_commission_percentage ?? '') }}" />
                        </div>
                        <div class="relative w-full">
                            <label for="sales_director_commission_amount" class="portal-floating-label active">Director Amount</label>
                            <input type="number" step="0.01" name="sales_director_commission_amount" class="portal-input-field"
                                value="{{ old('sales_director_commission_amount', $deal->sales_director_commission_amount ?? '') }}" />
                        </div>
                    </div>
                </div>

                <h3 class="text-gray-800 font-semibold border-b pb-2 mt-6">Status & Notes</h3>

                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start mb-20">
                    <label class="text-gray-600 font-medium">Finalize<span class="astric">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div class="relative w-full ermsg">
                            <label for="deal_status_id" class="portal-floating-label active">Deal Status<span class="astric">*</span></label>
                            <select name="deal_status_id" id="deal_status_id" class="portal-input-field h-12" required title="Please select deal status">
                                <option value="">-- Select Status --</option>
                                @foreach($dealStatuses ?? [] as $status)
                                    <option value="{{ $status->id }}" {{ old('deal_status_id', $deal->deal_status_id ?? '') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative w-full">
                            <label for="notes" class="portal-floating-label {{ old('notes', $deal->notes ?? '') ? 'active' : '' }}">Notes</label>
                            <textarea id="notes" name="notes" class="portal-input-field h-24 pt-3">{{ old('notes', $deal->notes ?? '') }}</textarea>
                        </div>
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
                @if(auth()->user()->hasRole('adai_admin') && (@$deal->status == 'in-review'))
                  <a href="javascript:;" data-id="{{@$deal->id}}" class="artist-edit-reject-link portal-btn-secondary-small" id="rejectArtist">
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
                      @if(isset($deal)) 
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