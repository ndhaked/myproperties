$(function () {
    $(document).on("keypress", ".numberonly", validateNumber);
    $(document).on("keypress", ".isinteger", isNumber);
    $(document).on("keypress", ".isNumberOrFloat", validateNumberAndFloat);

    DirectFormSubmitWithAjax.init();
    DirectFormSubmitWithAjaxWithConfirm.init();
});

document.querySelectorAll('[role="radiogroup"] > div.flex.items-start').forEach(optionDiv => {
    optionDiv.addEventListener('click', () => {
        const radio = optionDiv.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
        }
    });
});

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
function getMetaContentByName(name, content) {
    var content = content == null ? "content" : content;
    return document
        .querySelector("meta[name='" + name + "']")
        .getAttribute(content);
}
function validateNumber(event) {
    // 1. Get the element that triggered the event
    var $element = $(event.target);

    // 2. CRITICAL CHECK:
    // If the class 'numberonly' is missing, STOP validating and allow typing.
    if (!$element.hasClass('numberonly')) {
        return true;
    }

    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if (key < 48 || key > 57) {
        return false;
    } else {
        return true;
    }
}

// Restricts input for the given textbox to the given inputFilter.
function isNumber(evt) {
    evt = evt ? evt : window.event;
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function validateNumberAndFloat(event) {
    var $element = $(event.target);

    if (!$element.hasClass('isNumberOrFloat')) {
        return true;
    }

    var key = event.which || event.keyCode;
    var currentValue = $element.val();

    // 1. Allow: Backspace (8), Tab (9), Enter (13)
    // Removed 46 from here to handle it specifically for the Decimal Point/Delete
    if (key == 8 || key == 9 || key == 13) {
        return true;
    }

    // 2. Allow: Decimal Point (.)
    if (key == 46) {
        // If dot already exists, block it
        if (currentValue.indexOf('.') !== -1) {
            return false;
        }
        return true;
    }

    // 3. Allow: Numbers 0-9 (48-57)
    if (key >= 48 && key <= 57) {
        var dotIndex = currentValue.indexOf('.');

        // Check if a decimal exists
        if (dotIndex !== -1) {
            var afterDecimal = currentValue.substring(dotIndex + 1);

            // If there are already 2 digits after decimal
            if (afterDecimal.length >= 2) {
                // Only block if the cursor is positioned AFTER the decimal point
                var cursorPosition = event.target.selectionStart;
                if (cursorPosition > dotIndex) {
                    return false;
                }
            }
        }
        return true;
    }

    // Block everything else (letters, symbols, etc.)
    return false;
}

$(document).on('input', '.numberonlyvalid', function () {
    // Replace anything that is NOT a number (0-9) with empty string
    this.value = this.value.replace(/[^0-9]/g, '');
});

$(function () {
    $(".numberonly")
        .keypress(function (e) {
            if (isNaN(this.value + "" + String.fromCharCode(e.charCode)))
                return false;
        })
        .on("cut copy paste", function (e) {
            e.preventDefault();
        });
});

Lobibox.notify.DEFAULTS = $.extend({}, Lobibox.notify.DEFAULTS, {
    iconSource: 'fontAwesome',
});

$(".reset").click(function () {
    //$("#validateForm").data('validator').resetForm();
    $(".form-group").each(function () {
        $(this).removeClass("has-error");
    });
});

jQuery(document).ready(function () {
    $("#country").on("change", function () {
        let code = $(this).find("option:selected").data("code");
        let phoneCodeSelect = $("#phoneCode");

        if (code && phoneCodeSelect.length) {
            // Auto-select the matching phone code without removing other options
            let phoneCodeValue = "+" + code;
            phoneCodeSelect.val(phoneCodeValue);
        }
    });

    jQuery("#validateForm").validate({
        ignore: [],
        rules: {
            email: {
                email: true,
            },
            "g-recaptcha-response": {
                required: function () {
                    if (grecaptcha.getResponse() == "") {
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            password: {
                minlength: 8,
            },
            password_confirmation: {
                equalTo: "#password",
            },
            body: {
                required: function (textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, "");
                    return editorcontent.length === 0;
                },
            },
            description_en: {
                required: function (textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, "");
                    return editorcontent.length === 0;
                },
            },
            description1: {
                required: function (textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, "");
                    return editorcontent.length === 0;
                },
            },
        },
        messages: {
            email: {
                email: "Please enter correct email",
            },
            password: {
                minlength: "Password must be at least 8 characters long",
            },
            password_confirmation: {
                equalTo: "Password and confirm password doesn't match.",
            },
            "g-recaptcha-response": "Please verify the Captcha",
        },
        highlight: function (element) {
            jQuery(element)
                .closest(".form-group")
                .removeClass("has-success")
                .addClass("has-error");
        },
        errorPlacement: function (e, r) {
            e.appendTo(r.closest(".ermsg"));
        },
        success: function (label, element) {
            jQuery(element).closest(".form-group").removeClass("has-error");
            label.remove();
        },
        submitHandler: function (form) {
            $(".formsubmit").attr("disabled", true);
            form.submit();
        },
    });
    let _imageUpload = "";
    $(".onlyimageupload").change(function (event) {
        // if (this.id === "file-upload-space-photos") {

        //     const max = 5;

        //     // Count existing images inside gallery preview only
        //     const existing = $("#gallery-cover-preview-container .image-block").length;

        //     const selected = this.files.length;
        //     const remaining = max - existing;

        //     if (remaining <= 0) {
        //         Lobibox.notify("warning", {
        //             position: "bottom right",
        //             icon: 'fa fa-times-circle',
        //             msg: "You have reached the maximum limit of 5 images.",
        //         });
        //         $(this).val("");
        //         return;
        //     }

        //     if (selected > remaining) {
        //         Lobibox.notify("warning", {
        //             position: "bottom right",
        //             icon: 'fa fa-times-circle',
        //             msg: `You can upload only ${remaining} more image(s).`,
        //         });
        //         $(this).val("");
        //         return;
        //     }
        // }
        _imageUpload = $(this).data("uploadurl");
        var MediaId = this.id;
        var inter;
        Lobibox.progress({
            title: "Please wait",
            label: "Uploading files...",
            progressTpl:
                '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function () { },
            onShow: function ($this) {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $(".myprogress").text(percentComplete + "%");
                            $(".myprogress").css(
                                "width",
                                percentComplete + "%"
                            );
                            var i = 0;
                        }
                    },
                    false
                );
                return xhr;
            },
            closed: function () {
                //
            },
        });
        event.preventDefault();
        var data = new FormData();
        var files = $("#" + MediaId).get(0).files;
        data.append("_token", getMetaContentByName("csrf-token"));
        if (MediaId == "PImage" || MediaId == "imageUpload") {
            data.append("user_id", $(this).data("userid"));
        }
        if (files.length > 0) {
            data.append("files", files[0]);
        } else {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("info", {
                        position: "bottom right",
                        rounded: false,
                        delay: 120000,
                        delayIndicator: true,
                        msg: "Please select file to upload.",
                    });
                })();
            });
            return false;
        }
        var extension = $("#" + MediaId)
            .val()
            .split(".")
            .pop()
            .toUpperCase();
        if (
            extension != "PNG" &&
            extension != "JPG" &&
            extension != "GIF" &&
            extension != "JPEG"
        ) {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("error", {
                        position: "bottom right",
                        rounded: false,
                        delay: 120000,
                        delayIndicator: true,
                        icon: 'fa fa-times-circle',
                        msg: "Invalid image file format.",
                    });
                })();
            });
            $("#" + MediaId).val("");
            return false;
        }
        $.ajax({
            type: "post",
            enctype: "multipart/form-data",
            url: _imageUpload,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $(".myprogress").text(percentComplete + "%");
                            $(".myprogress").css(
                                "width",
                                percentComplete + "%"
                            );
                        }
                    },
                    false
                );
                return xhr;
            },
            beforeSend: function () { },
            success: function (data) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                if (data["status"]) {
                    Lobibox.notify("success", {
                        position: "bottom right",
                        icon: 'fa fa-check-circle',
                        msg: "File has been uploded successfully",
                    });
                    if (data["status_code"] == 250) {
                        if (data["s3FullPath"] != "") {
                            $("#f_" + MediaId).val(data["filename"]);
                            $("#dash_" + MediaId).attr(
                                "src",
                                data["s3FullPath"]
                            );
                            $("#v_" + MediaId).attr("src", data["s3FullPath"]);
                        } else {
                            $("#f_" + MediaId).val(data["filename"]);
                            $("#v_" + MediaId).attr(
                                "src",
                                _UserImgThumbSrc + data["filename"]
                            );
                        }
                    } else {
                        if (MediaId == "PImage" || MediaId == "imageUpload") {
                            $("#head_dp").attr(
                                "src",
                                _UserImgThumbSrc + data["filename"]
                            );
                            $("#imagePreview").css(
                                "background-image",
                                "url(" +
                                _UserImgThumbSrc +
                                data["filename"] +
                                ")"
                            );
                        }
                        $("#f_" + MediaId).val(data["filename"]);
                        if (data["asset_id"]) {
                            $("#f_" + MediaId).val(data["asset_id"]);
                        }
                    }
                    /*if(MediaId == 'PImage' || MediaId == 'imageUpload'){
                      $('#head_dp').attr('src',_UserImgThumbSrc+data['filename']);
                      $('#imagePreview').css('background-image', 'url('+_UserImgThumbSrc+data['filename'] +')');
                    }
                     $("#f_"+MediaId).val(data['filename']);
                     $('#dash_'+MediaId).attr('src',_UserImgThumbSrc+data['filename']);
                     $("#f_"+MediaId).val(data['filename']);*/
                } else {
                    Lobibox.notify("error", {
                        position: "bottom right",
                        icon: 'fa fa-times-circle',
                        msg: data["message"],
                    });
                }
            },
            error: function (e) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                jQuery("#logo-duplicate_" + MediaId).val("");
                var Arry = e.responseText;
                var error = "";
                JSON.parse(Arry, (k, v) => {
                    if (typeof v != "object") {
                        error += v + "<br>";
                    }
                });
                Lobibox.notify("error", {
                    position: "bottom right",
                    rounded: false,
                    delay: 120000,
                    icon: 'fa fa-times-circle',
                    delayIndicator: true,
                    msg: error,
                });
            },
        });
    });
});
function resetRecaptchaIfExists(formId) {
    // Hidden input exists only on captcha forms
    var $tokenField = $('#' + formId).find('#recaptcha_token');

    if ($tokenField.length) {
        $tokenField.val('');

        if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.reset === 'function') {
            try {
                grecaptcha.reset();
            } catch (e) {
                // v3 safe fallback
            }
        }
    }
}

