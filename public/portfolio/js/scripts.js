(function ($) {
	"use strict";

	// Page Loaded...
	$(document).ready(function () {

		/*==========  Tooltip  ==========*/
		$('.tool-tip').tooltip();

		/*==========  Progress Bars  ==========*/
		$('.progress-bar').on('inview', function (event, isInView) {
			if (isInView) {
				$(this).css('width', function () {
					return ($(this).attr('aria-valuenow') + '%');
				});
			}
		});
		$('.dial').on('inview', function (event, isInView) {
			if (isInView) {
				var $this = $(this);
				var myVal = $this.attr("value");
				var color = $this.attr("data-color");
				$this.knob({
					readOnly: true,
					width: 200,
					rotation: 'anticlockwise',
					thickness: .05,
					inputColor: '#232323',
					fgColor: color,
					bgColor: '#e8e8e8',
					'draw': function () {
						$(this.i).val(this.cv + '%')
					}
				});
				$({
					value: 0
				}).animate({
					value: myVal
				}, {
					duration: 1000,
					easing: 'swing',
					step: function () {
						$this.val(Math.ceil(this.value)).trigger('change');
					}
				});
			}
		});

		/*==========  Alerts  ==========*/
		$('.alert').on('inview', function (event, isInView) {
			if (isInView) {
				$(this).addClass('in');
			}
		});
		$(function () {
			$('[data-hide]').on('click', function () {
				$(this).closest('.' + $(this).attr('data-hide')).fadeOut();
			});
		});

		/*==========  Accordion  ==========*/
		$('.panel-heading a').on('click', function () {
			$('.panel-heading').removeClass('active');
			$(this).parents('.panel-heading').addClass('active');
		});

		/*==========  Responsive Navigation  ==========*/
		$('.main-nav').children().clone().appendTo('.responsive-nav');
		$('.responsive-menu-open').on('click', function (event) {
			event.preventDefault();
			$('body').addClass('no-scroll');
			$('.responsive-menu').addClass('open');
		});
		$('.responsive-menu-close').on('click', function (event) {
			event.preventDefault();
			$('body').removeClass('no-scroll');
			$('.responsive-menu').removeClass('open');
		});

		/*==========  Popup  ==========*/
		$('.share').on('click', function (event) {
			event.preventDefault();
			$('.popup').fadeToggle(250);
		});
		$('.slide-out-share').on('click', function (event) {
			event.preventDefault();
			$('.slide-out-popup').fadeToggle(250);
		});

		/*==========  Slide Out  ==========*/
		$('.header-action-button').on('click', function (event) {
			event.preventDefault();
			$('.slide-out-overlay').fadeIn(250);
			$('.slide-out').addClass('open');
		});
		$('.slide-out-close').on('click', function (event) {
			event.preventDefault();
			$('.slide-out-overlay').fadeOut(250);
			$('.slide-out').removeClass('open');
		});
		$('.slide-out-overlay').on('click', function (event) {
			event.preventDefault();
			$('.slide-out-overlay').fadeOut(250);
			$('.slide-out').removeClass('open');
		});

		/*==========  Search  ==========*/
		function positionSearch() {
			if ($(window).width() > $(window).height()) {
				var windowWidth = $(window).width();
				$('.search-overlay').css({ 'width': windowWidth * 2.5, 'height': windowWidth * 2.5 });
			} else {
				var windowHeight = $(window).height();
				$('.search-overlay').css({ 'width': windowHeight * 2.5, 'height': windowHeight * 2.5 });
			}
			var position = $('.header-open-search').offset();
			var height = $('.header-open-search').height();
			var width = $('.header-open-search').width();
			var top = position.top + height / 2 - $('.search-overlay').outerHeight() / 2;
			var left = position.left - width / 2 - $('.search-overlay').outerWidth() / 2;
			$('.search-overlay').css({ 'top': top, 'left': left });
		}
		positionSearch();
		$(window).on('resize', function () {
			positionSearch();
		});
		$('.open-search').on('click', function (event) {
			event.preventDefault();
			$('.search-overlay').addClass('scale');
			$('.search').addClass('open');
		});
		$('.search-close').on('click', function (event) {
			event.preventDefault();
			$('.search-overlay').removeClass('scale');
			$('.search').removeClass('open');
		});

		/*==========  Portfolio  ==========*/
		var $portfolioContainer = $('#portfolio').imagesLoaded(function () {
			$portfolioContainer.isotope({
				itemSelector: '.item',
				layoutMode: 'masonry'
			});
			horizontalSections();
		});
		$('#portfolio-filters').on('click', 'button', function () {
			var filterValue = $(this).attr('data-filter');
			$portfolioContainer.isotope({ filter: filterValue });
		});

		/*==========  Blog  ==========*/
		var $blogContainer = $('#blog-masonry').imagesLoaded(function () {
			$blogContainer.isotope({
				itemSelector: '.blog-post',
				layoutMode: 'masonry',
				masonry: {
					columnWidth: $blogContainer.find('.blog-grid-sizer')[0]
				}
			});
			horizontalSections();
		});

		/*==========  Horizontal Scroll  ==========*/
		var hash = window.location.hash;
		var url = 1;
		var count = $('.sections-wrapper section').length;
		if (location.hash) {
			setTimeout(function () {
				window.scrollTo(0, 0);
				$.smoothScroll({
					scrollTarget: location.hash,
					offset: 0,
					direction: 'left',
					scrollElement: $('div.sections'),
					speed: 600
				});
			}, 10);
			slide('link');
		}
		function horizontalSections() {
			var vWidth = $(window).width();
			var vheight = $(window).height();
			$('.sections-wrapper > section').css('width', vWidth);
			$('.sections-wrapper').css('width', vWidth * count).css('height', $('.sections-wrapper section.active').outerHeight());
		}
		function disableButtons(url) {
			if (url == count) {
				$('.section-nav a.forward').addClass('disabled');
				$('.section-nav a.backward').removeClass('disabled');
			} else if (url == 1) {
				$('.section-nav a.backward').addClass('disabled');
				$('.section-nav a.forward').removeClass('disabled');
			} else {
				$('.section-nav a.forward').removeClass('disabled');
				$('.section-nav a.backward').removeClass('disabled');
			}
		}
		function slide($type, $this) {
			if ($type == 'forward') {
				url = url + 1;
				$this.attr({ href: '#section' + url });
				$this.parent().attr({ class: 'section' + url });
			} else if ($type == 'backward') {
				url = url - 1;
				$this.attr({ href: '#section' + url });
				$this.parent().attr({ class: 'section' + url });
			} else if ($type == 'mainNav') {
				var href = $this.attr('href');
				if (href && href.indexOf('#section') !== -1) {
					var sectionNum = href.substring(href.indexOf('#section') + 8);
					sectionNum = parseInt(sectionNum, 10);
					if (!isNaN(sectionNum)) {
						url = sectionNum;
					}
				}
			} else if ($type == 'link') {
				var sectionNum = hash.replace(/[^\d.]/g, '');
				sectionNum = parseInt(sectionNum, 10);
				url = sectionNum;
			}
			$('.sections-wrapper section').removeClass('active');
			$('.main-nav .active').removeClass('active');
			$('.responsive-nav .active').removeClass('active');
			$('#section' + url).addClass('active');
			$('.main-nav a[href="#section' + url + '"]').parent().addClass('active');
			$('.responsive-nav a[href="#section' + url + '"]').parent().addClass('active');
			$('.sections-wrapper').css('height', $('.sections-wrapper section.active').outerHeight());
			disableButtons(url);
		}
		horizontalSections();
		$(window).on('resize', function () {
			horizontalSections();
		});
		disableButtons(url);
		$('.main-nav a, .responsive-nav a, .available').on('click', function (e) {
			var href = $(this).attr('href');
			if (href && href.indexOf('#section') !== -1) {
				var targetId = href.substring(href.indexOf('#'));
				if ($(targetId).length > 0) {
					// Home page horizontal scroll
					slide('mainNav', $(this));
					if (targetId !== '#section7') { // Keep contact sidebar working
						e.preventDefault();
						$.smoothScroll({
							scrollTarget: targetId,
							offset: 0,
							direction: 'left',
							scrollElement: $('div.sections'),
							speed: 600
						});
					}
				}
			}

			if ($(this).parent().hasClass('responsive-nav')) {
				$('body').removeClass('no-scroll');
				$('.responsive-menu').removeClass('open');
			}
		});

		$('.section-nav a.forward').on('click', function () {
			slide('forward', $(this));
		});
		$('.section-nav a.backward').on('click', function () {
			slide('backward', $(this));
		});

		$('a.forward, .section-nav a.backward').smoothScroll();

		/*==========  Testimonial Slider  ==========*/
		$('.testimonial-slider').owlCarousel({
			items: 1,
			autoplay: true,
			loop: true
		});

		/*==========  Portfolio Slider  ==========*/
		$('.portfolio-slider').owlCarousel({
			items: 1,
			autoplay: true,
			loop: true,
			nav: true,
			navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
			dots: false
		});

	});

	/*==========  Validate Email  ==========*/
	function validateEmail($validate_email) {
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if (!emailReg.test($validate_email)) {
			return false;
		} else {
			return true;
		}
	}

	/*==========  Contact Form  ==========*/
	/*$('.contact-form').on('submit', function() {
		var contactForm = $(this);
		contactForm.find('.contact-error').fadeOut();
		contactForm.find('.contact-success').fadeOut();
		contactForm.find('.contact-loading').fadeOut();
		contactForm.find('.contact-loading').fadeIn();
		if (validateEmail(contactForm.find('.contact-email').val()) && contactForm.find('.contact-email').val().length !== 0 && contactForm.find('.contact-name').val().length !== 0 && contactForm.find('.contact-message').val().length !== 0) {
			var action = contactForm.attr('action');
			$.ajax({
				type: "POST",
				url : action,
				data: {
					contact_name: contactForm.find('.contact-name').val(),
					contact_email: contactForm.find('.contact-email').val(),
					contact_message: contactForm.find('.contact-message').val()
				},
				success: function() {
					contactForm.find('.contact-loading').fadeOut();
					contactForm.find('.contact-success').find('.message').html('Success! Thanks for contacting us!');
					contactForm.find('.contact-success').fadeIn();
				},
				error: function() {
					contactForm.find('.contact-loading').fadeOut();
					contactForm.find('.contact-error').find('.message').html('Sorry, an error occurred.');
					contactForm.find('.contact-error').fadeIn();
				}
			});
		} else if (!validateEmail(contactForm.find('.contact-email').val()) && contactForm.find('.contact-email').val().length !== 0 && contactForm.find('.contact-name').val().length !== 0 && contactForm.find('.contact-message').val().length !== 0) {
			contactForm.find('.contact-error').fadeOut();
			contactForm.find('.contact-success').fadeOut();
			contactForm.find('.contact-loading').fadeOut();
			contactForm.find('.contact-error').find('.message').html('Please enter a valid email.');
			contactForm.find('.contact-error').fadeIn();
		} else {
			contactForm.find('.contact-error').fadeOut();
			contactForm.find('.contact-success').fadeOut();
			contactForm.find('.contact-loading').fadeOut();
			contactForm.find('.contact-error').find('.message').html('Please fill out all the fields.');
			contactForm.find('.contact-error').fadeIn();
		}
		return false;
	});*/

	// Helper function for email validation
	function validateEmail(email) {
		var re = /\S+@\S+\.\S+/;
		return re.test(email);
	}

	$('.contact-form').on('submit', function (e) {
		e.preventDefault(); // Stop normal form submission

		var contactForm = $(this);
		var submitBtn = contactForm.find('button[type="submit"]');

		// Reset alerts
		contactForm.find('.contact-error').fadeOut();
		contactForm.find('.contact-success').fadeOut();
		contactForm.find('.contact-loading').fadeIn();
		submitBtn.prop('disabled', true); // Prevent double click

		// Client-side Validation
		if (validateEmail(contactForm.find('.contact-email').val()) &&
			contactForm.find('.contact-name').val().length !== 0 &&
			contactForm.find('.contact-message').val().length !== 0) {

			var action = contactForm.attr('action');

			$.ajax({
				type: "POST",
				url: action,
				// Setup CSRF Token for Laravel
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					contact_name: contactForm.find('.contact-name').val(),
					contact_email: contactForm.find('.contact-email').val(),
					contact_message: contactForm.find('.contact-message').val(),
					contact_phone: contactForm.find('.contact-phone').val(),
				},
				success: function (response) {
					contactForm.find('.contact-loading').fadeOut();
					contactForm.find('.contact-success').find('.message').html(response.message);
					contactForm.find('.contact-success').fadeIn();

					// Clear inputs on success
					contactForm.find('input, textarea').val('');
					submitBtn.prop('disabled', false);
				},
				error: function (xhr) {
					contactForm.find('.contact-loading').fadeOut();
					var errorMsg = 'Sorry, an error occurred.';

					// If Laravel returns validation errors
					if (xhr.status === 422) {
						errorMsg = 'Please check your inputs.';
					}

					contactForm.find('.contact-error').find('.message').html(errorMsg);
					contactForm.find('.contact-error').fadeIn();
					submitBtn.prop('disabled', false);
				}
			});

		} else {
			// Validation Failed
			contactForm.find('.contact-loading').fadeOut();
			var msg = 'Please fill out all fields correctly.';
			if (!validateEmail(contactForm.find('.contact-email').val())) {
				msg = 'Please enter a valid email.';
			}
			contactForm.find('.contact-error').find('.message').html(msg);
			contactForm.find('.contact-error').fadeIn();
			submitBtn.prop('disabled', false);
		}
	});
	/*==========  Map  ==========*/
	var map;
	function initialize_map() {
		if ($('.map').length) {
			var myLatLng = new google.maps.LatLng(-37.814199, 144.961560);
			var mapOptions = {
				zoom: 15,
				center: myLatLng,
				scrollwheel: false,
				panControl: false,
				zoomControl: true,
				scaleControl: false,
				mapTypeControl: false,
				streetViewControl: false
			};
			map = new google.maps.Map(document.getElementById('map'), mapOptions);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				title: 'Envato',
				icon: './images/marker.png'
			});
		} else {
			return false;
		}
	}
	google.maps.event.addDomListener(window, 'load', initialize_map);

})(jQuery);