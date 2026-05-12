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
            <h1 class="page-title">Core Service Management</h1>
        </div>
    </div>
</header> -->
<div class="content-body">
    <input type="hidden" id="coreserviceroute" name="coreserviceroute"
        value="{{ route('cms.item-block.partial', ['type' => $section]) }}">


    <div class="flex items-center space-x-2 overflow-x-auto mb-6 p-1">
        <form action="{{ route('cms.section.store', ['section' => $section]) }}" method="POST"
            class="flex flex-col w-[908px] items-start gap-12 top-[162px] left-[312px] cms-form-wrapper" id='F_cmsForm' enctype="multipart/form-data">

            @csrf
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="page_title" value="{{ $page }}">


            <section class="flex flex-col items-start gap-5 relative self-stretch w-full flex-[0_0_auto]">

                @if(in_array('header_title', $fields))

                <div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative main-cms-input">
                    <div class="flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                        <div class="flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto] cms-input-wrap">
                            <label for="header_title"
                                class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                Header title
                            </label>
                            <div class="flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                                <div
                                    class="flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                                    <div class="flex flex-col items-start justify-center relative flex-1 grow">
                                        <input type="text" required id="header_title" name="main_content[header_title]" required aria-required="true"
                                            placeholder="Header Title" value="{{ @$content['main_content']['header_title'] }}" title="Please enter header title"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if(in_array('subtitle', $fields))
                <div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative main-cms-input">
                    <div class="flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                        <div class="flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto] cms-input-wrap">
                            <label for="subtitle"
                                class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                Subtitle
                            </label>
                            <div class="flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                                <div
                                    class="flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                                    <div class="flex flex-col items-start justify-center relative flex-1 grow">
                                        <input type="text" required id="subtitle" name="main_content[subtitle]" required aria-required="true"
                                            placeholder="Subtitle" value="{{ @$content['main_content']['subtitle'] }}" title="Please enter subtitle"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if(in_array('main_image', $fields))
                <div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative main-cms-input">
                    <div class="flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                        <div class="flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto] cms-input-wrap">
                            <label for="subtitle"
                                class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                Background Image
                            </label>
                            <div class="flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg cms-form-input-details">
                                <div
                                    class="flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                                    <div class="flex flex-col items-start justify-center relative flex-1 grow">

                                        <input type="file"
                                            name="main_image"
                                            id="main_image"
                                            accept="image/*"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />

                                    </div>

                                </div>
                            </div>
                            @if(!empty($content['main_image']))
                            <input type="hidden"
                                name="old_image"
                                value="{{ $content['main_image'] ?? '' }}">
                            <img src="{{ azure_url($content['main_image']) }}"
                                class="w-32 rounded mt-2"
                                alt="Main Page Image">
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @if(in_array('background_alt_text', $fields))
                <div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative main-cms-input">
                    <div class="flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                        <div class="flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto] cms-input-wrap">
                            <label for="subtitle"
                                class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                Alt Text
                            </label>
                            <div class="flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg cms-form-input-details">
                                <div
                                    class="flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                                    <div class="flex flex-col items-start justify-center relative flex-1 grow">

                                        <input type="text" required id="background_alt_text" name="main_content[background_alt_text]" required aria-required="true"
                                            placeholder="Alt Text" value="{{ @$content['main_content']['background_alt_text'] }}" title="Please enter alt text"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif
                @if(in_array('cta1_label', $fields))
                <div class="flex-col items-start gap-8 w-full flex relative main-cms-input">
                    <div class="flex flex-col gap-4 w-full">
                        <div class="flex items-start gap-8 w-full cms-input-wrap">

                            <label class="w-[250px] text-[#505050]">CTA 1 Label</label>

                            <div class="flex flex-col gap-2 flex-1 shadow-shadow-XS ermsg">
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded border border-[#dddddd]">
                                    <div class="flex flex-col justify-center flex-1">

                                        <input type="text" id="cta1_label" required
                                            name="main_content[cta1_label]"
                                            placeholder="Label"
                                            value="{{ @$content['main_content']['cta1_label'] }}"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif

                <!-- CTA 1 URL -->
                @if(in_array('cta1_url', $fields))
                <div class="flex-col items-start gap-8 w-full flex relative main-cms-input">
                    <div class="flex flex-col gap-4 w-full">
                        <div class="flex items-start gap-8 w-full cms-input-wrap">

                            <label class="w-[250px] text-[#505050]">CTA 1 URL</label>

                            <div class="flex flex-col gap-2 flex-1 shadow-shadow-XS ermsg">
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded border border-[#dddddd]">
                                    <div class="flex flex-col justify-center flex-1">

                                        <input type="text" id="cta1_url" required
                                            name="main_content[cta1_url]"
                                            placeholder="Url"
                                            value="{{ @$content['main_content']['cta1_url'] }}"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif


                <!-- CTA 2 LABEL -->
                @if(in_array('cta2_label', $fields))
                <div class="flex-col items-start gap-8 w-full flex relative main-cms-input">
                    <div class="flex flex-col gap-4 w-full">
                        <div class="flex items-start gap-8 w-full cms-input-wrap">

                            <label class="w-[250px] text-[#505050]">CTA 2 Label</label>

                            <div class="flex flex-col gap-2 flex-1 shadow-shadow-XS ermsg">
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded border border-[#dddddd]">
                                    <div class="flex flex-col justify-center flex-1">

                                        <input type="text" id="cta2_label" required
                                            name="main_content[cta2_label]"
                                            placeholder="Label"
                                            value="{{ @$content['main_content']['cta2_label'] }}"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif

                <!-- CTA 2 URL -->
                @if(in_array('cta2_url', $fields))
                <div class="flex-col items-start gap-8 w-full flex relative main-cms-input">
                    <div class="flex flex-col gap-4 w-full">
                        <div class="flex items-start gap-8 w-full cms-input-wrap">

                            <label class="w-[250px] text-[#505050]">CTA 2 URL</label>

                            <div class="flex flex-col gap-2 flex-1 shadow-shadow-XS ermsg">
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded border border-[#dddddd]">
                                    <div class="flex flex-col justify-center flex-1">

                                        <input type="text" id="cta2_url" required
                                            name="main_content[cta2_url]"
                                            placeholder="Url"
                                            value="{{ @$content['main_content']['cta2_url'] }}"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif
                @if(in_array('label1', $fields))
                <div class="flex-col items-start gap-8 w-full flex relative main-cms-input">
                    <div class="flex flex-col gap-4 w-full">
                        <div class="flex items-start gap-8 w-full cms-input-wrap">

                            <label class="w-[250px] text-[#505050]">Label 1</label>

                            <div class="flex flex-col gap-2 flex-1 shadow-shadow-XS ermsg">
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded border border-[#dddddd]">
                                    <div class="flex flex-col justify-center flex-1">

                                        <input type="text" id="label1" required
                                            name="main_content[label1]"
                                            placeholder="Label"
                                            value="{{ @$content['main_content']['label1'] }}"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif
                @if(in_array('label2', $fields))

                <div class="flex-col items-start gap-8 w-full flex relative main-cms-input">
                    <div class="flex flex-col gap-4 w-full">
                        <div class="flex items-start gap-8 w-full cms-input-wrap">

                            <label class="w-[250px] text-[#505050]">Label 2</label>

                            <div class="flex flex-col gap-2 flex-1 shadow-shadow-XS ermsg">
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded border border-[#dddddd]">
                                    <div class="flex flex-col justify-center flex-1">

                                        <input type="text" id="label2" required
                                            name="main_content[label2]"
                                            placeholder="Label"
                                            value="{{ @$content['main_content']['label2'] }}"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif
                @if(in_array('cta_button_label', $fields))
                <div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative main-cms-input">
                    <div class="flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                        <div class="flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto] cms-input-wrap">
                            <label for="subtitle"
                                class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                CTA button label

                            </label>
                            <div class="flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                                <div
                                    class="flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                                    <div class="flex flex-col items-start justify-center relative flex-1 grow">
                                        <input type="text" required id="cta_button_label" name="main_content[cta_button_label]" required aria-required="true"
                                            placeholder="CTA Button Label" value="{{ @$content['main_content']['cta_button_label'] }}" title="Please enter Cta Button Label"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if(in_array('cta_button_link', $fields))

                <div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative main-cms-input">
                    <div class="flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                        <div class="flex items-start gap-8 self-stretch w-full relative flex-[0_0_auto] cms-input-wrap">
                            <label for="subtitle"
                                class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                CTA button link

                            </label>
                            <div class="flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS ermsg">
                                <div
                                    class="flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                                    <div class="flex flex-col items-start justify-center relative flex-1 grow">
                                        <input type="text" required id="cta_button_link" name="main_content[cta_button_link]" required aria-required="true"
                                            placeholder="CTA Button Link" value="{{ @$content['main_content']['cta_button_link'] }}" title="Please enter Cta Button Label"
                                            class="w-full text-gray-700 placeholder-gray-400 outline-none" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if(in_array('add_items', $fields))
                <button type="button" id="addItemsBlock" class="px-4 py-2 bg-black text-white rounded">
                    + Add Items
                </button>
                @endif
            </section>
            <div id="serviceWrapper1" class="flex flex-col gap-8 w-full">

                @if(!empty($content))

                @foreach(@$content['services'] ?? [] as $index => $service)
                @include('cms.partials.item_with_row', ['i' => $index, 'service' => $service])
                @endforeach
                @endif

            </div>

            @include('cms.partials.footer')

        </form>
    </div>
</div>
@section('uniquePageScript')
@endsection