var DirectFormSubmitWithAjax = (function () {
    "use strict";
    return {
        init: function () {
            $(document).on("click", ".directSubmit", function (event) {
                let _forSubmitDraft = $(this).val();
                $("#submit_action").val($(this).val());
                var submitFormId = "F_" + this.id;
                if (_forSubmitDraft == "draft") {
                    submitFormId = "F_saveArtist";
                }
                // Run invisible captcha ONLY for login form
                if (
                    (submitFormId === 'F_doLogin' || submitFormId === 'F_resetForm' || submitFormId === 'F_register-interest' || submitFormId == 'F_contact-form') &&
                    $('#recaptcha_token').val() === ''
                ) {
                    event.preventDefault();
                    if (App_ENV !== 'local') {
                        grecaptcha.ready(function () {
                            var action = '';
                            if (submitFormId === 'F_doLogin') {
                                action = 'login';
                            } else if (submitFormId === 'F_resetForm') {
                                action = 'forgot_password';
                            } else if (submitFormId === 'F_register-interest') {
                                action = 'register_interest';
                            } else if (submitFormId === 'F_contact-form') {
                                action = 'contact';
                            }

                            grecaptcha.execute(RECAPTCHA_SITE_KEY, { action: action })
                                .then(function (token) {
                                    $('#recaptcha_token').val(token);
                                    $('.directSubmit').trigger('click'); // retry submit
                                });
                        });
                    }

                    return false;
                }


                var action = $("#" + submitFormId).attr("action");
                var $btn = $(this);
                window.clickedButtonValue = $(this).val();
                var originalBtnHtml = $btn.html();
                $("#" + submitFormId).validate({
                    ignore: [],
                    rules: {
                        password: {
                            minlength: 8,
                            required: function () {
                                if ($("#old_password").val() == "")
                                    return $("#old_password").val().trim() !== "";
                                else return true;
                            },
                        },
                        npass: {
                            minlength: 8,
                        },
                        password_confirmation: {
                            equalTo: "#password",
                            required: function () {
                                // Only validate confirm password if password is entered
                                return $("#password").val().trim() !== "";
                            },
                        },
                        "g-recaptcha-response": {
                            required: function () {
                                if (grecaptcha.getResponse() == "") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },
                    },
                    messages: {
                        password: {
                            minlength:
                                "Password must be at least 8 characters long",
                        },
                        npass: {
                            minlength:
                                "Password must be at least 8 characters long",
                        },
                        password_confirmation: {
                            equalTo:
                                "Password and confirm password doesn't match.",
                        },
                        "g-recaptcha-response": "Please verify the Captcha",
                    },

                    errorPlacement: function (e, r) {
                        // 1. Get the wrapper
                        var container = r.closest(".ermsg");

                        // 2. Remove ANY label with class 'error' inside this wrapper
                        // Make sure the class name matches exactly what is in your HTML
                        container.find("label.error").remove();

                        // 3. Add the new error
                        e.appendTo(container);
                    },
                    submitHandler: function (form) {
                        $(".directSubmit").prop("disabled", true);
                        $btn.html(
                            '<span style="color:#fff" class="spinner-border spinner-border-sm me-2" role="status"></span><span style="color:#fff">Processing...</span>'
                        );

                        $(".reset_loader").show();
                        $(".lobibox-close").trigger("click");
                        $(form).ajaxSubmit({
                            url: action,
                            type: "POST",
                            cache: false,
                            data: {
                                submit: window.clickedButtonValue,
                            },
                            success: function (data) {
                                $btn.html(originalBtnHtml);
                                // Pass the form element to our tracking function
                                if (data["ga4Event"]) {
                                    if (data["ga4Event"] === 'requestSuccess') {
                                        const formElement = document.getElementById(submitFormId);
                                        //window.trackGa4Subscription(formElement);
                                    }
                                }

                                $(".reset_loader").hide();
                                if (data["reset"]) {
                                    if (data["gcp-reset"]) {
                                        grecaptcha.reset();
                                    }
                                    document
                                        .getElementById(submitFormId)
                                        .reset();
                                }
                                /* Lobibox.notify(data['type'], {
                                     position: "top right",
                                     msg: data['message']
                                 });*/
                                if (data["status_code"] == 200) {
                                    if (data["html"]) {
                                        $("#result").html("");
                                        $("#result").html(
                                            JSON.parse(data["body"])
                                        );
                                    }

                                    if (data["reload"]) {
                                        location.reload();
                                    }
                                    if (data["modelCloseSide"]) {
                                        $("#" + data["modelCloseSide"]).trigger(
                                            "click"
                                        );
                                    }
                                    if (data["rejectCloseSide"]) {
                                        $(
                                            "#" + data["rejectCloseSide"]
                                        ).trigger("click");
                                    }
                                    if (data["closeModel"]) {
                                        $(
                                            "#" + data["closeModel"]
                                        ).trigger("click");
                                    }
                                    if (data["isPageListRefreshByAjax"]) {
                                        // Only run serach() if it exists and is a function
                                        if (typeof serach === "function") {
                                            serach();
                                        }
                                    }
                                    if (data["modelClose"]) {
                                        const modalId = data["modelClose"];
                                        const $modal = $("#" + modalId);

                                        if (
                                            $modal.length &&
                                            typeof $modal.modal === "function"
                                        ) {
                                            $modal.modal("hide");
                                        } else if (
                                            typeof window.ModalHandler !==
                                            "undefined" &&
                                            typeof window.ModalHandler.close ===
                                            "function"
                                        ) {
                                            window.ModalHandler.close(modalId);
                                        } else {
                                            const modalEl =
                                                document.getElementById(
                                                    modalId
                                                );
                                            if (modalEl) {
                                                modalEl.classList.add("hidden");
                                                modalEl.classList.remove(
                                                    "flex"
                                                );
                                                document.body.style.overflow =
                                                    "";
                                            }
                                        }
                                    }
                                }
                                if (data["status_code"] == 250) {
                                    if (data["azureFullPath"] != "") {
                                        if (data["appendUrlPage"] == 'forArtistProfileAddEdit') {
                                            $("#f_file-upload").val(data["asset_id"]);
                                            $(".artistEditAddUrl").attr(
                                                "src",
                                                data["azureFullPath"]
                                            );
                                        }
                                    }
                                    $("#closeModal").trigger('click');
                                }
                                if (
                                    data["message"] &&
                                    typeof Lobibox !== "undefined"
                                ) {
                                    Lobibox.notify(data["type"] || "success", {
                                        rounded: false,
                                        delay: 8000,
                                        delayIndicator: true,
                                        position: "bottom right",
                                        icon: 'fa fa-check-circle',
                                        msg: data["message"],
                                    });
                                }

                                if (data["url"]) {
                                    setTimeout(function () {
                                        location.href = data["url"];
                                    }, 3000); // 1000 milliseconds = 1 second
                                }

                                if (data["isDisabledTrue"]) {

                                } else {
                                    $(".directSubmit").prop("disabled", false);
                                }
                            },
                            error: function (e) {
                                resetRecaptchaIfExists(submitFormId);

                                $(".reset_loader").hide();
                                $btn.html(originalBtnHtml);
                                $(".directSubmit").prop("disabled", false);
                                var res = JSON.parse(e.responseText);



                                let error = "";

                                // Add main message
                                if (res.message) {
                                    error = res.message;
                                }


                                // Add validation errors (only unique, show only one)
                                if (res.errors) {
                                    const uniqueErrors = new Set();

                                    Object.values(res.errors).forEach(
                                        (errArr) => {
                                            errArr.forEach((msg) =>
                                                uniqueErrors.add(msg)
                                            );
                                        }
                                    );

                                    if (uniqueErrors.size > 0) {
                                        error = Array.from(uniqueErrors)[0]; // show only ONE message
                                    }
                                }

                                if (res["ga4Event"] === 'requestFailed') {
                                    const formElement = document.getElementById(submitFormId);
                                    //window.trackGa4Subscription(formElement,error);
                                }
                                // Show in notification
                                Lobibox.notify("error", {
                                    rounded: false,
                                    delay: 8000,
                                    delayIndicator: true,
                                    position: "bottom right",
                                    icon: 'fa fa-times-circle',
                                    msg: error,
                                });
                            },
                        });
                    },
                });
            });
        },
    };
})();

var DirectFormSubmitWithAjaxWithConfirm = (function () {
    "use strict";
    return {
        init: function () {
            $(document).on("click", ".directSubmitConfirm", function (e) {
                e.preventDefault(); // Prevent direct form submission

                var submitButton = $(this);
                var submitFormId = "F_" + submitButton.attr("id"); // Get form ID dynamically

                var form = $("#" + submitFormId); // Get the form element
                // 1. Use .data() to get 'data-titlemsg' automatically
                var titleMsg = submitButton.data("titlemsg");
                var bodyMsg = submitButton.data("bodymsg");

                // 2. Fix Logic: Set default only if the variable is EMPTY or UNDEFINED
                if (!titleMsg) {
                    titleMsg = "Confirmation";
                }

                if (!bodyMsg) {
                    bodyMsg = "Are you sure you want to proceed?";
                }
                Lobibox.confirm({
                    msg: "Are you sure you want to proceed?",
                    title: "Confirmation",
                    buttons: {
                        yes: {
                            text: "Yes",
                            class: "lobibox-btn lobibox-btn-yes",
                        },
                        no: {
                            text: "No",
                            class: "lobibox-btn lobibox-btn-no",
                        },
                    },
                    callback: function ($this, type) {
                        if (type === "yes") {
                            if (submitFormId == "F_approvedGalleryForm") {
                                $("#approvedGallary").trigger("click");
                            }
                            if (submitFormId == "F_approvedArtistForm") {
                                $("#approvedArtist").trigger("click");
                            } else {
                                form.submit(); // Manually submit the form
                            }
                        }
                    },
                });
            });
        },
    };
})();
$(document).ready(function () {
    let cooldown = false;
    const resendOtpUrl = "{{ url('../resend-otp') }}";

    $(".resend-btn").click(function () {
        if (cooldown) return;

        let email = $("input[name='email']").val();
        let resend_url = $("#resend_url").val();
        // Start cooldown
        startCooldown();

        // Call your resend OTP API
        $.ajax({
            url: resend_url,

            type: "POST",
            data: {
                email: email,
                _token: $("meta[name='csrf-token']").attr("content"),
            },
            success: function (res) {
                setTimeout(() => {
                    Lobibox.notify("success", {
                        rounded: false,
                        delay: 6000,
                        delayIndicator: true,
                        position: "bottom right",
                        icon: 'fa fa-check-circle',
                        msg: "OTP resent successfully.",
                    });
                }, 30000); // 30 seconds delay
            },
            error: function () {
                Lobibox.notify("error", {
                    rounded: false,
                    delay: 6000,
                    delayIndicator: true,
                    position: "bottom right",
                    icon: 'fa fa-times-circle',
                    msg: "Something went wrong. Please try again later.",
                });
            },
        });
    });

    // Cooldown function
    function startCooldown() {
        $("#countdown-badge").removeClass("d-none");
        cooldown = true;
        let seconds = 30;

        $("#resend-btn").prop("disabled", true);
        $("#countdown-badge").removeClass("d-none").text(`${seconds}s`);
        $("#resend-text").text("Resend in");

        let timer = setInterval(() => {
            seconds--;
            $("#countdown-badge").text(`${seconds}s`);
            if (seconds <= 0) {
                $("#countdown-badge").addClass("d-none");
                clearInterval(timer);
                cooldown = false;
                $("#resend-btn").prop("disabled", false);
                $("#resend-text").show();
            }
        }, 1000);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".verification-code-input");

    inputs.forEach((input, index) => {
        input.addEventListener("input", function (e) {
            this.value = this.value.replace(/[^0-9]/g, ""); // remove non-numbers

            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener("keydown", function (e) {
            if (e.key === "Backspace" && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
    // Public path from Blade
    var _publicPath = window._publicPath || "";

    // Icon paths
    const showIcon = _publicPath + "/assets/images/password-show.svg";
    const hideIcon = _publicPath + "/assets/images/password-hide.svg";

    // Find ALL password wrappers on page
    const wrappers = document.querySelectorAll(".password-wrapper");

    if (!wrappers.length) return; // exit if none exist

    wrappers.forEach((wrapper) => {
        const input = wrapper.querySelector(
            "input[type='password'], input[type='text']"
        );
        const toggleBtn = wrapper.querySelector(".password-toggle");
        const icon = wrapper.querySelector(".password-toggle-icon");

        if (!input || !toggleBtn || !icon) return;

        toggleBtn.addEventListener("click", function () {
            const isHidden = input.type === "password";

            // Toggle password visibility
            input.type = isHidden ? "text" : "password";

            // Update icon
            icon.src = isHidden ? hideIcon : showIcon;
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Run only if block exists
    let block = document.getElementById("birth_death_year_block");
    if (!block) return;

    let birthEl = document.getElementById("year_of_birth");
    let deathEl = document.getElementById("year_of_death");

    if (!birthEl || !deathEl) return;

    function validateYears() {
        let birth = parseInt(birthEl.value);
        let death = parseInt(deathEl.value);

        if (birth && death && death < birth) {
            Lobibox.notify("error", {
                position: "bottom right",
                rounded: false,
                delay: 120000,
                delayIndicator: true,
                icon: 'fa fa-times-circle',
                msg: "Year of Death must be greater than or equal to Year of Birth.",
            });

            if (document.activeElement.id === "year_of_death") {
                deathEl.value = "";
            } else {
                birthEl.value = "";
            }
        }
    }

    birthEl.addEventListener("change", validateYears);
    deathEl.addEventListener("change", validateYears);
});

if ($("#front_dash_pagination").length) {
    $("body").on("click", ".active_remove_tab", function () {
        $(".active_remove_tab").removeClass("active");
        $(this).addClass("active");

        var myvisitSerach = $(this).data("default");
        $("#search_type").val(myvisitSerach);

        serach();
    });
}
if ($("#main-artist-list").length) {
    let selectedArtistId = null;

    // OPEN MODAL
    $(document).on("click", ".deleteArtist", function () {
        selectedArtistId = $(this).data("id");
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
            url: _publicPath + "/artist/" + selectedArtistId,
            type: "DELETE",
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
                    icon: 'fa fa-times-circle',
                    msg: "Something went wrong!",
                });
            },
        });
    });

    $("#confirmDeleteProject").on("click", function () {
        let btn = $(this);
        let originalText = btn.text();

        // Change to "Deleting..."
        btn.text("Deleting...");
        btn.prop("disabled", true);

        $.ajax({
            url: _publicPath + "/projects/" + selectedArtistId,
            type: "DELETE",
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
                    icon: 'fa fa-times-circle',
                    msg: "Something went wrong!",
                });
            },
        });
    });

    $("#confirmDeleteLead").on("click", function () {
        let btn = $(this);
        let originalText = btn.text();

        // Change to "Deleting..."
        btn.text("Deleting...");
        btn.prop("disabled", true);

        $.ajax({
            url: _publicPath + "/leads/" + selectedArtistId,
            type: "DELETE",
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
                    icon: 'fa fa-times-circle',
                    msg: "Something went wrong!",
                });
            },
        });
    });

    $("#confirmDeleteDeal").on("click", function () {
        let btn = $(this);
        let originalText = btn.text();

        // Change to "Deleting..."
        btn.text("Deleting...");
        btn.prop("disabled", true);

        $.ajax({
            url: _publicPath + "/deals/" + selectedArtistId,
            type: "DELETE",
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
                    icon: 'fa fa-times-circle',
                    msg: "Something went wrong!",
                });
            },
        });
    });

    $("#confirmDeleteUser").on("click", function () {
        let btn = $(this);
        let originalText = btn.text();

        // Change to "Deleting..."
        btn.text("Deleting...");
        btn.prop("disabled", true);

        $.ajax({
            url: _publicPath + "/users/" + selectedArtistId,
            type: "DELETE",
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
                    icon: 'fa fa-times-circle',
                    msg: "Something went wrong!",
                });
            },
        });
    });
    // OPEN MODAL
    $(document).on("click", ".restoreArtist", function () {
        selectedArtistId = $(this).data("id");
        $("#restoreArtistModal").css("display", "flex");
        $("#restoreArtistModalBackground").css("display", "flex");
    });

    $(document).on("click", "#approvedArtistForm", function () {
        let approvedArtistId = $(this).data("id");

        $("#approved_id").val(approvedArtistId); approvedArtistModal
        $("#approvedArtistModalBackground").css("display", "flex");
        // $("#approvedArtistModal").show();
        // 2. Force the Modal to Block with !important
        // This overrides any inline 'display: none' or CSS class
        $("#approvedArtistModal").attr('style', 'display: block !important');
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
            url: _publicPath + "/artist/restoreArtist/" + selectedArtistId,
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
                    position: "bottom right",
                    rounded: false,
                    delay: 120000,
                    delayIndicator: true,
                    icon: 'fa fa-times-circle',
                    msg: "Something went wrong!",
                });
            },
        });
    });

    // Gallery Delete Functionality - Match artist delete pattern exactly
    console.log("DEBUG: Gallery delete functionality script loaded");
    let selectedGalleryId = null;

    // OPEN MODAL - Simple handler like artist delete
    $(document).on("click", ".deleteGallery", function (e) {
        console.log("DEBUG: Delete gallery button clicked");
        e.preventDefault();
        e.stopPropagation();
        selectedGalleryId = $(this).data("id");
        console.log("DEBUG: Selected Gallery ID:", selectedGalleryId);

        if (!selectedGalleryId) {
            console.error("DEBUG: No gallery ID found in data-id attribute!");
        }

        // Close the dropdown
        $(this).closest(".action-menu-dropdown").slideUp(100);

        // Show delete modal
        console.log("DEBUG: Attempting to show delete modal");
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

            console.log("DEBUG: Confirm delete gallery button clicked");
            console.log("DEBUG: Clicked element:", this);
            console.log("DEBUG: Selected Gallery ID:", selectedGalleryId);

            if (!selectedGalleryId) {
                console.error("DEBUG: No gallery ID selected!");
                alert("Error: No gallery ID selected!");
                return;
            }

            // Get the button element - could be clicked element or parent
            let btn = $(this).is("button")
                ? $(this)
                : $(this).closest("button");
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
                        console.log("DEBUG: Reloading page to refresh list");
                        window.location.reload();
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
                            position: "bottom right",
                            rounded: false,
                            delay: 120000,
                            icon: 'fa fa-times-circle',
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

    // 1. Open Panel on Row Click
    $("#main-artist-list").on("click", ".list-row-grid", function (e) {
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
            },
            error: function () {
                // Reset button text on failure
            },
        });
    });

    $(document).on("click", "#rejectArtist", function () {
        let rejectedArtistId = $(this).data("id");
        $("#reject_id").val(rejectedArtistId);
        $("#rejectArtistModalBackground").css("display", "flex");
        $("#rejectArtistModal").show();
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
if (
    $("#artist_create_update_form").length ||
    $("#artwork_create_update_form").length
) {
    $(document).ready(function () {
        // Function to get current exhibition count based on which container exists
        function getExhibitionCount() {
            if ($("#exhibition-container-artwork").length) {
                return (
                    $("#exhibition-container-artwork .exhibition-item")
                        .length || 0
                );
            } else if ($("#exhibition-container").length) {
                return $("#exhibition-container .exhibition-item").length || 0;
            } else {
                return $(".exhibition-item").length || 0;
            }
        }

        $(document).on("click", "#add-exhibition-btn", function () {
            // Get current count and increment for the new row
            let exhibitionCount = getExhibitionCount();
            exhibitionCount++;

            // Detect which page we're on and use appropriate route and container
            let url, containerId;
            if ($("#exhibition-container-artwork").length) {
                // Artwork page
                url = "/artwork/addmore/exhibition-row";
                containerId = "#exhibition-container-artwork";
            } else {
                // Artist page (default)
                url = "/artist/addmore/exhibition-row";
                containerId = "#exhibition-container";
            }

            $.ajax({
                url: url,
                type: "GET",
                data: { index: exhibitionCount },
                beforeSend: function () {
                    $(".ajaxloader").show();
                },
                success: function (html) {
                    const $newRow = $(html);
                    $(containerId).append($newRow);
                    $(".ajaxloader").hide();
                    // Initialize floating labels for the newly added row
                    setTimeout(function () {
                        if (typeof window.initializeFloatingLabels === 'function') {
                            window.initializeFloatingLabels($newRow);
                        } else {
                            // Fallback: manually initialize if function not available
                            $newRow.find('input[data-state], select[data-state], textarea[data-state]').each(function () {
                                const $field = $(this);
                                const $label = $field.siblings('label.portal-floating-label');
                                if ($label.length > 0) {
                                    const hasValue = $field.val() && $field.val().toString().trim() !== '';
                                    if (hasValue || $field.is(':focus')) {
                                        $label.addClass('active');
                                    }
                                }
                            });
                        }
                    }, 50);
                },
            });
        });

        // DELETE EXHIBITION ROW
        $(document).on("click", ".delete-exhibition", function () {
            // If you want to prevent deleting the last remaining row:
            $(this).closest(".exhibition-item").remove();
            /*if ($(".exhibition-item").length > 1) {
                $(this).closest(".exhibition-item").remove();
            } else {
                alert("You must have at least one exhibition.");
            }*/
        });
    });

    $(document).ready(function () {
        // 1. Initialize count based on existing rows
        let pressCount = $(".press-item").length || 0;

        // 2. Add New Press Row
        $(document).on("click", "#add-press-btn", function () {
            pressCount++;
            $.ajax({
                url: "/artist/addmore/press-row",
                type: "GET",
                data: { index: pressCount },
                beforeSend: function () {
                    $(".ajaxloader").show();
                },
                success: function (html) {
                    const $newRow = $(html);
                    $("#press-container").append($newRow);
                    $(".ajaxloader").hide();
                    // Initialize floating labels for the newly added row
                    setTimeout(function () {
                        if (typeof window.initializeFloatingLabels === 'function') {
                            window.initializeFloatingLabels($newRow);
                        } else {
                            // Fallback: manually initialize if function not available
                            $newRow.find('input[data-state], select[data-state], textarea[data-state]').each(function () {
                                const $field = $(this);
                                const $label = $field.siblings('label.portal-floating-label');
                                if ($label.length > 0) {
                                    const hasValue = $field.val() && $field.val().toString().trim() !== '';
                                    if (hasValue || $field.is(':focus')) {
                                        $label.addClass('active');
                                    }
                                }
                            });
                        }
                    }, 50);
                },
            });
        });

        // 3. Delete Press (except first, or logic to keep at least one)
        $(document).on("click", ".delete-press", function () {
            $(this).closest(".press-item").remove();
        });
    });

    if ($("#file-upload").length) {
        const dropArea = document.getElementById('dropArea');
        document
            .getElementById("file-upload")
            .addEventListener("change", function (event) {
                const file = event.target.files[0];

                if (file) {
                    // Create a URL for the selected file
                    $("#artistphotoDiv").show();
                    const imageUrl = URL.createObjectURL(file);

                    // Update the preview image source
                    const previewImage = document.getElementById("preview-image");
                    // const artistProPreview =
                    //     document.getElementById("artistProPreview");
                    previewImage.src = imageUrl;
                    // artistProPreview.src = imageUrl;

                    // Optional: Free up memory after the image loads
                    previewImage.onload = function () {
                        URL.revokeObjectURL(previewImage.src);
                    };
                    // artistProPreview.onload = function () {
                    //     URL.revokeObjectURL(artistProPreview.src);
                    // };
                }
            });

        // Prevent default behavior
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        // Highlight on dragover
        dropArea.addEventListener('dragover', () => dropArea.classList.add('drag-over'));
        dropArea.addEventListener('dragleave', () => dropArea.classList.remove('drag-over'));

        // On drop
        dropArea.addEventListener('drop', e => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];

            if (file) {
                // Create a URL for the selected file
                $("#artistphotoDiv").show();
                const imageUrl = URL.createObjectURL(file);

                // Update the preview image source
                const previewImage = document.getElementById("preview-image");
                previewImage.src = imageUrl;
                // Optional: Free up memory after the image loads
                previewImage.onload = function () {
                    URL.revokeObjectURL(previewImage.src);
                };
                /*const artistProPreview =
                    document.getElementById("artistProPreview");
                artistProPreview.src = imageUrl;
                artistProPreview.onload = function () {
                    URL.revokeObjectURL(artistProPreview.src);
                };*/
            }
        });
    }

    $(document).ready(function () {
        if ($("#biography").length) {
            const $textarea = $("#biography");
            const $counterDisplay = $("#charCount");
            const minLength = 50;
            const maxLength = 2000; // Define max here

            function updateBoographyCounter() {
                let content = $textarea.val();

                // --- FORCE TRUNCATION ---
                // If content is longer than 2000, cut it off immediately
                if (content.length > maxLength) {
                    content = content.substring(0, maxLength);
                    $textarea.val(content); // Update the box with the cut text
                }
                // ------------------------

                // Update the number
                let currentLength = content.length;
                $counterDisplay.text(currentLength);

                // Validation Colors
                if (currentLength < minLength) {
                    $counterDisplay
                        .removeClass("text-green-600")
                        .addClass("text-red-500");
                } else {
                    $counterDisplay
                        .removeClass("text-red-500")
                        .addClass("text-green-600");
                }
            }

            // Run on load
            updateBoographyCounter();

            // Run on typing OR pasting
            $textarea.on("input propertychange paste", function () {
                updateBoographyCounter();
            });
        }
    });
}

