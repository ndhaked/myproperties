// $(document).ready(function () {

//     // Focus handling
//     $('.tw-input').on('focus', function () {
//         $(this).addClass('focus:ring-2 focus:ring-black/30 focus:border-black');
//     });

//     // Remove focus ring on blur
//     $('.tw-input').on('blur', function () {
//         $(this).removeClass('focus:ring-2 focus:ring-black/30 focus:border-black');
//     });

//     // Error on empty
//     $('.tw-input').on('change keyup', function () {
//         const val = $(this).val().trim();
//         const group = $(this).closest('.form-group');

//         if (val === "") {
//             $(this).addClass('border-red-500 ring-2 ring-red-200');
//             group.find('.error-text').removeClass('hidden');
//         } else {
//             $(this).removeClass('border-red-500 ring-2 ring-red-200');
//             group.find('.error-text').addClass('hidden');
//         }
//     });
// });


// ///---
// $(document).ready(function () {

//     /** FLOAT LABEL : focus → go up */
//     $(".float-input").on("focus", function () {
//         const group = $(this).closest(".float-group");
//         group.addClass("is-focused");

//         group.find(".float-label").addClass("label-up");
//     });

//     /** FLOAT LABEL : blur → only go down if input is empty */
//     $(".float-input").on("blur", function () {
//         const group = $(this).closest(".float-group");
//         const val = $(this).val().trim();

//         if (val === "") {
//             group.removeClass("is-focused");
//             group.find(".float-label").removeClass("label-up");
//         }
//     });

//     /** ERROR state logic */
//     $(".float-input").on("keyup change", function () {
//         const group = $(this).closest(".float-group");
//         const val = $(this).val().trim();

//         if (val === "") {
//             group.addClass("has-error");
//             group.find(".float-error").removeClass("hidden");

//             $(this).addClass("border-red-500 ring-2 ring-red-200");
//         } else {
//             group.removeClass("has-error");
//             group.find(".float-error").addClass("hidden");

//             $(this).removeClass("border-red-500 ring-2 ring-red-200");
//         }
//     });

//     /** DISABLE input */
//     window.disableField = function (selector) {
//         $(selector).find(".float-input")
//             .prop("disabled", true)
//             .addClass("bg-gray-100 border-gray-200 cursor-not-allowed opacity-60");

//         $(selector).find(".float-label").addClass("text-gray-400");
//     };

// });


// $(document).on("input focus blur", ".float-input", function () {
//     const parent = $(this).closest(".float-group");
//     const label = parent.find(".float-label");

//     if ($(this).val().trim() !== "" || $(this).is(":focus")) {
//         label.addClass("text-xs -top-2 left-3 px-1 bg-white text-black");
//     } else {
//         label.removeClass("text-xs -top-2 left-3 px-1 bg-white text-black");
//     }
// });


// Wait for jQuery to be available and DOM to be ready
(function() {
  function initFloatingLabels() {
    if (typeof jQuery === 'undefined' || typeof window.$ === 'undefined') {
      // jQuery not loaded yet, try again
      setTimeout(initFloatingLabels, 50);
      return;
    }

    const $ = window.jQuery || window.$;

    // Function to update label state
    function updateLabelState($field) {
      const $label = $field.siblings('label.portal-floating-label');
      if ($label.length === 0) return;
      
      const fieldValue = $field.val();
      const hasValue = (fieldValue !== null && fieldValue !== undefined && fieldValue.toString().trim() !== '') || ($field.is('select') && $field[0].selectedIndex > -1);
      const isFocused = $field.is(':focus');
      const isError = $field.hasClass('error');

      // Add or remove active class based on focus or value
      if (hasValue || isFocused) {
        $label.addClass('active');
        if (isError) {
          $label.addClass('error-active');
        } else {
          $label.removeClass('error-active');
        }
      } else {
        $label.removeClass('active');
        if (isError) {
          $label.removeClass('error-active');
        }
      }
    }

    // Function to initialize floating label for a field
    function initializeFloatingLabel($field) {
      const state = $field.data('state');
      
      // Set initial states
      if (state === 'focused') {
        $field.focus();
      }
      
      // Update label state based on current value and focus
      updateLabelState($field);
    }

    // Function to handle floating label animation
    function handleFloatingLabel(e) {
      const $field = $(this);
      updateLabelState($field);
    }

    // Use event delegation for dynamically added elements
    $(document).on('focus focusin blur focusout input change keyup', 'input[data-state]:not([disabled]), select[data-state]:not([disabled]), textarea[data-state]:not([disabled])', handleFloatingLabel);
    
    // Also handle clicks on the input container to ensure focus works
    $(document).on('click', '.portal-input-field', function() {
      const $field = $(this);
      if (!$field.is(':focus')) {
        $field.focus();
      }
      updateLabelState($field);
    });

    // Initialize states on page load
    $(function() {
      $('input[data-state], select[data-state], textarea[data-state]').each(function() {
        initializeFloatingLabel($(this));
      });

      // Prevent focused state from losing focus on page load
      setTimeout(function() {
        $('input[data-state="focused"], select[data-state="focused"], textarea[data-state="focused"]').focus();
      }, 100);
    });

    // Expose function to initialize newly added fields (for AJAX-loaded content)
    window.initializeFloatingLabels = function(container) {
      const $container = container ? $(container) : $(document);
      $container.find('input[data-state], select[data-state], textarea[data-state]').each(function() {
        initializeFloatingLabel($(this));
      });
    };
  }

  // Start initialization when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFloatingLabels);
  } else {
    // DOM already loaded, but wait a bit for jQuery
    setTimeout(initFloatingLabels, 100);
  }
})();