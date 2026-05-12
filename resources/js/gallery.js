console.log("DEBUG: Gallery delete functionality script loaded");
let selectedGalleryId = null;
    let selectedType = null;

// OPEN MODAL - Simple handler like artist delete
$(document).on("click", ".deleteGallery", function (e) {
    console.log("DEBUG: Delete gallery button clicked");
    e.preventDefault();
    e.stopPropagation();
    selectedGalleryId = $(this).data("id");
    var selectedGalleryName = $(this).data("name");
    selectedType = $(this).data("type");

    console.log("DEBUG: Selected Gallery ID:", selectedGalleryId);
    console.log("DEBUG: Selected Gallery Name:", selectedGalleryName);

    if (!selectedGalleryId) {
        console.error("DEBUG: No gallery ID found in data-id attribute!");
    }

    // Close the dropdown
    $(this).closest(".action-menu-dropdown").slideUp(100);

    // Show delete modal
    console.log("DEBUG: Attempting to show delete modal");
    $('#galleryName').text(selectedGalleryName);
    const $modal = $("#deleteGalleryModal");
    const $background = $("#deleteGalleryModalBackground");

    console.log("DEBUG: Modal element found:", $modal.length > 0);
    console.log("DEBUG: Background element found:", $background.length > 0);

    $modal.show();
    $background.show();

    console.log("DEBUG: Modal display:", $modal.css("display"));
    console.log("DEBUG: Background display:", $background.css("display"));
});

// CLOSE MODAL - Use event delegation
$(document).on(
    "click",
    "#closeDeleteGalleryModal, #cancelDeleteGallery, button:has(#cancelDeleteGallery), #deleteGalleryModalBackground",
    function (e) {
        e.preventDefault();
        e.stopPropagation();
        console.log("DEBUG: Close delete gallery modal clicked");
        console.log("DEBUG: Clicked element:", this);
        $("#deleteGalleryModal").hide();
        $("#deleteGalleryModalBackground").hide();
        selectedGalleryId = null;
    }
);

// CONFIRM DELETE - Use event delegation (handle clicks on button or nested div)
$(document).on(
    "click",
    "#confirmDeleteGallery, button:has(#confirmDeleteGallery)",
    function (e) {
        e.preventDefault();
        e.stopPropagation();
        // Gallery Delete Functionality - Match artist delete pattern exactly

        console.log("DEBUG: Confirm delete gallery button clicked");
        console.log("DEBUG: Clicked element:", this);
        console.log("DEBUG: Selected Gallery ID:", selectedGalleryId);

        if (!selectedGalleryId) {
            console.error("DEBUG: No gallery ID selected!");
            alert("Error: No gallery ID selected!");
            return;
        }

        // Get the button element - could be clicked element or parent
        let btn = $(this).is("button") ? $(this) : $(this).closest("button");
        if (btn.length === 0) {
            btn = $("#confirmDeleteGallery").closest("button");
        }

        let originalText = btn.text();

        // Change to "Deleting..."
        btn.text("Deleting...");
        btn.prop("disabled", true);

        const deleteUrl = _publicPath + "/gallery/" + selectedGalleryId;
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        console.log("DEBUG: Delete URL:", deleteUrl);
        console.log("DEBUG: CSRF Token present:", !!csrfToken);
        console.log("DEBUG: Sending AJAX DELETE request...");

        $.ajax({
            url: deleteUrl,
            type: "DELETE",
            data: {
                _token: csrfToken,
            },
            success: function (response) {
                console.log(
                    "DEBUG: Delete gallery success response:",
                    response
                );

                // Refresh list
                if (typeof serach === "function") {
                    console.log(
                        "DEBUG: Calling serach() function to refresh list"
                    );
                    serach();
                } else {
                    if(selectedType == 'editPage'){
                    location.href = response["url"];
                    }else{
                    window.location.reload();

                    }
                }

                // Reset button text
                btn.text(originalText);
                btn.prop("disabled", false);

                // Close modal
                $("#deleteGalleryModal").hide();
                $("#deleteGalleryModalBackground").hide();
                selectedGalleryId = null;
            },
            error: function (xhr, status, error) {
                console.error("DEBUG: Delete gallery error:", error);
                console.error("DEBUG: Status:", status);
                console.error("DEBUG: Response:", xhr.responseText);
                console.error("DEBUG: Status code:", xhr.status);

                // Reset button text on failure
                btn.text(originalText);
                btn.prop("disabled", false);

                if (typeof Lobibox !== "undefined") {
                    Lobibox.notify("error", {
                        position: "top right",
                        rounded: false,
                        delay: 120000,
                        delayIndicator: true,
                        msg:
                            "Something went wrong! " +
                            (xhr.responseJSON?.message || error),
                    });
                } else {
                    alert("Something went wrong!");
                }
            },
        });
    }
);
$(document).on("click", ".viewprofile", function () {
    const selectedArtistId = $(this).data("artist-id");
    console.log(selectedArtistId);
    const selectedArtistShowRoute = $(this).data("artist-route");

    $("#profile-overlay").fadeIn(200).addClass("open");
    $.ajax({
        url: selectedArtistShowRoute,
        type: "get",
        beforeSend: function () {
            $(".ajaxloader").show();
        },
        success: function (data) {
            if (data["body"]) {
                $("#profile-result").html("");
                $("#profile-result").html(JSON.parse(data["body"]));
            }
            $(".ajaxloader").hide();
            $("#profile-overlay").fadeIn(200).addClass("open");
            updateGallerySlides(selectedArtistId);
            updateArtworkSwiper(selectedArtistId);
        },
        error: function () {
            // Reset button text on failure
        },
    });
});
$(document).on("click", ".rejectGallery", function () {
    const rejectedGallerytId = $(this).data("gallery-id");
    const rejectedGalleryName = $(this).data("gallery-name");
    $("#reject_id").val(rejectedGallerytId);
    $("#reject_gallery_name").html(rejectedGalleryName);
    $("#rejectArtistModalBackground").css("display", "flex");
    $("#rejectArtistModal").show();
});
$("#closeRejectModal, #cancelReject").on("click", function () {
    $("#rejectArtistModal").hide();
    $("#rejectArtistModalBackground").hide();
});
$("#rejectArtistModalBackground").on("click", function (e) {
    if (e.target === this) {
        $("#rejectArtistModal").hide();
        $("#rejectArtistModalBackground").hide();
    }
});
// Modal
const modal = document.getElementById("imageModal");
const artmodal = document.getElementById("artworksModal");