if ($("#gallery_update_form").length) {
    const dropArea1 = document.getElementById('dropArea1');
    const dropArea2 = document.getElementById('dropArea2');
    const dropArea3 = document.getElementById('dropArea3');

    let selectedLogoFile = null;
    let selectedCoverFile = null;

    const saveBtn = document.getElementById("cropSave");
    const saveText = document.getElementById("saveLogoText");
    const saveCoverText = document.getElementById("saveCoverText");
    const modal = document.getElementById("modalLogoOverlay");

    const saveCoverBtn = document.querySelector(".saveCoverBtn");
    const modalCover = document.getElementById("modalCoververlay");

    const cropModal = document.getElementById("customCropModal");
    const cropImg = document.getElementById("cropImage");
    const zoomSlider = document.getElementById("zoomSlider");

    let scale = 1;
    let imgX = 0;
    let imgY = 0;
    let dragging = false;
    let startX = 0;
    let startY = 0;
    let minScale = 1;
    let maxScale = 3;
    /* ---------- LOAD IMAGE ---------- */
    function loadImage(file) {

        cropImg.src = URL.createObjectURL(file);

        cropImg.onload = () => {
            const frame = document.querySelector(".crop-frame");

            const frameH = frame.offsetHeight;
            const imgH = cropImg.naturalHeight;
            const imgW = cropImg.naturalWidth;

            // 🔑 Set BASE size so image height == crop frame height
            const baseScale = frameH / imgH;

            // Apply base dimensions directly
            cropImg.style.height = frameH + "px";
            cropImg.style.width = (imgW * baseScale) + "px";

            // Reset transforms
            scale = 1;
            minScale = 1;
            maxScale = 3;

            zoomSlider.min = minScale;
            zoomSlider.max = maxScale;
            zoomSlider.value = scale;

            imgX = 0;
            imgY = 0;
            updateImage();
        };
    }

    /* ---------- IMAGE TRANSFORM ---------- */
    function updateImage() {
        cropImg.style.transform =
            `translate(-50%, -50%) translate(${imgX}px, ${imgY}px) scale(${scale})`;
    }

    /* ---------- DRAG ---------- */
    const stage = document.querySelector(".crop-stage");

    stage.addEventListener("mousedown", e => {
        dragging = true;
        startX = e.clientX - imgX;
        startY = e.clientY - imgY;
        stage.style.cursor = "grabbing";
    });

    window.addEventListener("mousemove", e => {
        if (!dragging) return;
        imgX = e.clientX - startX;
        imgY = e.clientY - startY;
        updateImage();
    });

    window.addEventListener("mouseup", () => {
        dragging = false;
        stage.style.cursor = "grab";
    });

    /* ---------- ZOOM ---------- */
    zoomSlider.addEventListener("input", () => {
        scale = Math.max(minScale, parseFloat(zoomSlider.value));
        updateImage();
    });

    document.getElementById("zoomIn").onclick = () => {
        scale = Math.min(scale + 0.1, maxScale);
        zoomSlider.value = scale;
        updateImage();
        updateZoomSliderUI();
    };

    document.getElementById("zoomOut").onclick = () => {
        scale = Math.max(scale - 0.1, minScale);
        zoomSlider.value = scale;
        updateImage();
        updateZoomSliderUI();
    };


    function updateZoomSliderUI() {
        const min = parseFloat(zoomSlider.min);
        const max = parseFloat(zoomSlider.max);
        const value = parseFloat(zoomSlider.value);

        const percent = ((value - min) / (max - min)) * 100;
        zoomSlider.style.setProperty("--zoom-progress", `${percent}%`);
    }

    // 🔁 Update on load & change
    zoomSlider.addEventListener("input", updateZoomSliderUI);
    updateZoomSliderUI();

    /* ---------- OPEN FROM FILE INPUT ---------- */
    document.getElementById("file-upload-logo").addEventListener("change", e => {
        const file = e.target.files[0];
        if (!file) return;
        e.target.value = "";
        document.getElementById("modalLogoOverlay").classList.remove("active");
        cropModal.classList.add("active");
        zoomSlider.style.setProperty("--zoom-progress", `0%`);
        loadImage(file);
    });

    /* ---------- CANCEL ---------- */
    document.getElementById("cropCancel").onclick = () => {
        cropModal.classList.remove("active");
    };

    document.getElementById("cropClose").onclick = () => {
        cropModal.classList.remove("active");
    };

    /* ---------- SAVE (REAL CROP) ---------- */
    saveBtn.addEventListener("click", async function (e) {
        e.preventDefault();
        e.stopPropagation();
        // 🔒 UI: before upload (same as saveBtn)
        $(".directSubmit").prop("disabled", true);
        saveBtn.disabled = true;
        saveBtn.classList.add("opacity-70", "cursor-not-allowed");
        saveText.textContent = "Saving...";

        const frame = document.querySelector(".crop-frame");
        const frameRect = frame.getBoundingClientRect();
        const imgRect = cropImg.getBoundingClientRect();

        // 🔒 Safety guard
        if (imgRect.width === 0 || imgRect.height === 0) {
            alert("Unable to crop this area. Please adjust and try again.");
            resetSaveUI();
            return;
        }

        const scaleX = cropImg.naturalWidth / imgRect.width;
        const scaleY = cropImg.naturalHeight / imgRect.height;

        let sx = (frameRect.left - imgRect.left) * scaleX;
        let sy = (frameRect.top - imgRect.top) * scaleY;
        let sw = frameRect.width * scaleX;
        let sh = frameRect.height * scaleY;

        // 🔒 Clamp values to image bounds
        sx = Math.max(0, sx);
        sy = Math.max(0, sy);
        sw = Math.min(cropImg.naturalWidth - sx, sw);
        sh = Math.min(cropImg.naturalHeight - sy, sh);

        if (sw <= 0 || sh <= 0) {
            alert("Unable to crop this area. Please adjust and try again.");
            resetSaveUI();
            return;
        }

        /* 🎯 FINAL OUTPUT SIZE (LOGO) */
        const OUTPUT_WIDTH = 164;
        const OUTPUT_HEIGHT = 164;

        const canvas = document.createElement("canvas");
        canvas.width = OUTPUT_WIDTH;
        canvas.height = OUTPUT_HEIGHT;

        const ctx = canvas.getContext("2d");
        ctx.imageSmoothingEnabled = true;
        ctx.imageSmoothingQuality = "high";

        ctx.drawImage(
            cropImg,
            sx, sy, sw, sh,
            0, 0, OUTPUT_WIDTH, OUTPUT_HEIGHT
        );

        /* 📦 COMPRESSED JPEG */
        canvas.toBlob(blob => {
            if (!blob) {
                alert("Image processing failed.");
                resetSaveUI();
                return;
            }

            const formData = new FormData();
            formData.append("files", blob, "logo.jpg");

            fetch("/gallery/logo/upload", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
                },
                body: formData
            })
                .then(res => res.json())
                .then(data => {

                    if (data.status) {
                        // ✅ SAME AFTER-SAVE UPDATES
                        document.getElementById("logo_file_id").value = data.asset_id;

                        document.getElementById("galleryLogo").src = data.url;
                        document.querySelectorAll(".gallery-logo-image").forEach(img => {
                            img.src = data.url;
                        });

                        cropModal.classList.remove("active");
                        modal.classList.remove("active");

                        selectedLogoFile = null;
                    }
                })
                .catch(err => {
                    console.error("Upload Error:", err);
                    alert("Upload failed. Please try again.");
                })
                .finally(resetSaveUI);

        }, "image/jpeg", 0.85); // 👈 QUALITY CONTROL
    });

    /* 🔁 UI RESET HELPER */
    function resetSaveUI() {
        $(".directSubmit").prop("disabled", false);
        saveBtn.disabled = false;
        saveBtn.classList.remove("opacity-70", "cursor-not-allowed");
        saveText.textContent = "Save";
    }



    // 2️⃣ Save click → upload
    // saveBtn.addEventListener("click", function () {

    //     if (!selectedLogoFile) {
    //         alert("Please select a logo first");
    //         return;
    //     }

    //     // 🔄 Processing state
    //     $(".directSubmit").prop("disabled", true);
    //     saveBtn.disabled = true;
    //     saveBtn.classList.add("opacity-70", "cursor-not-allowed");
    //     saveText.textContent = "Saving...";

    //     let formData = new FormData();
    //     formData.append("files", selectedLogoFile);

    //     fetch("/gallery/logo/upload", {
    //         method: "POST",
    //         headers: {
    //             "X-CSRF-TOKEN": document.querySelector(
    //                 'meta[name="csrf-token"]'
    //             ).content,
    //         },
    //         body: formData,
    //     })
    //     .then(res => res.json())
    //     .then(data => {
    //         if (data.status) {
    //             // Save asset id
    //             document.getElementById("logo_file_id").value = data.asset_id;
    //             // Update logo everywhere
    //             document.getElementById("galleryLogo").src = data.url;
    //             document.querySelectorAll(".gallery-logo-image").forEach(img => {
    //                 img.src = data.url;
    //             });
    //             modal.classList.remove("active");
    //             selectedLogoFile = null;
    //         }
    //     })
    //     .catch(err => console.error("Upload Error:", err))
    //     .finally(() => {
    //         // 🔁 Reset button
    //         $(".directSubmit").prop("disabled", false);
    //         saveBtn.disabled = false;
    //         saveBtn.classList.remove("opacity-70", "cursor-not-allowed");
    //         saveText.textContent = "Save";
    //     });
    // });
    // Prevent default behavior
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea1.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    // Highlight on dragover
    dropArea1.addEventListener('dragover', () => dropArea1.classList.add('drag-over'));
    dropArea1.addEventListener('dragleave', () => dropArea1.classList.remove('drag-over'));

    // On drop
    dropArea1.addEventListener('drop', e => {
        const file = e.dataTransfer.files[0];
        if (!file) return;

        document.getElementById("modalLogoOverlay").classList.remove("active");
        cropModal.classList.add("active");

        loadImage(file);
    });

    document
        .getElementById("file-upload-cover")
        .addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (!file) return;

            selectedCoverFile = file;

            // Preview only
            const imageUrl = URL.createObjectURL(file);
            document.getElementById("gallery-cover-preview-image").src = imageUrl;
        });

    // Prevent default behavior
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea2.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    // Highlight on dragover
    dropArea2.addEventListener('dragover', () => dropArea2.classList.add('drag-over'));
    dropArea2.addEventListener('dragleave', () => dropArea2.classList.remove('drag-over'));

    // On drop
    dropArea2.addEventListener('drop', e => {
        dropArea2.classList.remove('drag-over');

        const file = e.dataTransfer.files[0];
        if (!file) return;

        selectedCoverFile = file;

        const imageUrl = URL.createObjectURL(file);
        document.getElementById("gallery-cover-preview-image").src = imageUrl;
    });

    saveCoverBtn.addEventListener("click", function () {

        if (!selectedCoverFile) {
            alert("Please select a cover image first");
            return;
        }

        // Processing state
        saveCoverBtn.disabled = true;
        saveCoverBtn.classList.add("opacity-70", "cursor-not-allowed");
        saveCoverText.textContent = "Saving...";
        $(".directSubmit").prop("disabled", true);
        let formData = new FormData();
        formData.append("files", selectedCoverFile);

        fetch("/gallery/coverimage/upload", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData,
        })
            .then(async res => {
                const data = await res.json();

                // Handle validation / server errors
                if (!res.ok) {
                    throw data;
                }

                return data;
            })
            .then(data => {
                // ✅ SUCCESS
                document.getElementById("cover_file_id").value = data.asset_id;
                document.getElementById("galleryCover").src = data.url;
                document.getElementById("gallery-cover-preview-image").src = data.url;

                modalCover.classList.remove("active");
                selectedCoverFile = null;
            })
            .catch(err => {
                // ✅ ERROR (422 / 500)
                let errorMsg = "Something went wrong";

                if (err.message) {
                    errorMsg = err.message;
                } else if (err.errors && err.errors.files) {
                    errorMsg = err.errors.files[0];
                }

                Lobibox.notify("error", {
                    rounded: false,
                    delay: 8000,
                    delayIndicator: true,
                    position: "bottom right",
                    icon: 'fa fa-times-circle',
                    msg: errorMsg,
                });
            })
            .finally(() => {
                $(".directSubmit").prop("disabled", false);
                saveCoverText.textContent = "Save";
                saveCoverBtn.disabled = false;
                saveCoverBtn.classList.remove("opacity-70", "cursor-not-allowed");
            });

    });



    // Prevent default behavior


    // Highlight on dragover


    // On drop


    function createImageBlock(imageUrl, isFeatured = false, assetId) {
        return `
        <div class="relative w-[162px] image-block" data-asset-id="${assetId}">
            <img src="${imageUrl}" class="w-[162px] h-[162px] rounded border">

            ${isFeatured
                ? `<div class="featured-badge">Featured Image</div>`
                : `<button type="button" class="set-featured-btn">Set Featured</button>`
            }
        </div>
    `;
    }

    // Delegated click
    $(document).on("click", ".set-featured-btn", function (e) {
        e.preventDefault();

        const block = $(this).closest(".image-block");
        const assetId = block.data("asset-id");

        // Find the previously featured block (if any)
        const prevFeatured = $(".image-block").has(".featured-badge");

        if (prevFeatured.length && !prevFeatured.is(block)) {
            // 1️⃣ Remove only the badge from previous block
            prevFeatured.find(".featured-badge").remove();
            // 2️⃣ Show the "Set Featured" button on previous block
            prevFeatured.append(`<button type="button" class="set-featured-btn">Set Featured</button>`);

        }

        // Hide clicked button and add badge if not already there
        if (block.find(".featured-badge").length === 0) {
            $(this).hide();
            block.append(`<div class="featured-badge">Featured Image</div>`);
        }

        // Update hidden input for backend
        $("#featured_image_input").val(assetId);
    });


}

