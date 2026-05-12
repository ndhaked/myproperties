<!-- PROFILE PHOTO DELETE MODAL -->
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm"
     id="deleteProfilePhotoModalBg">

    <div class="adai-modal-wrapper flex flex-col gap-6 p-6 bg-white rounded-lg border border-[#dddddd] relative max-w-md w-full mx-4">

        <!-- Close -->
        <button type="button"
                class="absolute top-4 right-4 w-6 h-6 cursor-pointer bg-transparent border-0"
                id="closeDeleteProfilePhotoModal">
            <svg class="w-6 h-6 text-gray-400 hover:text-gray-700" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Content -->
        <div class="flex flex-col gap-4">
            <h2 class="text-[20px] font-semibold text-[#1C1B1B]">
                Delete Profile Photo?
            </h2>

            <p class="text-[14px] text-[#505050] leading-relaxed">
                Are you sure you want to delete your profile photo?
            </p>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="button"
                    class="flex-1 portal-btn-secondary-small"
                    id="cancelDeleteProfilePhoto">
                Cancel
            </button>

            <button type="button"
                    class="flex-1 portal-btn-primary-small"
                    id="confirmDeleteProfilePhoto">
                Delete Photo
            </button>
        </div>
    </div>
</div>
