import './bootstrap';

import './jquery.validate.min';
import './jquery.form.js';
import './custom';
import './input-fields';
import './dashboard-script';
import './developer';

import './artwork';
import './gallery';

//import './ga4_dataLayer';
//import './marketplace_home';
//import './marketplace_search.js';
//import './artist-details';
//import './global_autocomplete_search';
//import './front_developer';

//import './marketplace_front';
// GSAP is loaded from CDN in layout file to avoid module conflicts

//import './animation';
//import './marketplace/market-animation';

// Marketplace page-specific scripts
// Each page (artwork-listing, gallery-details, artist-details) handles its own filtering
/*import './marketplace/artwork-listing';
import './marketplace/gallery-listing';
import './marketplace/gallery-details';*/


//import Alpine from 'alpinejs';

//window.Alpine = Alpine;

//Alpine.start();

$(document).ready(function () {
    // Initialize Summernote only if it's loaded and elements exist
    if (typeof $.fn.summernote !== 'undefined' && $('.summernote').length > 0) {
        $('.summernote').summernote({ height: 250 });
    }
});