//Krishna Js for cms
document.addEventListener("click", function (e) {
    if (e.target.id === "addItemsBlock") {
        let wrapper = document.getElementById("serviceWrapper1");
        let current = wrapper.querySelectorAll(".items-block").length;

        let newIndex = current;
        let url = document.getElementById("coreserviceroute").value;

        fetch(url + "?index=" + newIndex)
            .then((res) => res.text())
            .then((html) => {
                wrapper.insertAdjacentHTML("beforeend", html);
            });
    }
});
document.addEventListener("click", function (e) {
    // Add Bullet
    if (e.target.classList.contains("addBullet")) {
        let serviceIndex = e.target.dataset.service;
        let wrapper = e.target.closest(".bullet-wrapper");
        let template = wrapper.querySelector(".template");

        let clone = template.cloneNode(true);
        clone.classList.remove("hidden", "template");

        let total = wrapper.querySelectorAll(
            ".bullet-item:not(.template)"
        ).length;
        let input = clone.querySelector("input");

        input.name = `services[${serviceIndex}][bullets][${total}]`;

        wrapper.insertBefore(clone, e.target);
    }

    // Remove Bullet
});

// --------- C) Delegate Remove Buttons ----------
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("removeService")) {
        e.target.closest(".service-block").remove();
    }

    if (e.target.classList.contains("removeItem")) {
        e.target.closest(".items-block").remove();
    }

    if (e.target.classList.contains("removeBullet")) {
        e.target.closest(".bullet-item").remove();
    }
});

