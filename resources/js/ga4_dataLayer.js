/**
 * GA4 Unified Tracking: Navigation, Homepage & Subscriptions
 */
document.addEventListener('click', function (e) {
    const targetElement = e.target.closest('a, button');
    if (!targetElement) return;

    // Identify Containers
    const isHeader = targetElement.closest('#header, #mobile-menu, #sidebar-menu-container');
    const isFooter = targetElement.closest('footer');
    const isHomePage = window.location.pathname === '/' || window.location.pathname === '/home';
    const isAboutPage = window.location.pathname === '/about';
    const isMarketplaceAndDatabasePage = window.location.pathname === '/marketplace-and-database';
    const isArtConsultancyPage = window.location.pathname === '/art-consultancy';
    const isContactUsPage = window.location.pathname === '/contact';
    const isLoginPage = window.location.pathname === '/login';
    const isGalleryHubPage = window.location.pathname === '/gallery-hub';

    let eventName = '';

    // Initialize without quotes so it is a true undefined type
    let _ga4UserID = undefined;

    if (typeof _authUserId_ !== 'undefined' && _authUserId_) {
        _ga4UserID = _authUserId_;
    }
    
    // --- FALLBACK LOGIC ---
    // 1. data-cta attribute | 2. Visible text | 3. Fallback string
    let ctaText = targetElement.getAttribute('data-cta') || 
                  targetElement.innerText.trim() || 
                  "unlabeled_click";

    // 1. Navigation Journey (Header/Footer)
    if (isHeader) {
        eventName = 'header_nav_click';
    } else if (isFooter) {
        eventName = 'footer_nav_click';
    } 
    
    // 2. Home Page Journey (Only if on Home Page)
    else if (isHomePage) {
        if (targetElement.id === 'play-video-btn') {
            eventName = 'home_banner_click'; //
        } else if (targetElement.hasAttribute('data-home-section')) {
            eventName = 'home_cta_click'; //
            ctaText = targetElement.getAttribute('data-home-section'); // Priority for section name
        } else {
            ctaText = targetElement.getAttribute('data-same-section'); // Priority for section name
            eventName = 'home_section_click'; //
        }

        $("#ga4-subscription-type").val('');
        if(targetElement.hasAttribute('data-form-top-subscribe')){
            $("#ga4-subscription-type").val('data-form-top-subscribe');   
        }
        if(targetElement.hasAttribute('data-form-bottom-subscribe')){
            $("#ga4-subscription-type").val('data-form-bottom-subscribe');   
        }
    }
    else if (isAboutPage) {
        // Get the value of the attribute
        const sectionValue = targetElement.getAttribute('data-content-cta-section');

        if (sectionValue === 'View All Services') {
            eventName = 'about_services_cta_click'; 
            ctaText = sectionValue;
        } else if (sectionValue === 'Contact Us') {
            eventName = 'about_contact_redirect'; 
            ctaText = sectionValue;
        } else if (targetElement.hasAttribute('data-same-section')) {
            // Fallback for other sections
            ctaText = targetElement.getAttribute('data-same-section');
            eventName = 'about_services_cta_click'; 
        } else {
            // Default fallback if no specific data attribute is found
            ctaText = targetElement.innerText.trim();
            eventName = 'about_general_click';
        }
    }
    else if (isMarketplaceAndDatabasePage) {
        // Get the value of the attribute
        const clickValue = targetElement.getAttribute('data-content-click-section');

        if (clickValue === 'Subscribe For Updates') {
            eventName = 'marketplace_subscribe_click'; 
            ctaText = clickValue;
            $("#ga4-subscription-type").val('marketplace-and-database-data-form-bottom-subscribe');  
        }
        //alert(clickValue)
    }
    else if (isArtConsultancyPage) {
        // Get the value of the attribute
        const clickValue = targetElement.getAttribute('data-content-cta-section');

        if (clickValue === 'Schedule a Consultation') {
            eventName = 'art_schedule_consultation_redirect'; 
            ctaText = clickValue;
        }
    }
    else if (isContactUsPage) {
        // Get the value of the attribute
        const clickValue = targetElement.getAttribute('data-content-cta-section');
        if (clickValue === 'Follow Us on Instagram') {
            eventName = 'social_link_click'; 
            ctaText = clickValue;
        }
    }
    else if (isLoginPage) {
        // Get the value of the attribute
        const clickValue = targetElement.getAttribute('data-content-cta-section');
        if (clickValue === 'Login') {
            eventName = 'login_continue'; 
            ctaText = clickValue;
        }
    }
    else if (isGalleryHubPage) {
        // Get the value of the attribute
        const clickValue = targetElement.getAttribute('data-content-cta-section');
        if (clickValue === 'Top Become a Member') {
            eventName = 'galleries_top_member_redirect'; 
            ctaText = clickValue;
        } else if (clickValue === 'Bottom Become a Member') {
            eventName = 'galleries_bottom_member_redirect'; 
            ctaText = clickValue;
        }
        //alert(clickValue)
    }

    if (eventName) {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'event': eventName,
            'click_text': ctaText,
            'user_id': _ga4UserID,
            'page_path': window.location.pathname
        });
        console.log(`GA4 Tracked Click: ${eventName} | CTA: ${ctaText}`);
    }
}, true);

/**
 * Global function to be called ONLY on AJAX success
 */
window.trackGa4Subscription = function(formElement, error_type='') {
    if (!formElement) return;

    // Get the value from your hidden input
    const subscriptionType = formElement.querySelector('#ga4-subscription-type')?.value;

    let eventName = '';
    let ctaText = '';
    
    let _ga4UserID = 'undefined';
    if(_authUserId_){
        _ga4UserID = _authUserId_;
    }
    

    // 1. Check for Top Marketplace Subscription
    if (subscriptionType === "data-form-top-subscribe") {
        eventName = 'home_top_marketplace_subscribe'; //
        ctaText = 'Subscribe to Marketplace';
    } 
    // 2. Check for Bottom Updates Subscription
    else if (subscriptionType === "data-form-bottom-subscribe") {
        eventName = 'home_bottom_subscribe_submit'; //
        ctaText = 'Subscribe for Updates';
    }
    else if (subscriptionType === "marketplace-and-database-data-form-bottom-subscribe") {
        eventName = 'marketplace_subscribe_submit'; //
        ctaText = 'Subscribe for Updates';
    }
    else if (subscriptionType === "register-intrest") {
        eventName = 'registration_form_submit';
        ctaText = 'Registration Intrest Page';
        if(error_type){
            eventName = 'register_failed';
            ctaText = 'Registration Intrest Page';
        }
    }
    else if (subscriptionType === "contact-us-form") {
        eventName = 'contact_form_submit';
        ctaText = 'Contact Us Page';
        if(error_type){
            eventName = 'contact_us_failed';
        }
    }
    else if (subscriptionType === "login-form") {
        eventName = 'login_success';
        ctaText = 'Login Page';
        if(error_type){
            eventName = 'login_failed';
        }
    }
    
    if (eventName) {
        window.dataLayer = window.dataLayer || [];
        if(error_type){
            window.dataLayer.push({
                'event': eventName,
                'error_type': error_type,
                'click_text': ctaText,
                'user_id': _ga4UserID,
                'page_path': window.location.pathname
            });
        }else{
            window.dataLayer.push({
                'event': eventName,
                'click_text': ctaText,
                'user_id': _ga4UserID,
                'page_path': window.location.pathname
            });
        }
        console.log(`GA4 Tracked AJAX Success: ${eventName} | CTA: ${ctaText}`);
    }
};