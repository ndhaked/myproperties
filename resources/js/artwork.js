if ($("#main-artwork-list").length) {
    let selectedArtistId = null; 
    // OPEN MODAL
    $(document).on("click", ".restoreArtist", function () {
        selectedArtistId = $(this).data("id");
        $("#restoreArtistModal").css("display", "flex");
        $("#restoreArtistModalBackground").css("display", "flex");
    });

    // CLOSE MODAL
    $("#closeRestoreModal, #cancelRestore").on("click", function () {
        $("#restoreArtistModal").hide();
        $("#restoreArtistModalBackground").hide();
    });

    // Only close if clicking directly on the background, not on the modal content
    $(document).on("click", "#restoreArtistModalBackground", function (e) {
        if (e.target === this) {
            $("#restoreArtistModal").hide();
            $("#restoreArtistModalBackground").hide();
        }
    });

    // CONFIRM DELETE
    $("#confirmRestoreArtist").on("click", function () {
        let btn = $(this);
        let originalText = btn.text();

        // Change to "Deleting..."
        btn.text("Restoring...");
        btn.prop("disabled", true);

        $.ajax({
            url: _publicPath + "/artwork/restoreArtwork/" + selectedArtistId,
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function () {
                // Refresh list
                serach();

                // Reset button text
                btn.text(originalText);
                btn.prop("disabled", false);

                // Close modal
                $("#restoreArtistModal").hide();
                $("#restoreArtistModalBackground").hide();
            },
            error: function () {
                // Reset button text on failure
                btn.text(originalText);
                btn.prop("disabled", false);

                Lobibox.notify("error", {
                    position: "top right",
                    rounded: false,
                    delay: 120000,
                    delayIndicator: true,
                    msg: "Something went wrong!",
                });
            },
        });
    });

    // 1. Open Panel on Row Click
    $("#main-artwork-list").on("click", ".list-row-grid", function (e) {
        // Check if the click originated from an explicit interactive element (checkbox, button, menu button)
        // If it did, we don't open the profile, we let the element handle its own action.
        const selectedArtistId = $(this).data("artist-id");
        const selectedArtistShowRoute = $(this).data("artist-route");
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
                updateArtworkSlides(selectedArtistId);
                renderVideoModal();
            },
            error: function () {
                // Reset button text on failure
            },
        });
    });
    function renderVideoModal(){
        const modal = document.getElementById("videoModal");
        const player = document.getElementById("videoPlayer");
        const closeBtn = document.getElementById("closeVideoModal");

        console.log("SCRIPT LOADED");

        document.querySelectorAll(".open-video-modal").forEach(item => {
            // console.log("FOUND VIDEO ITEM", item);
            item.addEventListener("click", () => {
                console.log("CLICKED", item);
                const videoSrc = item.dataset.video;
                player.src = videoSrc;
                modal.classList.remove("hidden");
                player.play();
            });
        });
        // CLOSE BUTTON 
        closeBtn.addEventListener("click", () => { player.pause(); modal.classList.add("hidden"); player.src = ""; });
        // CLOSE WHEN CLICK OUTSIDE VIDEO 
        modal.addEventListener("click", (e) => { if (e.target === modal) { player.pause(); modal.classList.add("hidden"); player.src = ""; } });
    }

    $(document).on("click", "#rejectArtist", function () {
        let rejectedArtistId = $(this).data("id");
        $("#reject_id").val(rejectedArtistId);
        $("#rejectArtistModalBackground").css("display", "flex");
        $("#rejectArtistModal").show();
    });

    $(document).on("click", "#approvedArtistForm", function () {
        let approvedArtistId = $(this).data("id");
        $("#approved_id").val(approvedArtistId);
        $("#approvedArtistModalBackground").css("display", "flex");
        $("#approvedArtistModal").show();
    });

    $("#closeRejectModal, #cancelReject").on("click", function () {
        $("#rejectArtistModal").hide();
        $("#rejectArtistModalBackground").hide();
    });

    // Close modal when clicking on background only (not on modal content)
    $("#rejectArtistModalBackground").on("click", function (e) {
        if (e.target === this) {
            $("#rejectArtistModal").hide();
            $("#rejectArtistModalBackground").hide();
        }
    });
}
if ($("#artwork_create_update_form").length) {
    $(document).on("click", "#rejectArtist", function () {
        let rejectedArtistId = $(this).data("id");
        $("#reject_id").val(rejectedArtistId);
        $("#rejectArtistModalBackground").css("display", "flex");
        $("#rejectArtistModal").show();
    });

}
const artmodal = document.getElementById("artworksModal");
const closeArtworksBtn = document.getElementById("closeArtworksModal");
const artworksModalEl = document.querySelector(".mySwiperArtworksModal");
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
let artworksSwiper = null;
if (artworksModalEl) {
   artworksSwiper = new Swiper(".mySwiperArtworksModal", artworksSwiperConfig);
    // Function to close artworks modal
    function closeArtworksModal() {
        if (artmodal) {
            artmodal.classList.add("hidden");
        }
    }
    $(document).on("click", ".openArtworkPageModal", function (e) {
        e.preventDefault();
        let TypeId = $(this).data("id");
        let type = $(this).data("type");
        console.log(TypeId,type);
        if(TypeId){
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
        }
    });
    // Close artworks modal button
    if (closeArtworksBtn) {
        closeArtworksBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            closeArtworksModal();
        });
    }
    if (artmodal) {
        artmodal.addEventListener("click", function (e) {
            // If click is directly on the modal backdrop (not on the content)
            if (e.target === artmodal) {
                closeArtworksModal();
            }
        });
    }

    // Close modals on Escape key
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            if (artmodal && !artmodal.classList.contains("hidden")) {
                closeArtworksModal();
            }
        }
    });
}