$(".tab-btn").on("click", function () {
    const url = $(this).data("url");
    // Switch active class
    $(".tab-btn")
        .removeClass(
            "active active-tab bg-black text-sm font-medium border border-black text-white"
        )
        .addClass("text-gray-500");

    $(this)
        .addClass(
            "active active-tab bg-black text-sm font-medium border border-black-500 text-white"
        )
        .removeClass("text-gray-500");
    // AJAX load partial
    $("#tabContent").html("<div class='p-10 text-center'>Loading...</div>");
    $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
            $("#tabContent").html(response);
            // Initialize Summernote only if it's loaded and elements exist
            if (typeof $.fn.summernote !== "undefined") {
                $("#tabContent").find(".summernote").summernote({
                    height: 250,
                });
            }
        },
        error: function () {
            $("#tabContent").html(
                "<p class='text-red-500 p-4'>Failed to load content</p>"
            );
        },
    });
});
$("#viewnoted").on("click", function () {
    $("#rejectArtistModalBackground").css("display", "flex");
    $("#rejectArtistModal").show();
});
$(document).on("click", ".cancelNotPopHtml", function () {
    $(".notification-banner").hide();
});
//End Krishna CMS js


if ($("#artist_create_update_form").length) {
    $("#viewnoted").on("click", function () {
        $("#rejectArtistModalBackground").css("display", "flex");
        $("#rejectArtistModal").show();
    });
    $(document).on("click", "#rejectArtist", function () {
        let rejectedArtistId = $(this).data("id");
        $("#reject_id").val(rejectedArtistId);
        $("#rejectArtistModalBackground").css("display", "flex");
        $("#rejectArtistModal").show();
    });

    $("#closeRejectModal, #cancelReject").on("click", function () {
        $("#rejectArtistModal").hide();
        $("#rejectArtistModalBackground").hide();
    });

    $(".cancelNotPopHtml").on("click", function () {
        $("#settings-alert").hide();
    });


    // Close modal when clicking on background only (not on modal content)
    $("#rejectArtistModalBackground").on("click", function (e) {
        if (e.target === this) {
            $("#rejectArtistModal").hide();
            $("#rejectArtistModalBackground").hide();
        }
    });


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
            url: _publicPath + "/artist/" + selectedArtistId,
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


if ($("#size_weight_block").length) {
    // 1. The Logic Function
    function toggleDimensionInputs(event) {
        var selectElement = document.getElementById('dimensions-select');
        if (!selectElement) return; // Safety check
        document.getElementById('height-text-artwork').innerHTML = 'Height';
        document.getElementById('width-text-artwork').innerHTML = 'Width';
        var selected = selectElement.value;
        //alert(selected)
        // List of all group IDs to hide first
        var ids = [
            'height-group',
            'width-group',
            'depth-group',
            'length-group',
            'diameter-group',
            'duration-group',
            'hours-group',
            'minutes-group',
            'seconds-group',
            'aspect_ratio-group',
            'custom_dimensions-group',
        ];

        // Hide all inputs initially
        ids.forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });

        document.getElementById('size-unit-container').style.display = 'block';
        document.getElementById('frame_block').style.display = 'inline-grid';
        document.getElementById('artwork_weight_block').style.display = 'inline-grid';

        if (event && event.type === 'change') {
            $('#height-input, #width-input').val('').trigger('click');
        }
        // Show specific inputs based on selection
        if (selected === 'Pixel Dimensions (px x px)') {

            $('#height-input, #width-input').removeClass('numberonly');
            $('#height-input, #width-input').removeClass('numberonlyvalid');

            document.getElementById('height-text-artwork').innerHTML = 'Px';
            document.getElementById('width-text-artwork').innerHTML = 'Px';

            document.getElementById('height-group').style.display = 'block';
            document.getElementById('width-group').style.display = 'block';
            document.getElementById('size-unit-container').style.display = 'none';

            document.getElementById('artwork_weight_block').style.display = 'none';

            document.getElementById('frame_block').style.display = 'none';

        } else if (selected === 'H x W') {
            document.getElementById('height-group').style.display = 'block';
            document.getElementById('width-group').style.display = 'block';
            if (event && event.type === 'change') {
                $('#height-input, #width-input').addClass('numberonly');
            } else {
                $('#height-input, #width-input').addClass('numberonlyvalid');
            }
        }
        else if (selected === 'H , W , D') {
            document.getElementById('height-group').style.display = 'block';
            document.getElementById('width-group').style.display = 'block';
            document.getElementById('depth-group').style.display = 'block';
            if (event && event.type === 'change') {
                $('#height-input, #width-input').addClass('numberonly');
            } else {
                $('#height-input, #width-input').addClass('numberonlyvalid');
            }
        }
        else if (selected === 'L x W x H') {
            document.getElementById('length-group').style.display = 'block';
            document.getElementById('width-group').style.display = 'block';
            document.getElementById('height-group').style.display = 'block';
            if (event && event.type === 'change') {
                $('#height-input, #width-input').addClass('numberonly');
            } else {
                $('#height-input, #width-input').addClass('numberonlyvalid');
            }
        }
        else if (selected === 'Diameter') {
            document.getElementById('length-group').style.display = 'block';

        }
        else if (selected === 'Digital Art Dimensions') {
            document.getElementById('hours-group').style.display = 'block';
            document.getElementById('minutes-group').style.display = 'block';
            document.getElementById('seconds-group').style.display = 'block';
            document.getElementById('aspect_ratio-group').style.display = 'block';
            document.getElementById('size-unit-container').style.display = 'none';

            document.getElementById('frame_block').style.display = 'none';
            document.getElementById('artwork_weight_block').style.display = 'none';
        } else if (selected === 'Variable Dimensions') {
            document.getElementById('hours-group').style.display = 'none';
            document.getElementById('minutes-group').style.display = 'none';
            document.getElementById('seconds-group').style.display = 'none';

            document.getElementById('custom_dimensions-group').style.display = 'block';

        } else {
            document.getElementById('height-group').style.display = 'block';
            document.getElementById('width-group').style.display = 'block';
            document.getElementById('depth-group').style.display = 'block';
            // ONLY RUN THIS IF IT IS A 'CHANGE' EVENT
            if (event && event.type === 'change') {
                $('#height-input, #width-input').addClass('numberonly');
            } else {
                $('#height-input, #width-input').addClass('numberonlyvalid');
            }
        }
    }

    // 2. Event Listeners
    document.addEventListener("DOMContentLoaded", function () {
        var selectElement = document.getElementById('dimensions-select');

        if (selectElement) {
            // Run on Page Load (to show correct fields if editing or error returned)
            toggleDimensionInputs();

            // Run on Change (whenever user selects a new option)
            selectElement.addEventListener('change', toggleDimensionInputs);
        }
    });


    document.addEventListener("DOMContentLoaded", function () {

        // 1. Select the elements
        const frameRadios = document.querySelectorAll('input[name="frame"]');
        const frameBlock = document.getElementById('framed-dimensions-block');

        // 2. Define the toggle function
        function toggleFrameInputs() {
            // Find which radio button is currently checked
            const selectedFrame = document.querySelector('input[name="frame"]:checked');

            // If "framed" is selected, show the block. Otherwise, hide it.
            if (selectedFrame && selectedFrame.value === 'framed') {
                if (frameBlock) frameBlock.style.display = 'block';
            } else {
                if (frameBlock) frameBlock.style.display = 'none';

                // Clear the inputs so they are sent as empty (which Laravel converts to null)
                const frameH = document.getElementById('frame-height');
                const frameW = document.getElementById('frame-width');
                const frameD = document.getElementById('frame-depth');

                if (frameH) frameH.value = '';
                if (frameW) frameW.value = '';
                if (frameD) frameD.value = '';
                $('#frame-height, #frame-width, #frame-depth').trigger('click');
            }
        }

        // 3. Attach Event Listeners (Run whenever user clicks a radio button)
        frameRadios.forEach(radio => {
            radio.addEventListener('change', toggleFrameInputs);
        });

        // 4. Run once on Page Load (To show fields if editing an existing framed artwork)
        toggleFrameInputs();
    });
}

