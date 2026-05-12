$(document).ready(function() {
  // Mobile menu toggle
  $('.mobile-menu-toggle').on('click', function() {
    $(this).toggleClass('active');
    $('.sidebar').toggleClass('open');
    $('#sidebarBackdrop').toggleClass('active');

    const isOpen = $('.sidebar').hasClass('open');
    $(this).attr('aria-expanded', isOpen);
  });

  // Close sidebar when clicking close button
  $('#sidebarCloseButton').on('click', function() {
    $('.sidebar').removeClass('open');
    $('.mobile-menu-toggle').removeClass('active').attr('aria-expanded', 'false');
    $('#sidebarBackdrop').removeClass('active');
  });

  // Close sidebar when clicking backdrop
  $('#sidebarBackdrop').on('click', function() {
    $('.sidebar').removeClass('open');
    $('.mobile-menu-toggle').removeClass('active').attr('aria-expanded', 'false');
    $('#sidebarBackdrop').removeClass('active');
  });

  // Close mobile menu when clicking outside
  $(document).on('click', function(e) {
    if ($(window).width() <= 768 && $('.sidebar').hasClass('open')) {
      if (!$(e.target).closest('.sidebar, .mobile-menu-toggle, .sidebar-close-button, .sidebar-backdrop').length) {
        $('.sidebar').removeClass('open');
        $('.mobile-menu-toggle').removeClass('active').attr('aria-expanded', 'false');
        $('#sidebarBackdrop').removeClass('active');
      }
    }
  });

  // Navigation item click handling (excluding dropdowns and submenu items)
  $(document).on('click', '.nav-item:not(.nav-dropdown):not(.nav-submenu-item)', function(e) {
    if ($(this).is('a') && $(this).attr('href') === '#') {
      e.preventDefault();
    }

    // Remove active class from all nav items
    $('.nav-item').removeClass('active');

    // Add active class to clicked item
    $(this).addClass('active');

    // Close mobile menu on navigation
    if ($(window).width() <= 768) {
      $('.sidebar').removeClass('open');
      $('.mobile-menu-toggle').removeClass('active').attr('aria-expanded', 'false');
      $('#sidebarBackdrop').removeClass('active');
    }
  });

  // Initially hide all submenus, except profile submenu on mobile
  $('.nav-submenu').hide();
  
  // Show profile submenu on mobile by default
  if ($(window).width() <= 768) {
    $('.sidebar-footer .nav-dropdown-wrapper .nav-submenu').show();
  }

  // Dropdown toggle
  $(document).on('click', '.nav-dropdown', function(e) {
    e.preventDefault();
    e.stopPropagation();

    const $dropdown = $(this);
    const $wrapper = $dropdown.closest('.nav-dropdown-wrapper');
    const $submenu = $wrapper.find('.nav-submenu');
    const $chevron = $dropdown.find('.chevron');

    // Skip dropdown toggle for profile section on mobile
    if ($(window).width() <= 768 && $wrapper.closest('.sidebar-footer').length) {
      return;
    }

    if ($submenu.length) {
      // Toggle submenu with animation
      $submenu.slideToggle(200);

      // Toggle dropdown open state
      $dropdown.toggleClass('open');
      $wrapper.toggleClass('open');
      const isOpen = $dropdown.hasClass('open');
      $dropdown.attr('aria-expanded', isOpen);

      // Rotate chevron icon
      if (isOpen) {
        $chevron.css('transform', 'rotate(180deg)');
      } else {
        $chevron.css('transform', 'rotate(0deg)');
      }

      // Close other dropdowns
      $('.nav-dropdown').not($dropdown).removeClass('open').attr('aria-expanded', 'false');
      $('.nav-dropdown-wrapper').not($wrapper).removeClass('open');
      $('.nav-submenu').not($submenu).slideUp(200);
      $('.nav-dropdown').not($dropdown).find('.chevron').css('transform', 'rotate(0deg)');
    }
  });

  // Handle submenu item clicks
  $(document).on('click', '.nav-submenu-item', function(e) {
    // Add active class to the submenu item
    $('.nav-submenu-item').removeClass('active');
    $(this).addClass('active');

    // Close mobile menu on navigation
    if ($(window).width() <= 768) {
      $('.sidebar').removeClass('open');
      $('.mobile-menu-toggle').removeClass('active').attr('aria-expanded', 'false');
      $('#sidebarBackdrop').removeClass('active');
    }
  });

  // User profile dropdown
  $(document).on('click', '.user-profile', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).toggleClass('open');
    const isOpen = $(this).hasClass('open');
    $(this).attr('aria-expanded', isOpen);
  });

  // Handle window resize
  let resizeTimer;
  $(window).on('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      if ($(window).width() > 768) {
        $('.sidebar').removeClass('open');
        $('.mobile-menu-toggle').removeClass('active').attr('aria-expanded', 'false');
        $('#sidebarBackdrop').removeClass('active');
        // Hide profile submenu on desktop (unless open)
        $('.sidebar-footer .nav-dropdown-wrapper .nav-submenu').not(':visible').hide();
      } else {
        // Show profile submenu on mobile
        $('.sidebar-footer .nav-dropdown-wrapper .nav-submenu').show();
      }
    }, 250);
  });

  // Smooth scroll for anchor links (only for same-page anchors)
  $(document).on('click', 'a[href^="#"]', function(e) {
    const target = $(this).attr('href');
    if (target === '#' || target === '#!') {
      e.preventDefault();
      return;
    }
    // Allow normal anchor link behavior for valid anchors
  });

  // Card hover effects
  $('.card').hover(
    function() {
      $(this).addClass('hovered');
    },
    function() {
      $(this).removeClass('hovered');
    }
  );

  // Button click effects
  $('.btn').on('mousedown', function() {
    $(this).addClass('pressed');
  }).on('mouseup mouseleave', function() {
    $(this).removeClass('pressed');
  });

  // Keyboard navigation
  $('.nav-item, .btn, .user-profile').on('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      $(this).click();
    }
  });

  // Add loading state to buttons (only for buttons with data-loading attribute)
  $(document).on('click', '.btn[data-loading]', function(e) {
    const $btn = $(this);
    if (!$btn.hasClass('loading') && !$btn.prop('disabled')) {
      $btn.addClass('loading').prop('disabled', true);
      setTimeout(function() {
        $btn.removeClass('loading').prop('disabled', false);
      }, 1000);
    }
  });
});
