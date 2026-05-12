<x-dashboard-layout>
  <main class="main-content cms-management-page">
   
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
          <h1 class="page-title">MarketPlace & Database</h1>
          <!-- <p>Please complete the fields below to create an accurate artist profile. Make sure all submitted information is accurate and properly formatted (including capitalization). Once submitted, any future edits will be reviewed and approved by our team.</p> -->
        </div>
      </div>
    </header>


    <div class="content-body">
      <div class="cms-form-wrapper flex items-center space-x-2 overflow-x-auto mb-6 p-1">
        <form action="{{ route('cms.marketplace-save') }}" method="POST"
          class="cms-manage-form flex flex-col w-[908px] items-start gap-12 top-[162px] left-[312px]" id='F_saveArtist' enctype="multipart/form-data">
          @csrf
          <section class="cms-form-section flex flex-col items-start gap-5 relative self-stretch w-full flex-[0_0_auto]">
            <!-- <header
              class="flex flex-col w-[908px] items-start justify-center gap-1.5 px-3 py-1 relative flex-[0_0_auto] bg-[#05f9e2] rounded-md">
              <div class="flex items-center justify-end gap-2 relative self-stretch w-full flex-[0_0_auto]">
                <div class="flex-col items-start flex-1 grow flex relative">
                  <div class="items-center gap-4 self-stretch w-full flex-[0_0_auto] flex relative">

                  </div>
                </div>
              </div>
            </header> -->
            <!-- <h2>Hero Section</h2> -->
            <div class="cms-form-input-wrapper flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative">
              <div class="cms-form-input flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                <div class="cms-form-label flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto]">
                  <label for="email_address"
                    class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                    Tag 1
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <input type="text" id="email_address" name="hero[tag_1]" required aria-required="true"
                          placeholder="Tag 1" value="{{ old('hero.tag_1', $data['hero']['tag_1']) }}"
                          title="Please enter email address"
                          class="relative flex items-center justify-center flex-1 mt-[-1.00px] font-typography-paragraph-base-regular font-[number:var(--typography-paragraph-base-regular-font-weight)] text-[#707070] text-[length:var(--typography-paragraph-base-regular-font-size)] tracking-[var(--typography-paragraph-base-regular-letter-spacing)] leading-[var(--typography-paragraph-base-regular-line-height)] [font-style:var(--typography-paragraph-base-regular-font-style)] [background:transparent] border-[none] p-0" />
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
                    Tag 2
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <input type="text" id="phone_number" name="hero[tag_2]" required aria-required="true"
                          placeholder="Tag 2" value="{{ old('hero.tag_2', $data['hero']['tag_2']) }}"
                          title="Please enter phone number"
                          class="relative flex items-center justify-center flex-1 mt-[-1.00px] font-typography-paragraph-base-regular font-[number:var(--typography-paragraph-base-regular-font-weight)] text-[#707070] text-[length:var(--typography-paragraph-base-regular-font-size)] tracking-[var(--typography-paragraph-base-regular-letter-spacing)] leading-[var(--typography-paragraph-base-regular-line-height)] [font-style:var(--typography-paragraph-base-regular-font-style)] [background:transparent] border-[none] p-0" />
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
                    Main Title
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <input type="text" id="main_title" name="hero[main_title]" required aria-required="true"
                          placeholder="main title" value="{{ old('hero.main_title', $data['hero']['main_title']) }}"
                          title="Please enter office location"
                          class="relative flex items-center justify-center flex-1 mt-[-1.00px] font-typography-paragraph-base-regular font-[number:var(--typography-paragraph-base-regular-font-weight)] text-[#707070] text-[length:var(--typography-paragraph-base-regular-font-size)] tracking-[var(--typography-paragraph-base-regular-letter-spacing)] leading-[var(--typography-paragraph-base-regular-line-height)] [font-style:var(--typography-paragraph-base-regular-font-style)] [background:transparent] border-[none] p-0" />
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
                    Subtitle
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <input type="text" id="office_location" name="hero[subtitle]" required aria-required="true"
                          placeholder="subtitle" value="{{ old('hero.subtitle', $data['hero']['subtitle']) }}"
                          title="Please enter office location"
                          class="relative flex items-center justify-center flex-1 mt-[-1.00px] font-typography-paragraph-base-regular font-[number:var(--typography-paragraph-base-regular-font-weight)] text-[#707070] text-[length:var(--typography-paragraph-base-regular-font-size)] tracking-[var(--typography-paragraph-base-regular-letter-spacing)] leading-[var(--typography-paragraph-base-regular-line-height)] [font-style:var(--typography-paragraph-base-regular-font-style)] [background:transparent] border-[none] p-0" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <h2>Overview</h2>
            <div class="cms-form-input-wrapper flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative">
              <div class="cms-form-input flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                <div class="cms-form-label flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto]">
                  <label for="office_location"
                    class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                    Section Label
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <input type="text" id="section_label" name="overview[section_label]" required
                          aria-required="true" placeholder="section label"
                          value="{{ old('overview.section_label', $data['overview']['section_label']) }}"
                          title="Please enter section label"
                          class="relative flex items-center justify-center flex-1 mt-[-1.00px] font-typography-paragraph-base-regular font-[number:var(--typography-paragraph-base-regular-font-weight)] text-[#707070] text-[length:var(--typography-paragraph-base-regular-font-size)] tracking-[var(--typography-paragraph-base-regular-letter-spacing)] leading-[var(--typography-paragraph-base-regular-line-height)] [font-style:var(--typography-paragraph-base-regular-font-style)] [background:transparent] border-[none] p-0" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="cms-form-input-wrapper  flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative">
              <div class="cms-form-input flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                <div class="cms-form-label flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto]">
                  <label for="headline"
                    class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                    Headline
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <input type="text" id="headline" name="overview[headline]" required aria-required="true"
                          placeholder="headline" value="{{ old('overview.headline', $data['overview']['headline']) }}"
                          title="Please enter office location"
                          class="relative flex items-center justify-center flex-1 mt-[-1.00px] font-typography-paragraph-base-regular font-[number:var(--typography-paragraph-base-regular-font-weight)] text-[#707070] text-[length:var(--typography-paragraph-base-regular-font-size)] tracking-[var(--typography-paragraph-base-regular-letter-spacing)] leading-[var(--typography-paragraph-base-regular-line-height)] [font-style:var(--typography-paragraph-base-regular-font-style)] [background:transparent] border-[none] p-0" />
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
                    Body Text
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <textarea id="body_text" name="overview[body_text]" required aria-required="true"
                          placeholder="body text" title="Please enter body text"
                          class="relative flex items-center justify-center flex-1 mt-[-1.00px] font-typography-paragraph-base-regular font-[number:var(--typography-paragraph-base-regular-font-weight)] text-[#707070] text-[length:var(--typography-paragraph-base-regular-font-size)] tracking-[var(--typography-paragraph-base-regular-letter-spacing)] leading-[var(--typography-paragraph-base-regular-line-height)] [font-style:var(--typography-paragraph-base-regular-font-style)] [background:transparent] border-[none] p-0 resize-none h-[120px]">{{ old('overview.body_text', $data['overview']['body_text']) }}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <h2>What sets us apart</h2>
            <div class="cms-form-input-wrapper flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative">
              <div class="cms-form-input flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                <div class="cms-form-label flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto]">
                  <label for="apart_section_title"
                    class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                    Section Title
                  </label>
                  <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                    <div
                      class="cms-input-details flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                      <div class="cms-input flex flex-col items-start justify-center relative flex-1 grow">
                        <input type="text" id="apart_section_title" name="apart[section_title]" required
                          aria-required="true" placeholder="section title"
                          value="{{ old('apart.section_title', $data['apart']['section_title']) }}"
                          title="Please enter section title"
                          class="relative flex items-center justify-center flex-1 mt-[-1.00px] font-typography-paragraph-base-regular font-[number:var(--typography-paragraph-base-regular-font-weight)] text-[#707070] text-[length:var(--typography-paragraph-base-regular-font-size)] tracking-[var(--typography-paragraph-base-regular-letter-spacing)] leading-[var(--typography-paragraph-base-regular-line-height)] [font-style:var(--typography-paragraph-base-regular-font-style)] [background:transparent] border-[none] p-0" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            @for ($i = 0; $i < 5; $i++)
                          <h4>Feature Item {{ $i + 1 }}</h4>

                          {{-- Title --}}
                          <div class="cms-form-input-wrapper flex-col items-start gap-8 self-stretch w-full flex relative">
                            <div class="cms-form-input flex flex-col items-start gap-4 relative self-stretch w-full">
                              <div class="cms-form-label flex items-start gap-8 self-stretch w-full">

                                <label for="apart_featured_{{ $i }}_title"
                                  class="w-[250px] font-typography-paragraph-base-medium text-[#505050]">
                                  Title
                                </label>

                                <div class="cms-form-input-details flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                                  <div class="cms-input-details flex items-center gap-2 px-3 py-1.5 w-full bg-white rounded border border-[#dddddd]">
                                    <div class="cms-input flex flex-col items-start justify-center flex-1 grow">
                                      <input type="text" id="apart_featured_{{ $i }}_title"
                                        name="apart[featured][{{ $i }}][section_title]" required aria-required="true"
                                        placeholder="section title"
                                        value="{{ old('apart.featured.' . $i . '.section_title', $data['apart']['featured'][$i]['section_title'] ?? '') }}"
                                        class="flex-1 font-typography-paragraph-base-regular text-[#707070] bg-transparent border-none p-0" />
                                    </div>
                                  </div>
                                </div>

                              </div>
                            </div>
                          </div>

                          {{-- Description --}}
                          <div class="cms-form-input-wrapper flex-col items-start gap-8 self-stretch w-full flex relative">
                            <div class="cms-form-input flex flex-col items-start gap-4 w-full">
                              <div class="cms-form-label flex items-start gap-8 w-full">

                                <label class="w-[250px] font-typography-paragraph-base-medium text-[#505050]">
                                  Description
                                </label>

                                <div class="cms-form-input-details flex flex-col items-start gap-2 flex-1 grow shadow-shadow-XS ermsg">
                                  <div class="cms-input-details flex items-center gap-2 px-3 py-1.5 w-full bg-white rounded border border-[#dddddd]">
                                    <div class="cms-input flex flex-col items-start justify-center flex-1 grow">
                                      <textarea name="apart[featured][{{ $i }}][description]" placeholder="Description" required
                                        class="flex-1 font-typography-paragraph-base-regular text-[#707070] bg-transparent border-none p-0 resize-none h-[120px]">{{ old('apart.featured.' . $i . '.description', $data['apart']['featured'][$i]['description'] ?? '') }}</textarea>
                                    </div>
                                  </div>
                                </div>

                              </div>
                            </div>
                          </div>

                          {{-- Image --}}
                          <div class="cms-form-input-wrapper flex-col items-start gap-8 self-stretch w-full flex relative">
                            <div class="cms-form-input flex flex-col items-start gap-4 w-full">
                              <div class="cms-form-label flex items-start gap-8 w-full">

                                <label class="w-[250px] font-typography-paragraph-base-medium text-[#505050]">
                                  Image
                                </label>

                                <div class="cms-form-input-details flex flex-col items-start gap-2 flex-1 grow shadow-shadow-XS ermsg">
                                  <div class="cms-input-details flex items-center gap-2 px-3 py-1.5 w-full bg-white rounded border border-[#dddddd]">
                                    <div class="cms-input flex flex-col items-start justify-center flex-1 grow">

                                      <input type="file" name="apart[featured][{{ $i }}][image]" accept="image/*"
                                        class="font-typography-paragraph-base-regular text-[#707070] bg-transparent border-none p-0" />

                                      <!-- @if (!empty($data['apart']['featured'][$i]['image']))
                                        <img src="{{ asset($data['apart']['featured'][$i]['image']) }}"
                                          class="mt-2 w-20 h-20 object-cover rounded" />
                                      @endif -->

                                    </div>
                                  </div>
                                </div>

                              </div>
                            </div>
                          </div>

                          {{-- Order --}}
                          <div class="cms-form-input-wrapper flex-col items-start gap-8 self-stretch w-full flex relative">
                            <div class="cms-form-input flex flex-col items-start gap-4 w-full">
                              <div class="cms-form-label flex items-start gap-8 w-full">

                                <label class="w-[250px] font-typography-paragraph-base-medium text-[#505050]">
                                  Order
                                </label>

                                <div class="cms-form-input-details flex flex-col items-start gap-2 flex-1 grow shadow-shadow-XS ermsg">
                                  <div class="cms-input-details flex items-center gap-2 px-3 py-1.5 w-full bg-white rounded border border-[#dddddd]">
                                    <div class="cms-input flex flex-col items-start justify-center flex-1 grow">

                                      <input type="number" name="apart[featured][{{ $i }}][order]" placeholder="Order number"
                                        value="{{ old('apart.featured.' . $i . '.order', $data['apart']['featured'][$i]['order'] ?? '') }}"
                                        class="flex-1 font-typography-paragraph-base-regular text-[#707070] bg-transparent border-none p-0" />

                                    </div>
                                  </div>
                                </div>

                              </div>
                            </div>
                          </div>

            @endfor



          
          </section>
          @include('cms.partials.footer')
        </form>
      </div>
    </div>

  </main>
  @section('uniquePageScript')
  @endsection
</x-dashboard-layout>