if ($("#art_medium_type_block").length) {
    document.addEventListener("DOMContentLoaded", function () {
        const mediumSelect = document.getElementById('medium-type-select');
        const otherContainer = document.getElementById('medium-other-container');
        const otherInput = document.getElementById('medium-other-input');

        function toggleOtherField() {
            // Check if the selected value is 'Other'
            if (mediumSelect.value === 'Other') {
                otherContainer.style.display = 'block';
                otherInput.setAttribute('required', 'required'); // Make it required
            } else {
                otherContainer.style.display = 'none';
                otherInput.removeAttribute('required'); // Remove required
                otherInput.value = ''; // Optional: Clear value if they switch away
            }
        }

        // Run on change
        mediumSelect.addEventListener('change', toggleOtherField);

        // Run on load (to handle validation errors or edit pages)
        toggleOtherField();
    });
}
if ($("#art_materials_block").length) {
    document.addEventListener("DOMContentLoaded", function () {
        // ------------------------------------
        // LOGIC FOR MATERIALS DROPDOWN
        // ------------------------------------
        const materialSelect = document.getElementById('materials-select');
        const materialOtherContainer = document.getElementById('materials-other-container');
        const materialOtherInput = document.getElementById('materials-other-input');

        function toggleMaterialOtherField() {
            if (materialSelect.value === 'Other') {
                materialOtherContainer.style.display = 'block';
                materialOtherInput.setAttribute('required', 'required'); // Make mandatory
            } else {
                materialOtherContainer.style.display = 'none';
                materialOtherInput.removeAttribute('required');
                materialOtherInput.value = ''; // Clear value
            }
        }

        // Run on change
        materialSelect.addEventListener('change', toggleMaterialOtherField);

        // Run on load
        toggleMaterialOtherField();
    });
}



