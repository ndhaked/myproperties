<footer class="bg-background border-t border-gray-200">
    <div class="mx-auto w-full max-w-full px-0">
        <div class="px-4 lg:px-8">
            <div class="pt-16 pb-6 lg:py-12">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between lg:mb-8">
                    <!-- Left Side -->
                    <div class="mb-6 lg:mb-0 flex flex-col gap-y-4 lg:gap-y-6">
                        <a href="{{ url('/') }}" data-cta="Footer Logo">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 228 64" fill="none" class="w-[110px] h-auto lg:w-[228px]">
                                <g clip-path="url(#clip0_2125_856)">
                                    <path d="M195.972 64H227.999V-0.00285339H195.972V64Z" fill="#161617"/>
                                    <path d="M176.917 31.9983L160.134 -0.00285339L144.121 31.5147L128.107 64H160.134H192.161L176.917 31.9983Z" fill="#161617"/>
                                    <path d="M48.8094 31.9983L32.0269 -0.00285339L16.0135 31.5147L0 64H32.0269H64.0537L48.8094 31.9983Z" fill="#161617"/>
                                    <path d="M99.893 -0.00285339H67.8665V64H99.893C117.581 64 131.92 49.6723 131.92 31.9983C131.92 14.3246 117.581 -0.00285339 99.893 -0.00285339Z" fill="#161617"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_2125_856">
                                        <rect width="228" height="64" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>

                        <p class="text-black text-sm font-normal lg:font-semibold">The Home of MENA Cultural Transformation</p>

                        <div class="flex items-center mb-3 lg:mb-0 gap-3">
                            <div class="text-md">Follow Us</div>
                            <a href="https://www.instagram.com/adai.arts" data-content-cta-section="Follow Us on Instagram" target="_blank" rel="noopener noreferrer" class="rounded-full flex items-center justify-center bg-muted-background hover:bg-primary-hover transition-colors cursor-pointer gap-2 px-4 py-3">
                                <img src="{{ asset('assets/svg/instagram.svg') }}" alt="Instagram" class="w-4 h-4">
                                <span class="font-semibold">@adai.arts</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                        <a href="{{ url('about') }}" data-cta="Footer About" class="text-black text-xs lg:text-sm font-normal font-sans hover:text-gray-600 transition-colors">ABOUT</a>
                        <a href="{{ url ('art-consultancy')}}" data-cta="Footer Art Consultancy" class="text-black text-xs lg:text-sm font-normal font-sans hover:text-gray-600 transition-colors">ART CONSULTANCY</a>
                        <a href="{{ url('marketplace') }}" data-cta="Footer Marketplace" class="text-black text-xs lg:text-sm font-normal font-sans hover:text-gray-600 transition-colors">MARKETPLACE & DATABASE</a>
                        <a href="{{ url('gallery-hub') }}" data-cta="Footer ADAI For Galleries" class="text-black text-xs lg:text-sm font-normal font-sans hover:text-gray-600 transition-colors">GALLERY HUB</a>

                        <a href="{{ url('contact') }}" data-cta="Footer Contact Us" class="text-black text-xs lg:text-sm font-normal font-sans hover:text-gray-600 transition-colors">CONTACT US</a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-300 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-center text-xs text-black">
                    <div class="w-full sm:w-auto flex justify-between sm:justify-start sm:space-x-6 mb-4 sm:mb-0">
                        <a href="{{ url('terms') }}"  data-cta="Footer Terms" class="hover:text-gray-600 transition-colors">Terms of Use</a>
                        <a href="{{ url('privacy') }}"  data-cta="Footer Privacy" class="hover:text-gray-600 transition-colors">Privacy Policy</a>
                    </div>
                    <p class="text-black">Copyright © {{date('Y')}} ADAI</p>
                </div>
            </div>
        </div>
    </div>
</footer>
@include('partials._subscription_model')