function updateArtworkSlides(TypeId){    
    $.ajax({
        url: _publicPath + "/artwork/images/" + TypeId,
        type: "GET",
        data: {
            type: type
        },
        success: function (response) {

            // response: { artworks: [{ id, image }] }

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
                                <img src="${art.image}"
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

if ($("#main-artwork-list").length || $("#artwork_create_update_form").length){
    let selectedArtistId = null; 
    let selectedType = null;

    // OPEN MODAL
    $(document).on("click", ".deleteArtist", function () {
        selectedArtistId = $(this).data("id");
        selectedType = $(this).data("type");
        $("#deleteArtistModal").css("display", "flex");
        $("#deleteArtistModalBackground").css("display", "flex");
    });

    // CLOSE MODAL
    $("#closeDeleteModal, #cancelDelete").on("click", function () {
        $("#deleteArtistModal").hide();
        $("#deleteArtistModalBackground").hide(); 
        $(".approvedArtistModal").hide();
        $(".approvedArtistModalBackground").hide();
    });

    // Only close if clicking directly on the background, not on the modal content
    $(document).on("click", "#deleteArtistModalBackground", function (e) {
        if (e.target === this) {
            $("#deleteArtistModal").hide();
            $("#deleteArtistModalBackground").hide();
        }
    });


    // CONFIRM DELETE
    $("#confirmDeleteArtist").on("click", function () {
        let btn = $(this);
        let originalText = btn.text();

        // Change to "Deleting..."
        btn.text("Deleting...");
        btn.prop("disabled", true);

        $.ajax({
            url: _publicPath + "/artwork/" + selectedArtistId,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                _type: selectedType,
            },
            success: function (data) {
                if (selectedType == 'editPage') {
                    location.href = data["url"];
                } else {
                    // Refresh list
                    serach();
                }

                // Reset button text
                btn.text(originalText);
                btn.prop("disabled", false);

                // Close modal
                $("#deleteArtistModal").hide();
                $("#deleteArtistModalBackground").hide();
            },
            error: function () {
                // Reset button text on failure
                btn.text(originalText);
                btn.prop("disabled", false);

                Lobibox.notify("error", {
                    position: "bottom right",
                    rounded: false,
                    delay: 120000,
                    delayIndicator: true,
                    msg: "Something went wrong!",
                });
            },
        });
    });
}

if ($("#artwork_tabs_block").length) {
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Selectors
        const navLinks = document.querySelectorAll('.removeartactive');
        
        // Select sections by ID
        const sections = {
            'artwork-details': document.getElementById('artwork-details'),
            'media': document.getElementById('media'),
            'price-location': document.getElementById('price-location'),
            'provenance': document.getElementById('provenance'),
            'exhibition-history': document.getElementById('exhibition-history')
        };

        // 2. Constants for Styles & Icons
        const activeTextClasses = ['text-[#1c1b1b]', 'font-typography-paragraph-base-semibold'];
        const inactiveTextClasses = ['text-[#505050]', 'font-typography-paragraph-base-medium'];
        
        // 3. Helper Function: Activates a specific tab and resets others
        function activateTab(targetLink) {
            if (!targetLink) return;

            // A. Reset ALL tabs first
            navLinks.forEach(link => {
                link.classList.remove('artactive');
                
                // Reset Text
                const textSpan = link.querySelector('span:first-child');
                if(textSpan) {
                    textSpan.classList.remove(...activeTextClasses);
                    textSpan.classList.add(...inactiveTextClasses);
                    textSpan.style.removeProperty('--typography-paragraph-base-semibold-font-weight');
                }

                // Reset Icon
                const iconSpan = link.querySelector('img'); 
                if(iconSpan) iconSpan.src = inactiveIconSrc;

                // HIDE THE LINE (Image sibling)
                // We look for the next sibling element which is the img.tab-line
                const lineImg = link.nextElementSibling;
                if(lineImg && lineImg.classList.contains('tab-line')) {
                    lineImg.classList.add('hidden');
                }
            });

            // B. Activate TARGET tab
            targetLink.classList.add('artactive');
            
            // Set Text
            const activeTextSpan = targetLink.querySelector('span:first-child');
            if(activeTextSpan) {
                activeTextSpan.classList.remove(...inactiveTextClasses);
                activeTextSpan.classList.add(...activeTextClasses);
            }

            // Set Icon
            const activeIconSpan = targetLink.querySelector('img');
            if(activeIconSpan) activeIconSpan.src = activeIconSrc;

            // SHOW THE LINE
            const activeLineImg = targetLink.nextElementSibling;
            if(activeLineImg && activeLineImg.classList.contains('tab-line')) {
                activeLineImg.classList.remove('hidden');
            }
        }

        // 4. Click Event Listener
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) { 
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = sections[targetId];

                if (targetSection) {
                    const offsetTop = targetSection.offsetTop - 100;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                    history.pushState(null, null, '#' + targetId);
                    activateTab(this);
                }
            });
        });

        // 5. Scroll Event Listener
        let scrollTimeout;
        /*window.addEventListener('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                let currentId = '';
                const scrollPosition = window.pageYOffset + 150;

                Object.keys(sections).forEach(id => {
                    const section = sections[id];
                    if (section) {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.offsetHeight;
                        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                            currentId = id;
                        }
                    }
                });

                if (currentId) {
                    const targetLink = document.querySelector(`.removeartactive.${currentId}`);
                    if (targetLink && !targetLink.classList.contains('artactive')) {
                        activateTab(targetLink);
                    }
                }
            }, 10);
        });*/

        // 6. Initial Load Handling
        if (window.location.hash) {
            const hash = window.location.hash.substring(1);
            if (sections[hash]) {
                setTimeout(() => {
                    const targetSection = sections[hash];
                    const offsetTop = targetSection.offsetTop - 100;
                    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                    const targetLink = document.querySelector(`.removeartactive.${hash}`);
                    if (targetLink) activateTab(targetLink);
                }, 100);
            }
        }
    });
}