if ($("#lead-details-page").length) {

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Datepicker
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d", // Format: 2026-02-01
            allowInput: true,     // Allows manual typing if needed
            altInput: true,       // Shows a nicer format to the user
            altFormat: "F j, Y",  // User sees: February 1, 2026
        });

        // Initialize Timepicker
        flatpickr(".timepicker", {
            enableTime: true,
            noCalendar: true,     // Hide calendar, only show time
            dateFormat: "H:i",    // 24h format for backend
            time_24hr: false,     // Set to true if you want 24h clock, false for AM/PM
            altInput: true,
            altFormat: "h:i K",   // User sees: 01:30 PM
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('taskModal');
        const openBtn = document.getElementById('openTaskModal');
        const closeBtn = document.getElementById('closeTaskModalBtn');
        const closeX = document.getElementById('closeTaskModalX');
        const backdrop = document.getElementById('taskModalBackdrop');

        function toggleModal() {
            modal.classList.toggle('hidden');
        }

        if (openBtn) openBtn.addEventListener('click', toggleModal);
        if (closeBtn) closeBtn.addEventListener('click', toggleModal);
        if (closeX) closeX.addEventListener('click', toggleModal);
        if (backdrop) backdrop.addEventListener('click', toggleModal);
    });

    document.addEventListener('DOMContentLoaded', function () {
        // --- NOTE MODAL TOGGLE LOGIC ---
        const noteModal = document.getElementById('noteModal');
        const openNoteBtn = document.getElementById('openNoteModal');
        const closeNoteBtn = document.getElementById('closeNoteModalBtn');
        const closeNoteX = document.getElementById('closeNoteModalX');
        const noteBackdrop = document.getElementById('noteModalBackdrop');

        function toggleNoteModal() {
            noteModal.classList.toggle('hidden');
        }

        if (openNoteBtn) openNoteBtn.addEventListener('click', toggleNoteModal);
        if (closeNoteBtn) closeNoteBtn.addEventListener('click', toggleNoteModal);
        if (closeNoteX) closeNoteX.addEventListener('click', toggleNoteModal);
        if (noteBackdrop) noteBackdrop.addEventListener('click', toggleNoteModal);


        // --- WITH TASK CHECKBOX LOGIC ---
        const withTaskCheckbox = document.getElementById('with_task');
        const taskFieldsContainer = document.getElementById('noteTaskFields'); // Contains Date, Time AND Type

        const noteDate = document.getElementById('note_date');
        const noteTime = document.getElementById('note_time');
        const noteType = document.getElementById('note_task_type'); // Get the Type dropdown

        if (withTaskCheckbox && taskFieldsContainer) {
            withTaskCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    // Show Date, Time, Type
                    taskFieldsContainer.classList.remove('hidden');

                    // Add Validation to all 3
                    noteDate.setAttribute('required', 'required');
                    noteTime.setAttribute('required', 'required');
                    noteType.setAttribute('required', 'required');
                } else {
                    // Hide Date, Time, Type
                    taskFieldsContainer.classList.add('hidden');

                    // Remove Validation from all 3
                    noteDate.removeAttribute('required');
                    noteTime.removeAttribute('required');
                    noteType.removeAttribute('required');

                    // Reset values
                    noteDate.value = '';
                    noteTime.value = '';
                    noteType.value = '';
                }
            });
        }
    });

    const logCallModal = document.getElementById('logCallModal');
    const openLogCallBtn = document.getElementById('openLogCallModal');
    const closeLogCallBtn = document.getElementById('closeLogCallModalBtn');
    const closeLogCallX = document.getElementById('closeLogCallModalX');
    const logCallBackdrop = document.getElementById('logCallModalBackdrop');

    function toggleLogCallModal() {
        logCallModal.classList.toggle('hidden');
    }

    if (openLogCallBtn) openLogCallBtn.addEventListener('click', toggleLogCallModal);
    if (closeLogCallBtn) closeLogCallBtn.addEventListener('click', toggleLogCallModal);
    if (closeLogCallX) closeLogCallX.addEventListener('click', toggleLogCallModal);
    if (logCallBackdrop) logCallBackdrop.addEventListener('click', toggleLogCallModal);


    const callWithTaskCheckbox = document.getElementById('call_with_task');
    const callTaskFields = document.getElementById('logCallTaskFields');

    const callTaskDate = document.getElementById('call_task_date');
    const callTaskTime = document.getElementById('call_task_time');
    const callTaskType = document.getElementById('call_task_type');

    if (callWithTaskCheckbox && callTaskFields) {
        callWithTaskCheckbox.addEventListener('change', function () {
            if (this.checked) {
                // Show Fields
                callTaskFields.classList.remove('hidden');

                // Add Required Attributes
                callTaskDate.setAttribute('required', 'required');
                callTaskTime.setAttribute('required', 'required');
                callTaskType.setAttribute('required', 'required');
            } else {
                // Hide Fields
                callTaskFields.classList.add('hidden');

                // Remove Required Attributes
                callTaskDate.removeAttribute('required');
                callTaskTime.removeAttribute('required');
                callTaskType.removeAttribute('required');

                // Clear values
                callTaskDate.value = '';
                callTaskTime.value = '';
                callTaskType.value = '';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {

        const logsBtn = document.getElementById('logsDropdownBtn');
        const logsMenu = document.getElementById('logsDropdownMenu');

        if (logsBtn && logsMenu) {
            logsBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                logsMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function (e) {
                if (!logsBtn.contains(e.target) && !logsMenu.contains(e.target)) {
                    logsMenu.classList.add('hidden');
                }
            });
        }

        // --- 2. MODAL OPEN/CLOSE LOGIC ---
        const logModal = document.getElementById('logCallModal');
        const closeLogBtn = document.getElementById('closeLogCallModalBtn');
        const closeLogX = document.getElementById('closeLogCallModalX');
        const logBackdrop = document.getElementById('logCallModalBackdrop');

        // This function opens the modal and sets up the fields
        // --- GLOBAL OPEN FUNCTION ---
        window.openLogModal = function (type) {
            // 1. Close Dropdown if open
            const logsMenu = document.getElementById('logsDropdownMenu');
            if (logsMenu) logsMenu.classList.add('hidden');

            // 2. Set Modal Title (e.g., "Log Meeting", "Log Email")
            const titleEl = document.getElementById('logModalTitle');
            const typeInput = document.getElementById('log_type_input');

            if (titleEl) titleEl.textContent = 'Log ' + type.charAt(0).toUpperCase() + type.slice(1);
            if (typeInput) typeInput.value = type;

            // 3. Get Wrapper Elements
            const durationWrapper = document.getElementById('logDurationWrapper');
            const callOutcomeWrapper = document.getElementById('logCallOutcomeWrapper');
            const meetingOutcomeWrapper = document.getElementById('logMeetingOutcomeWrapper');

            // 4. Get Input Elements (for validation)
            const durationInput = document.getElementById('log_duration');
            const callOutcomeInput = document.getElementById('call_outcome');
            const meetingOutcomeInput = document.getElementById('meeting_outcome');

            // 5. RESET: Hide all dynamic fields and remove required
            if (durationWrapper) durationWrapper.classList.add('hidden');
            if (callOutcomeWrapper) callOutcomeWrapper.classList.add('hidden');
            if (meetingOutcomeWrapper) meetingOutcomeWrapper.classList.add('hidden');

            if (durationInput) durationInput.removeAttribute('required');
            if (callOutcomeInput) callOutcomeInput.removeAttribute('required');
            if (meetingOutcomeInput) meetingOutcomeInput.removeAttribute('required');

            // 6. LOGIC BASED ON TYPE
            if (type === 'meeting') {
                // --- MEETING: Show Duration & Meeting Outcome ---
                if (durationWrapper) {
                    durationWrapper.classList.remove('hidden');
                    durationInput.setAttribute('required', 'required');
                }
                if (meetingOutcomeWrapper) {
                    meetingOutcomeWrapper.classList.remove('hidden');
                    meetingOutcomeInput.setAttribute('required', 'required');
                }
            }
            else if (type === 'call') {
                // --- CALL: Show Call Outcome ---
                if (callOutcomeWrapper) {
                    callOutcomeWrapper.classList.remove('hidden');
                    callOutcomeInput.setAttribute('required', 'required');
                }
            }
            // else if type == 'email' or 'whatsapp' -> We show neither (standard behavior)

            // 7. Open the Modal
            const logModal = document.getElementById('logCallModal');
            logModal.classList.remove('hidden');
        };

        function closeLogModal() {
            logModal.classList.add('hidden');
        }

        if (closeLogBtn) closeLogBtn.addEventListener('click', closeLogModal);
        if (closeLogX) closeLogX.addEventListener('click', closeLogModal);
        if (logBackdrop) logBackdrop.addEventListener('click', closeLogModal);


        // --- 3. "WITH TASK" LOGIC ---
        const callWithTaskCheckbox = document.getElementById('call_with_task');
        const callTaskFields = document.getElementById('logCallTaskFields');
        const tDate = document.getElementById('call_task_date');
        const tTime = document.getElementById('call_task_time');
        const tType = document.getElementById('call_task_type');

        if (callWithTaskCheckbox && callTaskFields) {
            callWithTaskCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    callTaskFields.classList.remove('hidden');
                    tDate.setAttribute('required', 'required');
                    tTime.setAttribute('required', 'required');
                    tType.setAttribute('required', 'required');
                } else {
                    callTaskFields.classList.add('hidden');
                    tDate.removeAttribute('required');
                    tTime.removeAttribute('required');
                    tType.removeAttribute('required');
                    // Reset values
                    tDate.value = '';
                    tTime.value = '';
                    tType.value = '';
                }
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {

        // 1. Define the Filter Function
        window.filterTimeline = function (category, btnElement) {
            // A. Reset All Buttons to Gray
            const buttons = document.querySelectorAll('.filter-tab');
            buttons.forEach(btn => {
                btn.className = 'filter-tab px-4 py-2 bg-white text-gray-600 hover:bg-gray-50 rounded-md text-sm font-medium border border-gray-200 flex items-center gap-2 transition whitespace-nowrap';
            });

            // B. Set Active Style (Blue for All, Green for others)
            if (btnElement) {
                if (category === 'all') {
                    btnElement.className = 'filter-tab active px-4 py-2 bg-[#155e96] text-white rounded-md text-sm font-medium flex items-center gap-2 shadow-sm whitespace-nowrap transition';
                } else {
                    btnElement.className = 'filter-tab active px-4 py-2 bg-[#84cc16] text-white rounded-md text-sm font-medium flex items-center gap-2 shadow-sm whitespace-nowrap transition';
                }
            }

            // C. Filter Items Logic
            const items = document.querySelectorAll('.timeline-item');
            items.forEach(item => {
                const itemCat = item.getAttribute('data-category');
                
                if (category === 'all') {
                    item.style.display = 'block';
                } else {
                    // Show if match, hide if not
                    if (itemCat === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        };

        // ---------------------------------------------------------
        // 2. AUTO-TRIGGER DEFAULT ('SYSTEM') ON PAGE LOAD
        // ---------------------------------------------------------
        
        // Find the button that filters for 'system'
        // We look for a button that has "system" in its onclick attribute
        const systemBtn = Array.from(document.querySelectorAll('.filter-tab')).find(btn => 
            btn.getAttribute('onclick') && btn.getAttribute('onclick').includes("'system'")
        );

        if (systemBtn) {
            // If button exists, simulate the filter click
            window.filterTimeline('system', systemBtn);
        } else {
            // Fallback: If button not found, just hide non-system items manually
            const items = document.querySelectorAll('.timeline-item');
            items.forEach(item => {
                if (item.getAttribute('data-category') === 'system') {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    });
}


if ($("#assignLeadsModal").length) {
    // Define Global Functions
    window.closeAssignModal = function () {
        document.getElementById('assignLeadsModal').classList.add('hidden');
    }

    window.openAssignModal = function () {
        document.getElementById('assignLeadsModal').classList.remove('hidden');
    }

    $(document).ready(function () {

        // Helper function to update UI state
        function updateBulkActions() {
            var selectedCount = $('.lead-checkbox:checked').length;
            $('#selected-count').text(selectedCount);

            if (selectedCount > 0) {
                $('#bulk-actions').removeClass('hidden');
            } else {
                $('#bulk-actions').addClass('hidden');
            }
        }

        // 1. Handle "Select All" click
        $(document).on('change', '#selectAllLeads', function () {
            var isChecked = $(this).prop('checked');
            // Select all checkboxes in the table
            $('.lead-checkbox').prop('checked', isChecked);
            updateBulkActions();
        });

        // 2. Handle Individual Checkbox click
        $(document).on('change', '.lead-checkbox', function () {
            updateBulkActions();

            // Update "Select All" state automatically
            var totalCheckboxes = $('.lead-checkbox').length;
            var checkedCheckboxes = $('.lead-checkbox:checked').length;

            $('#selectAllLeads').prop('checked', (totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes));
        });

        // 3. Open Modal
        $(document).on('click', '#openAssignModal', function (e) {
            e.preventDefault();
            window.openAssignModal();
        });

        // 5. Submit Assignment
        $(document).on('click', '#submitAssignLeads', function (e) {
            e.preventDefault();

            var selectedIds = [];
            $('.lead-checkbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            var agentId = $('#assign_agent_id').val();


            if (selectedIds.length === 0) {
                Lobibox.notify("error", {
                    rounded: false,
                    delay: 6000,
                    delayIndicator: true,
                    position: "bottom right",
                    icon: 'fa fa-times-circle',
                    msg: "Please select at least one lead.",
                });
                return;
            }
            if (!agentId) {
                Lobibox.notify("error", {
                    rounded: false,
                    delay: 6000,
                    delayIndicator: true,
                    position: "bottom right",
                    icon: 'fa fa-times-circle',
                    msg: "Please select a user to assign.",
                });
                return;
            }

            var $btn = $(this);
            var _leadAssingRoute = $btn.data("leadassignroute");
            $btn.prop('disabled', true).text('Assigning...');
            $.ajax({
                url: _leadAssingRoute,
                method: "POST",
                data: {
                    ids: selectedIds,
                    agent_id: agentId,
                    _token: $("meta[name='csrf-token']").attr("content"),
                },
                success: function (response) {
                    $btn.prop('disabled', false).text('Assign');
                    if (response.status_code == 200) {
                        window.closeAssignModal();
                        Lobibox.notify(response["type"] || "success", {
                            rounded: false,
                            delay: 8000,
                            delayIndicator: true,
                            position: "bottom right",
                            icon: 'fa fa-check-circle',
                            msg: response["message"],
                        });
                        // Reload page to reflect changes
                        //window.location.reload(); 
                        if (response["isPageListRefreshByAjax"]) {
                            // Only run serach() if it exists and is a function
                            if (typeof serach === "function") {
                                serach();
                            }
                        }
                    } else {
                        Lobibox.notify("error", {
                            rounded: false,
                            delay: 6000,
                            delayIndicator: true,
                            position: "bottom right",
                            icon: 'fa fa-times-circle',
                            msg: 'Something went wrong: ' + (response.message || 'Unknown error'),
                        });
                    }
                },
                error: function (xhr) {
                    $btn.prop('disabled', false).text('Assign');
                    var msg = 'Error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        msg = xhr.responseText;
                    }
                    Lobibox.notify("error", {
                        rounded: false,
                        delay: 6000,
                        delayIndicator: true,
                        position: "bottom right",
                        icon: 'fa fa-times-circle',
                        msg: msg,
                    });
                    console.error(xhr);
                }
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {

    // Check if the delete modal exists in the DOM
    if ($("#deleteConfirmationModal").length) {

        // ---------------------------------------------------------
        // 1. BULK DELETE CLICK (Toolbar Button)
        // ---------------------------------------------------------
        $(document).on('click', '#bulkDeleteLeads', function (e) {
            e.preventDefault();

            // Collect selected IDs from checkboxes
            var selectedIds = [];
            $('.lead-checkbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            // Validation: Check if any lead is selected
            if (selectedIds.length === 0) {
                Lobibox.notify("error", {
                    rounded: false,
                    delay: 6000,
                    delayIndicator: true,
                    position: "bottom right",
                    icon: 'fa fa-times-circle',
                    msg: "Please select at least one lead to delete.",
                });
                return;
            }

            var $btn = $(this);
            var _deleteRoute = $btn.data("leaddeleteroute");

            // --- FIX: Attach data to the CORRECT button ID from your HTML ---
            $('#confirmMutipleDeleteLead').data('route', _deleteRoute);
            $('#confirmMutipleDeleteLead').data('ids', selectedIds);

            // Open the modal
            openDeleteModal('deleteConfirmationModal');
        });

        // ---------------------------------------------------------
        // 2. CLOSE MODAL LOGIC (X Icon & Cancel Button)
        // ---------------------------------------------------------
        $(document).on('click', '#closeMultiplLeadDeleteModal, #cancelMultiplLeadDelete', function () {
            closeDeleteModal('deleteConfirmationModal');
        });

        // ---------------------------------------------------------
        // 3. CONFIRM DELETE CLICK (Button inside Modal)
        // ---------------------------------------------------------
        $(document).on('click', '#confirmMutipleDeleteLead', function (e) {
            e.preventDefault();
            
            var $btn = $(this);
            
            // Retrieve data attached in Step 1
            var _deleteRoute = $btn.data('route');
            var selectedIds = $btn.data('ids');

            // Debugging check
            if(!_deleteRoute || !selectedIds) {
                alert("Error: Missing route or IDs");
                return;
            }

            // Disable button to prevent double-click
            $btn.prop('disabled', true).text('Deleting...');

            $.ajax({
                url: _deleteRoute,
                method: "POST",
                data: {
                    ids: selectedIds,
                    _token: $("meta[name='csrf-token']").attr("content"),
                },
                success: function (response) {
                    $btn.prop('disabled', false).text('Delete Lead');
                    
                    // Close the modal
                    closeDeleteModal('deleteConfirmationModal');

                    if (response.status_code == 200) {
                        Lobibox.notify("success", {
                            rounded: false,
                            delay: 6000,
                            delayIndicator: true,
                            position: "bottom right",
                            icon: 'fa fa-check-circle',
                            msg: response.message,
                        });

                        // Refresh Logic
                        if (response["isPageListRefreshByAjax"]) {
                            if (typeof serach === "function") {
                                serach(); // Your custom search/refresh function
                            } else {
                                window.location.reload();
                            }
                        }
                    } else {
                        Lobibox.notify("error", {
                            rounded: false,
                            delay: 6000,
                            delayIndicator: true,
                            position: "bottom right",
                            icon: 'fa fa-times-circle',
                            msg: 'Something went wrong: ' + (response.message || 'Unknown error'),
                        });
                    }
                },
                error: function (xhr) {
                    $btn.prop('disabled', false).text('Delete Lead');
                    closeDeleteModal('deleteConfirmationModal');

                    var msg = 'Error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        msg = xhr.responseText;
                    }
                    Lobibox.notify("error", {
                        rounded: false,
                        delay: 6000,
                        delayIndicator: true,
                        position: "bottom right",
                        icon: 'fa fa-times-circle',
                        msg: msg,
                    });
                }
            });
        });
    }

    // ---------------------------------------------------------
    // 4. HELPER FUNCTIONS (Global)
    // ---------------------------------------------------------
    
    // Global variable for single delete form ID (from your old code)
    window.deleteFormId = null;

    // Open Modal Function
    window.openDeleteModal = function(modalIdOrFormId) {
        // If passing a specific modal ID string (like 'deleteConfirmationModal')
        if(typeof modalIdOrFormId === 'string' && document.getElementById(modalIdOrFormId)) {
             document.getElementById(modalIdOrFormId).classList.remove('hidden');
        } 
        // Logic for single delete (if you passed a form ID)
        else {
             window.deleteFormId = modalIdOrFormId; 
             document.getElementById('deleteConfirmationModal').classList.remove('hidden');
        }
    };

    // Close Modal Function
    window.closeDeleteModal = function() {
        document.getElementById('deleteConfirmationModal').classList.add('hidden');
        window.deleteFormId = null;
    };

});


$(document).ready(function() {
    
    // Handle Toggle Change
    $(document).on('change', '.user-status-toggle', function(e) {
        var $checkbox = $(this);
        var userId = $checkbox.data('id');
        var _actInactiveRoute = $checkbox.data('route');
        var isChecked = $checkbox.is(':checked');
        var newStatus = isChecked ? 'active' : 'inactive';
        var statusText = isChecked ? 'Activate' : 'Deactivate';

        // 1. Confirmation Dialog
       /*if(!confirm('Are you sure you want to ' + statusText + ' this user?')) {
            $checkbox.prop('checked', !isChecked);
            return;
        }*/

        // 2. Disable temporarily to prevent double clicks
        $checkbox.prop('disabled', true);

        // 3. AJAX Request
        $.ajax({
            url: _actInactiveRoute,
            type: "POST",
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
                id: userId
            },
            success: function(response) {
                $checkbox.prop('disabled', false);

                if (response.status_code == 200) {
                    Lobibox.notify("success", {
                        rounded: false,
                        position: "bottom right",
                        icon: 'fa fa-check-circle',
                        msg: response.message
                    });
                    
                    // Update the text label next to the toggle without reloading
                    $('.status-text-' + userId).text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                    
                } else {
                    // Revert on server failure
                    $checkbox.prop('checked', !isChecked);
                    Lobibox.notify("error", {
                        rounded: false,
                        position: "bottom right",
                        icon: 'fa fa-times-circle',
                        msg: 'Something went wrong.'
                    });
                }
            },
            error: function(xhr) {
                // Revert on error
                $checkbox.prop('disabled', false);
                $checkbox.prop('checked', !isChecked);
                console.error(xhr);
                Lobibox.notify("error", {
                    rounded: false,
                    position: "bottom right",
                    icon: 'fa fa-times-circle',
                    msg: 'Error: Could not update status.'
                });
            }
        });
    });

});
