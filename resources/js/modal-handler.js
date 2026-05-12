/**
 * Generic Modal Handler - CSP-compliant reusable modal controller
 *
 * Usage:
 * Add data attributes to your modal element:
 * - data-modal-auto-open="true" - Auto-open modal on page load
 * - data-modal-redirect-url="/path" - Redirect to URL when modal closes
 * - data-modal-close-btn="#closeBtnId" - Close button selector (default: looks for [data-modal-close] or .modal-close)
 * - data-modal-cancel-btn="#cancelBtnId" - Cancel button selector (default: looks for [data-modal-cancel] or .modal-cancel)
 * - data-modal-close-on-backdrop="true" - Close on backdrop click (default: true)
 * - data-modal-close-on-escape="true" - Close on ESC key (default: true)
 *
 * Example:
 * <div id="myModal"
 *      data-modal-auto-open="true"
 *      data-modal-redirect-url="/dashboard"
 *      data-modal-close-btn="#closeBtn"
 *      class="modal hidden">
 *   ...
 * </div>
 */

(function() {
    'use strict';

    /**
     * Initialize a modal with the given configuration
     * @param {HTMLElement} modal - The modal element
     */
    function initModal(modal) {
        if (!modal) return;

        // Get configuration from data attributes
        const config = {
            autoOpen: modal.dataset.modalAutoOpen === 'true',
            redirectUrl: modal.dataset.modalRedirectUrl || null,
            closeBtnSelector: modal.dataset.modalCloseBtn || '[data-modal-close], .modal-close',
            cancelBtnSelector: modal.dataset.modalCancelBtn || '[data-modal-cancel], .modal-cancel',
            closeOnBackdrop: modal.dataset.modalCloseOnBackdrop !== 'false',
            closeOnEscape: modal.dataset.modalCloseOnEscape !== 'false',
            hiddenClass: 'hidden',
            visibleClass: 'flex'
        };

        // Auto-open modal if configured
        if (config.autoOpen) {
            openModal(modal, config);
        }

        // Setup close handlers
        setupCloseHandlers(modal, config);
    }

    /**
     * Open the modal
     * @param {HTMLElement} modal - The modal element
     * @param {Object} config - Configuration object
     */
    function openModal(modal, config) {
        modal.classList.remove(config.hiddenClass);
        modal.classList.add(config.visibleClass);
        document.body.style.overflow = 'hidden';
    }

    /**
     * Close the modal
     * @param {HTMLElement} modal - The modal element
     * @param {Object} config - Configuration object
     */
    function closeModal(modal, config) {
        modal.classList.add(config.hiddenClass);
        modal.classList.remove(config.visibleClass);
        document.body.style.overflow = '';

        // Redirect if configured
        if (config.redirectUrl) {
            window.location.href = config.redirectUrl;
        }
    }

    /**
     * Setup all close event handlers
     * @param {HTMLElement} modal - The modal element
     * @param {Object} config - Configuration object
     */
    function setupCloseHandlers(modal, config) {
        // Close button handler
        const closeBtn = modal.querySelector(config.closeBtnSelector);
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeModal(modal, config);
            });
        }

        // Cancel button handler
        const cancelBtn = modal.querySelector(config.cancelBtnSelector);
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeModal(modal, config);
            });
        }

        // Backdrop click handler
        if (config.closeOnBackdrop) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal(modal, config);
                }
            });
        }

        // ESC key handler
        if (config.closeOnEscape) {
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains(config.hiddenClass)) {
                    closeModal(modal, config);
                }
            });
        }
    }

    /**
     * Initialize all modals with data-modal attribute on page load
     */
    function initAllModals() {
        // Find all modals with data-modal attribute or data-modal-auto-open
        const modals = document.querySelectorAll('[data-modal-auto-open], [data-modal-redirect-url]');

        modals.forEach(function(modal) {
            initModal(modal);
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllModals);
    } else {
        initAllModals();
    }

    // Export for programmatic use
    window.ModalHandler = {
        init: initModal,
        open: function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                const config = {
                    hiddenClass: 'hidden',
                    visibleClass: 'flex'
                };
                openModal(modal, config);
            }
        },
        close: function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                const config = {
                    hiddenClass: 'hidden',
                    visibleClass: 'flex',
                    redirectUrl: modal.dataset.modalRedirectUrl || null
                };
                closeModal(modal, config);
            }
        }
    };
})();

