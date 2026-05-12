<div id="deleteConfirmationModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-gray-900/50 bg-opacity-50 transition-opacity backdrop-blur-sm"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md p-6 border border-gray-200">
                
                <div class="absolute right-4 top-4">
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" id="closeMultiplLeadDeleteModal">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-2">
                    <h3 class="text-lg font-bold leading-6 text-gray-900 mb-2" id="modal-title">Delete Multiple Leads?</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to permanently delete these leads? 
                            <br>
                            All the associated deals will be deleted.
                        </p>
                    </div>
                </div>
                 <div class="mt-6 flex delete-artist-action flex items-center justify-center gap-4 w-full">
                    <button
                      type="button"
                      class="portal-btn-secondary-small"
                      style="width:100%;"
                      id="cancelMultiplLeadDelete"
                    >
                      Cancel
                    </button>
                    <button 
                      type="button"
                      class="portal-btn-primary-small" 
                      id="confirmMutipleDeleteLead"
                      style="width:100%;"
                      data-route="{{route('leads.bulk_delete')}}"
                    >
                      Delete Deal
                    </button>
                  </div>
            </div>
        </div>
    </div>
</div>