const closeGalleryBtn = document.getElementById("closeGalleryModal");
const closeArtworksBtn = document.getElementById("closeArtworksModal");
const modalEl = document.querySelector(".mySwiperModal");
const artworksModalEl = document.querySelector(".mySwiperArtworksModal");
const modalSwiperConfig={
    loop: true,
    pagination: {
        el: ".gallery-modal-pagination",
        clickable: true,
        dynamicBullets: false,
    },
    navigation: {
        nextEl: ".gallery-modal-next",
        prevEl: ".gallery-modal-prev",
    },
    keyboard: {
        enabled: true,
    },
    spaceBetween: 0,
    centeredSlides: true,
    slidesPerView: 1,
    slideToClickedSlide: false,
    centeredSlidesBounds: false,
    on: {
        init: function() {
            // Ensure slide width is set
            const slides = this.slides;
            slides.forEach(slide => {
                slide.style.width = '800px';
            });
            this.update();
        }
    }
};
const artworksSwiperConfig = {
    loop: true,
    pagination: {
        el: ".artworks-modal-pagination",
        clickable: true,
        dynamicBullets: false,
    },
    navigation: {
        nextEl: ".artworks-modal-next",
        prevEl: ".artworks-modal-prev",
    },
    keyboard: {
        enabled: true,
    },
    spaceBetween: 0,
    centeredSlides: true,
    slidesPerView: 1,
    slideToClickedSlide: false,
    centeredSlidesBounds: false,
    on: {
        init: function() {
            // Ensure slide width is set
            const slides = this.slides;
            slides.forEach(slide => {
                slide.style.width = '800px';
            });
            this.update();
        }
    }
};
// Initialize Gallery Space Photos modal swiper
let modalSwiper = null;
if (modalEl) {
    modalSwiper = new Swiper(".mySwiperModal", modalSwiperConfig );
}

// Initialize Artworks modal swiper
let artworksSwiper = null;
if (artworksModalEl) {
    artworksSwiper = new Swiper(".mySwiperArtworksModal", artworksSwiperConfig);
}

