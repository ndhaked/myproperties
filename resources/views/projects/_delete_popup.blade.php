<div class="delete-artist-popup fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center" id="deleteArtistModalBackground" style="display: none;">
  <div class="adai-modal-wrapper flex flex-col items-start gap-6 p-6 bg-white rounded-lg border border-solid border-[#dddddd] relative max-w-md w-full mx-4 my-4 deleteArtistModal-removespace" id="deleteArtistModal">
    <button
      type="button"
      class="absolute top-4 right-4 w-6 h-6 cursor-pointer bg-transparent border-0 p-0 z-10"
      id="closeDeleteModal"
    >
      <svg class="w-6 h-6 text-gray-400 hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
    
    <div class="delete-artist-content flex flex-col w-full items-start gap-6">
      <div class="flex flex-col items-start gap-3 w-full">
        <h1 class="font-semibold text-[#1c1b1b]" style="font-size: 20px;">
          Delete Project?
        </h1>
        <p class="text-sm text-[#505050] leading-relaxed">
          Are you sure you want to permanently delete this project?<br />All the associated deals will be deleted.
        </p>
      </div>
      
      <div class="delete-artist-action flex items-center justify-center gap-4 w-full">
        <button
          type="button"
          class="portal-btn-secondary-small"
          id="cancelDelete"
          style="width:100%;"
        >
          Cancel
        </button>
        <button 
          type="button"
          class="portal-btn-primary-small" 
          id="confirmDeleteProject"
          style="width:100%;"
        >
          Delete Project
        </button>
      </div>
    </div>
  </div>
</div>