    <!-- Footer -->
    <footer class="marketplace-footer">
        <div class="footer-container">
            <div class="footer-content">
                <!-- Left Column: Branding and Social Media -->
                <div class="footer-left">
                    <!-- Logo -->
                    <div class="footer-logo">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 228 64" fill="none" class="logo-svg">
                            <g clip-path="url(#clip0_2125_856)">
                                <path d="M195.972 64H227.999V-0.00285339H195.972V64Z" fill="#161617" />
                                <path d="M176.917 31.9983L160.134 -0.00285339L144.121 31.5147L128.107 64H160.134H192.161L176.917 31.9983Z" fill="#161617" />
                                <path d="M48.8094 31.9983L32.0269 -0.00285339L16.0135 31.5147L0 64H32.0269H64.0537L48.8094 31.9983Z" fill="#161617" />
                                <path d="M99.893 -0.00285339H67.8665V64H99.893C117.581 64 131.92 49.6723 131.92 31.9983C131.92 14.3246 117.581 -0.00285339 99.893 -0.00285339Z" fill="#161617" />
                            </g>
                            <defs>
                                <clipPath id="clip0_2125_856">
                                    <rect width="228" height="64" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>

                    <!-- Tagline -->
                    <p class="footer-tagline">Home of MENA Cultural Transformation</p>

                    <!-- Follow Us Section -->
                    <div class="footer-follow">
                        <span class="follow-text">Follow us</span>
                        <a href="https://www.instagram.com/adai.arts" target="_blank" rel="noopener noreferrer" class="instagram-button">
                            <img src="{{ asset('assets/svg/instagram.svg') }}" alt="Instagram" class="instagram-icon">
                            <span>@ADAI. ART</span>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Newsletter and Navigation -->
                <div class="footer-right">
                    <!-- Newsletter Subscription -->
                    <div class="footer-newsletter">
                        <h3 class="newsletter-title">Subscribe to our newsletter</h3>
                        <form id="F_newsletter-form" class="newsletter-form" method="POST" action="{{ route('newsletter.subscription') }}">
                            @csrf
                            <div class="newsletter-input-wrapper ermsg">
                                <input
                                    type="email"
                                    name="email"
                                    id="newsletter-email"
                                    placeholder="Email Address"
                                    required
                                    class="newsletter-input @error('email') error @enderror"
                                    title="Please enter an email address"
                                    value="{{ old('email') }}">

                                @error('email')
                                <p class="text-red-500 text-sm mt-1 error">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" id="newsletter-form" class="newsletter-button directSubmit">Subscribe</button>
                        </form>
                    </div>

                    <!-- Navigation Links -->
                    <div class="footer-navigation">
                        <!-- Discover Column -->
                        <div class="nav-column">
                            <h4 class="nav-column-title">Discover</h4>
                            <ul class="nav-links">
                                <li><a href="{{ route('front.artwork.artworks-list') }}">ARTWORKS</a></li>
                                <li><a href="{{route('front.artist.artists-list')}}">ARTISTS</a></li>
                                <li><a href="{{route('front.gallery.index')}}">GALLERIES</a></li>
                                <li class="d-none"><a href="#">EVENTS</a></li>
                                <li class="d-none"><a href="#">EDITORIAL</a></li>
                                <li class="d-none"><a href="#">CULTURAL CONCIERGE</a></li>
                                <li class="d-none"><a href="#">AI ASSISTANT</a></li>
                            </ul>
                        </div>

                        <!-- Services Column -->
                        <div class="nav-column">
                            <h4 class="nav-column-title">Services</h4>
                            <ul class="nav-links">
                                <li><a href="{{ route('front.gallery-hub') }}">GALLERY HUB</a></li>
                                <li class="d-none"><a href="{{ url('artists-circle') }}">ARTISTS CIRCLE</a></li>
                                <li><a href="{{ route('front.art-consultancy') }}">ART CONSULTANCY</a></li>
                            </ul>
                        </div>

                        <!-- Company Column -->
                        <div class="nav-column">
                            <h4 class="nav-column-title">Company</h4>
                            <ul class="nav-links">
                                <li><a href="{{ route('front.about') }}">ABOUT</a></li>
                                <li><a href="{{ route('front.contact') }}">CONTACT US</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom: Legal and Copyright -->
            <div class="footer-bottom">
                <div class="footer-legal">
                    <a href="{{ url('terms') }}" class="legal-link">Terms of Use</a>
                    <a href="{{ url('privacy') }}" class="legal-link">Privacy Policy</a>
                </div>
                <p class="footer-copyright">Copyright © {{ date('Y') }} ADAI</p>
            </div>
        </div>
    </footer>