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
        <div class="flex flex-col gap-2">
          <h1 class="page-title">Contact Management</h1>
          <!-- <p>Please complete the fields below to create an accurate artist profile. Make sure all submitted information is accurate and properly formatted (including capitalization). Once submitted, any future edits will be reviewed and approved by our team.</p> -->
        </div>
      </div>
    </header>

    <div class="content-body">
      <div class="cms-form-wrapper flex items-center space-x-2 overflow-x-auto mb-6 p-1">
        <form action="{{ route('cms.store') }}" method="POST"
          class="cms-manage-form flex flex-col w-[908px] items-start gap-12 top-[162px] left-[312px] cms-manage-form" id='F_cmsContact'>
          @csrf
          @if(isset($artist))
            @method('PUT')
          @endif
          <section class="cms-form-section flex flex-col items-start gap-5 relative self-stretch w-full flex-[0_0_auto]">
            
            <div class="cms-form-input-wrapper flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative">
              <div class="cms-form-input flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                <div class="cms-form-label flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto]">
                  <label for="email_address"
                    class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                    Email Address
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <input type="text" required id="email_address" name="email_address" required aria-required="true"
                          placeholder="Email Address" value="{{ @$content['email_address'] }}" title="Please enter email address"
                          class="w-full text-gray-700 placeholder-gray-400 outline-none" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="cms-form-input-wrapper flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative">
              <div class="cms-form-input flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                <div class="cms-form-label flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto]">
                  <label for="phone_number"
                    class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                    PHONE NUMBER
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <input type="text" required id="phone_number" name="phone_number" required aria-required="true"
                          placeholder="Phone Number" value="{{ @$content['phone_number'] }}" title="Please enter phone number"
                          class="w-full text-gray-700 placeholder-gray-400 outline-none" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="cms-form-input-wrapper flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative">
              <div class="cms-form-input flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                <div class="cms-form-label flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto]">
                  <label for="office_location"
                    class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                    Office Location
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <input type="text" id="office_location" name="office_location" required aria-required="true"
                          placeholder="Office Location" value="{{ @$content['office_location'] }}" title="Please enter office location"
                          class="w-full text-gray-700 placeholder-gray-400 outline-none" />
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