if (modalEl || artworksModalEl) { // Only run if elements exist

    // Function to close gallery modal
    function closeGalleryModal() {
        if (modal) {
            modal.classList.add("hidden");
        }
    }

    // Function to close artworks modal
    function closeArtworksModal() {
        if (artmodal) {
            artmodal.classList.add("hidden");
        }
    }
    $(document).on("click", ".openGalleryPhotosModal", function (e) {
        e.preventDefault();
        
        modal.classList.remove("hidden");
        if (modalSwiper) {
            modalSwiper.destroy(true, true);
            modalSwiper = null;
        }

        // 3️⃣ Init Swiper AFTER modal is visible
        requestAnimationFrame(() => {
            modalSwiper = new Swiper(".mySwiperModal", modalSwiperConfig);
        });

    });
    $(document).on("click", ".openArtworkPhotosModal", function (e) {
        e.preventDefault();

        // 1️⃣ Show modal first
        artmodal.classList.remove("hidden");

        // 2️⃣ Destroy old Swiper (if exists)
        if (artworksSwiper) {
            artworksSwiper.destroy(true, true);
            artworksSwiper = null;
        }

        // 3️⃣ Init Swiper AFTER modal is visible
        requestAnimationFrame(() => {
            artworksSwiper = new Swiper(".mySwiperArtworksModal", artworksSwiperConfig);
        });
    });

    // Close gallery modal button
    if (closeGalleryBtn) {
        closeGalleryBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            closeGalleryModal();
        });
    }

    // Close artworks modal button
    if (closeArtworksBtn) {
        closeArtworksBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            closeArtworksModal();
        });
    }

    // Close modals on outside click (click on backdrop)
    if (modal) {
        modal.addEventListener("click", function (e) {
            // If click is directly on the modal backdrop (not on the content)
            if (e.target === modal ||  e.target.closest(".swiper-slide")) {
                closeGalleryModal();
            }
            
        });
    }

    if (artmodal) {
        artmodal.addEventListener("click", function (e) {
            // If click is directly on the modal backdrop (not on the content)
            if (e.target === artmodal ||  e.target.closest(".swiper-slide")) {
                closeArtworksModal();
            }
        });
    }

    // Close modals on Escape key
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            if (modal && !modal.classList.contains("hidden")) {
                closeGalleryModal();
            }
            if (artmodal && !artmodal.classList.contains("hidden")) {
                closeArtworksModal();
            }
        }
    });
}
  $(document).on("click", "#approvedGalleryForm", function () {
        let approvedArtistId = $(this).data("id");
        $("#approved_id").val(approvedArtistId);
        $("#approvedArtistModalBackground").css("display", "flex");
        $("#approvedArtistModal").show();
    });
 $("#closeDeleteModal, #cancelDelete").on("click", function () {
        $("#deleteArtistModal").hide();
        $("#deleteArtistModalBackground").hide();
        $(".approvedArtistModal").hide();
        $(".approvedArtistModalBackground").hide();
    });
     $("#viewnoted").on("click", function () {
       $("#rejectArtistModalBackground").css("display", "flex");
       $("#rejectArtistModal").show();
    });
     $("#closeRejectModal, #cancelReject").on("click", function () {
        $("#rejectArtistModal").hide();
        $("#rejectArtistModalBackground").hide();
    });

    // Close modal when clicked outside of modal    
    $(document).on('click', function (event) {
        if ($(event.target).attr('id') === 'approvedArtistForm' || $(event.target).attr('id') === 'approvedGalleryForm') {
            console.log('approvedArtistForm button clicked');
        } else {
            $(".approvedArtistModal").hide();
            $(".approvedArtistModalBackground").hide();
        }
    });

    function updateArtworkSwiper(selectedId){
        let galleryId = selectedId;
        $.ajax({
            url: _publicPath + "/gallery/artwork-images/" + galleryId,
            type: "GET",
            success: function (response) {

                // response: { artworks: [{ id, primary_image }] }

                let wrapper = $(".mySwiperArtworksModal .swiper-wrapper");
                wrapper.html("");

                if (!response.artworks || response.artworks.length === 0) {
                    wrapper.append(`
                        <div class="swiper-slide flex items-center justify-center">
                            <p class="text-white text-xl">No artworks found.</p>
                        </div>
                    `);
                } else {
                    response.artworks.forEach(art => {
                        wrapper.append(`
                            <div class="swiper-slide flex items-center justify-center">
                                <div class="flex items-center justify-center"
                                    style="width:auto;height:600px;max-width:800px;margin:0 auto;top:50%;position:relative;transform:translateY(-50%);">
                                    <img src="${art.primary_image}"
                                        style="max-width:100%;max-height:100%;object-fit:contain;">
                                </div>
                            </div>
                        `);
                    });
                }

                // Update swiper
                if (artworksSwiper) {
                    artworksSwiper.update();
                    artworksSwiper.slideTo(0);
                }
            },
            error: function () {
                alert("Failed to load artworks.");
            }
        });
    }
    function updateGallerySlides(selectedId){
        let galleryId = selectedId;
        $.ajax({
            url: _publicPath + "/gallery/images/" + galleryId,
            type: "GET",
            success: function (res) {

                let wrapper = $(".mySwiperModal .swiper-wrapper");
                wrapper.html(""); // empty old

                // === FEATURED IMAGE ===
                if (res.featured) {
                    wrapper.append(`
                        <div class="swiper-slide flex flex-col items-center justify-center">
                            <div class="flex items-center justify-center"
                                style="width:auto;height:600px;max-width:800px;margin:0 auto;top:50%;position:relative;transform:translateY(-50%);">
                                <img src="${res.featured}"
                                    style="max-width:100%;max-height:100%;width:100%;height:auto;object-fit:contain;border-radius:4px;">
                            </div>
                        </div>
                    `);
                }

                // === OTHER IMAGES ===
                let others = Array.isArray(res.others) ? res.others : Object.values(res.others || {});

                others.forEach(img => {
                    wrapper.append(`
                        <div class="swiper-slide flex flex-col items-center justify-center">
                            <div class="flex items-center justify-center"
                                style="width:auto;height:600px;max-width:800px;margin:0 auto;top:50%;position:relative;transform:translateY(-50%);">
                                <img src="${img}"
                                    style="max-width:100%;max-height:100%;width:100%;height:auto;object-fit:contain;border-radius:4px;">
                            </div>
                        </div>
                    `);
                });

                // Update Swiper
                if (modalSwiper) {
                    modalSwiper.update();
                    modalSwiper.slideToLoop(0);
                }
            },
            error: function () {
                alert("Could not load gallery images.");
            }
        });
    }


if ($("#_license_block_html").length) {
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('certificate-upload');
        const displayPill = document.getElementById('file-display-pill');
        const fileNameLink = document.getElementById('file-name-link');
        const fileSizeText = document.getElementById('file-size-text');
        const btnText = document.getElementById('upload-btn-text');

        if (fileInput) {
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    // Validate file type (PDF only)
                    const allowedTypes = ['application/pdf'];
                    const maxSize = 5 * 1024 * 1024; // 5 MB in bytes
                    
                    if (!allowedTypes.includes(file.type) && !file.name.toLowerCase().endsWith('.pdf')) {
                        Lobibox.notify("error", {
                            position: "bottom right",
                            icon: 'fa fa-exclamation-circle',
                            msg: 'Please upload a PDF file only.',
                        });
                        fileInput.value = '';
                        return;
                    }
                    
                    // Validate file size (5 MB max)
                    if (file.size > maxSize) {
                        Lobibox.notify("error", {
                            position: "bottom right",
                            icon: 'fa fa-exclamation-circle',
                            msg: 'File size must be less than 5 MB.',
                        });
                        fileInput.value = '';
                        return;
                    }
                    
                    // 1. Update the filename text
                    fileNameLink.textContent = file.name;
                    
                    // 2. Remove the download link attributes for new files (since it's not on server yet)
                    fileNameLink.removeAttribute('href');
                    fileNameLink.removeAttribute('target');
                    fileNameLink.classList.remove('hover:text-blue-600', 'hover:underline');
                    fileNameLink.style.cursor = 'default';

                    // 3. Hide the old file size (since we can't easily calc formatted size in JS without logic)
                    if(fileSizeText) {
                        fileSizeText.style.display = 'none'; 
                    }

                    // 4. Show the gray pill container
                    displayPill.classList.remove('hidden');
                    displayPill.classList.add('flex');

                    // 5. Update button text
                    if (btnText) {
                        btnText.textContent = 'Update License';
                    }
                }
            });
        }
    });
}