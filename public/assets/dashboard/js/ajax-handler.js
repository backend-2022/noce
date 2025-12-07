/* ===============================================================
   ========== AJAX REQUEST =======================================
   =============================================================== */
function ajaxRequest(config) {
    const defaultConfig = {
        headers: getDefaultHeaders(),
        processData: true,
        contentType: "application/x-www-form-urlencoded; charset=UTF-8",
    };
    return $.ajax({
        ...defaultConfig,
        ...config,
        headers: { ...defaultConfig.headers, ...config.headers },
    });
}

function getDefaultHeaders() {
    return {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
    };
}

/* ===============================================================
   ========== TOAST NOTIFICATIONS (Toastr) =======================
   =============================================================== */
// Configure Toastr defaults
if (typeof toastr !== "undefined") {
    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "3000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        rtl: true,
    };
}

function showSuccessAlert(
    message = "تمت العملية بنجاح",
    callback = null,
    title = "تم بنجاح"
) {
    if (typeof toastr === "undefined") {
        console.warn("toastr is not loaded");
        if (callback && typeof callback === "function") {
            setTimeout(callback, 100);
        }
        return;
    }
    toastr.success(message, title);
    if (callback && typeof callback === "function") {
        setTimeout(callback, 100);
    }
}

function showErrorAlert(
    message = "حدث خطأ غير متوقع",
    callback = null,
    title = "خطأ"
) {
    if (typeof toastr === "undefined") {
        console.warn("toastr is not loaded");
        if (callback && typeof callback === "function") {
            setTimeout(callback, 100);
        }
        return;
    }
    toastr.error(message, title);
    if (callback && typeof callback === "function") {
        setTimeout(callback, 100);
    }
}

/* ===============================================================
   ========== CONFIRMATION DIALOGS (SweetAlert) ==================
   =============================================================== */
function showAlert(options = {}) {
    if (typeof Swal === "undefined") {
        console.warn("SweetAlert2 is not loaded");
        if (options.callback && typeof options.callback === "function") {
            options.callback({ isConfirmed: true });
        }
        return Promise.resolve({ isConfirmed: true });
    }
    if (Swal.isVisible()) Swal.close();

    const defaultOptions = {
        title: "",
        text: "",
        html: null,
        icon: "info", // success | error | warning | info | question
        confirmButtonText: "حسناً",
        cancelButtonText: "إلغاء",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        timer: null,
        showConfirmButton: true,
        allowOutsideClick: true,
        callback: null,
        showLoading: null,
    };

    const config = { ...defaultOptions, ...options };

    return Swal.fire({
        title: config.title,
        text: config.text,
        html: config.html,
        icon: config.icon,
        showCancelButton: config.showCancelButton,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
        cancelButtonText: config.cancelButtonText,
        timer: config.timer,
        showConfirmButton: config.showConfirmButton,
        allowOutsideClick: config.allowOutsideClick,
        didOpen: () => {
            if (config.showLoading) {
                Swal.showLoading();
            }
        },
    }).then((result) => {
        if (config.callback && typeof config.callback === "function") {
            config.callback(result);
        }
        return result;
    });
}

function showConfirmAlert(
    title = "تأكيد العملية",
    text = "هل أنت متأكد من المتابعة؟",
    callback = null,
    confirmText = "نعم",
    cancelText = "إلغاء"
) {
    return showAlert({
        title: title,
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        callback: callback,
    });
}

function showProcessingAlert(
    title = "جارى المعالجة",
    callback = null,
    message = "جارى معالجة البيانات"
) {
    // Mark that a processing alert is open
    window.__processingAlertOpen = true;
    return showAlert({
        title: title,
        text: message,
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false,
        showLoading: true,
        callback: callback,
    });
}

/* ===============================================================
   ========== HANDLE ALERTS INFORMATION (CRUD Operations) ========
   =============================================================== */
function handleCreate(
    actionUrl,
    successMessage = "تم الإنشاء بنجاح",
    errorMessage = "حدث خطأ في الإنشاء",
    callback = null
) {
    ajaxRequest({
        url: actionUrl,
        type: "POST",
        success: function (response) {
            showSuccessAlert(successMessage);
        },
    });
}

function handleUpdate(
    actionUrl,
    type = "PUT",
    successMessage = "تم التحديث بنجاح",
    errorMessage = "حدث خطأ في التحديث",
    callback = null
) {
    ajaxRequest({
        url: actionUrl,
        type: type,
        success: function (response) {
            showSuccessAlert(successMessage);
        },
    });
}

function handleDelete(
    actionUrl,
    successMessage = "تم الحذف بنجاح",
    errorMessage = "حدث خطأ في الحذف",
    rowElement = null,
    callback = null,
    confirmTitle = "هل أنت متأكد؟",
    confirmText = "هل تريد حذف هذا العنصر؟\nلا يمكن التراجع عن هذا الإجراء!"
) {
    showConfirmAlert(
        confirmTitle,
        confirmText,
        (result) => {
            if (result.isConfirmed) {
                ajaxRequest({
                    url: actionUrl,
                    type: "DELETE",
                    success: function (response) {
                        // Remove row immediately before showing toast
                        if (rowElement) {
                            $(rowElement).fadeOut(300, function() {
                                $(this).remove();
                            });
                        }
                        // Show success message
                        showSuccessAlert(successMessage);
                        // Execute callback if provided
                        if (callback && typeof callback === "function") {
                            callback();
                        }
                    },
                    error: function (xhr) {
                        showErrorAlert(xhr.responseJSON?.message || errorMessage);
                    },
                });
            }
        },
        "نعم، احذف",
        "إلغاء"
    );
}

function handleBulkDelete(
    actionUrl,
    ids = [],
    {
        successMessage = "تم حذف العناصر المحددة بنجاح",
        errorMessage = "حدث خطأ أثناء حذف العناصر المحددة",
        confirmTitle = "هل أنت متأكد؟",
        confirmText = "هل تريد حذف العناصر المحددة؟ لا يمكن التراجع عن هذا الإجراء!",
        confirmButtonText = "نعم، احذف",
        cancelButtonText = "إلغاء",
        requestMethod = "POST",
        additionalData = {},
        onSuccess = null,
        onError = null,
    } = {}
) {
    if (!Array.isArray(ids) || ids.length === 0) {
        showErrorAlert("يرجى اختيار عنصر واحد على الأقل للحذف.");
        return;
    }

    showConfirmAlert(
        confirmTitle,
        confirmText,
        function(result) {
            if (!result.isConfirmed) {
                return;
            }

            ajaxRequest({
                url: actionUrl,
                type: requestMethod,
                data: {
                    ids: ids,
                    _method: "DELETE",
                    ...additionalData,
                },
                success: function(response) {
                    showSuccessAlert(response?.message || successMessage);
                    if (typeof onSuccess === "function") {
                        onSuccess(response);
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || errorMessage;
                    showErrorAlert(message);
                    if (typeof onError === "function") {
                        onError(xhr);
                    }
                },
            });
        },
        confirmButtonText,
        cancelButtonText
    );
}

function handleRestore(
    actionUrl,
    successMessage = "تم الاستعادة بنجاح",
    errorMessage = "حدث خطأ في الاستعادة",
    rowElement = null
) {
    const confirmTitle = "تأكيد الاستعادة";
    const confirmMessage = "هل أنت متأكد من رغبتك في استعادة هذا العنصر؟";
    const buttonText = "نعم، استعد";

    showConfirmAlert(
        confirmTitle,
        confirmMessage,
        (result) => {
            if (result.isConfirmed) {
                ajaxRequest({
                    url: actionUrl,
                    type: "POST",
                    success: function (response) {
                        showSuccessAlert(successMessage, function () {
                            if (rowElement) {
                                $(rowElement).remove();
                            }
                        });
                    },
                    error: function (xhr) {
                        showErrorAlert(xhr.responseJSON?.message || errorMessage);
                    },
                });
            }
        },
        buttonText
    );
}

function handleForceDelete(
    actionUrl,
    successMessage = "تم الحذف النهائي بنجاح",
    errorMessage = "حدث خطأ في الحذف النهائي",
    rowElement = null
) {
    const confirmTitle = "تأكيد الحذف النهائي";
    const confirmMessage =
        "هل أنت متأكد من رغبتك في حذف هذا العنصر نهائياً؟\n تحذير: لا يمكن التراجع عن هذا الإجراء.";
    const buttonText = "نعم، احذف نهائياً";

    showConfirmAlert(
        confirmTitle,
        confirmMessage,
        (result) => {
            if (result.isConfirmed) {
                ajaxRequest({
                    url: actionUrl,
                    type: "DELETE",
                    success: function (response) {
                        showSuccessAlert(successMessage, function () {
                            if (rowElement) {
                                $(rowElement).remove();
                            }
                        });
                    },
                    error: function (xhr) {
                        showErrorAlert(errorMessage);
                    },
                });
            }
        },
        buttonText
    );
}

function handleToggle(
    url,
    data,
    successMessage = "تم التحديث بنجاح",
    errorMessage = "حدث خطأ في التحديث",
    callback
) {
    if (typeof $ === "undefined") {
        return;
    }

    ajaxRequest({
        url: url,
        method: "POST",
        data: data,
        success: function (response) {
            showSuccessAlert(successMessage);
            if (callback && typeof callback === "function") {
                callback(response, true);
            }
        },
        error: function (xhr) {
            showErrorAlert(errorMessage);
            if (callback && typeof callback === "function") {
                callback(xhr, false);
            }
        },
    });
}

function handleDataLoad(
    url,
    method = "GET",
    data = null,
    successCallback,
    errorCallback = null,
    isSynchronous = false,
    errorMessage = "حدث خطأ أثناء تحميل البيانات"
) {
    if (typeof $ === "undefined") {
        return;
    }

    const ajaxConfig = {
        url: url,
        method: method,
        xhrFields: {
            withCredentials: true,
        },
        async: !isSynchronous,
        success: function (response) {
            if (successCallback && typeof successCallback === "function") {
                successCallback(response);
            }
        },
        error: function (xhr) {
            if (errorCallback && typeof errorCallback === "function") {
                errorCallback(xhr);
            } else {
                showErrorAlert(errorMessage);
            }
        },
    };

    if (data) {
        ajaxConfig.data = data;
    }

    ajaxRequest(ajaxConfig);
}

/* ===============================================================
   ========= HANDLE AJAX FORM SUBMISSION =========================
   =============================================================== */
function handleFormSubmission(formSelector, options = {}) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    const defaultOptions = {
        successMessage: "تم الحفظ بنجاح",
        errorMessage: "حدث خطأ في الخادم. يرجى المحاولة مرة أخرى.",
        redirectUrl: null,
        redirectImmediately: false, // If true, redirect immediately after showing toast without waiting
        showSuccessToast: true, // If false, don't show success toast
        beforeSubmit: null, // Function to call before submission
        afterSubmit: null, // Function to call after submission
    };

    const config = { ...defaultOptions, ...options };

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // Call before submit callback
        if (config.beforeSubmit && typeof config.beforeSubmit === "function") {
            if (!config.beforeSubmit()) {
                return false;
            }
        }

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');

        // Show loading state in submit button
        if (submitButton) {
            // Store original button content if not already stored
            if (!submitButton.dataset.originalHtml) {
                submitButton.dataset.originalHtml = submitButton.innerHTML;
            }

            submitButton.setAttribute("data-kt-indicator", "on");
            submitButton.disabled = true;

            const indicatorLabel =
                submitButton.querySelector(".indicator-label");
            const indicatorProgress = submitButton.querySelector(
                ".indicator-progress"
            );

            // Support for indicator-label/indicator-progress pattern
            if (indicatorLabel && indicatorProgress) {
                indicatorLabel.style.display = "none";
                indicatorProgress.style.display = "inline-block";
            } else {
                // Support for button-text pattern (settings style)
                const buttonText = submitButton.querySelector(".button-text");
                if (buttonText) {
                    // Add spinner before text
                    const spinner = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>';
                    buttonText.innerHTML = spinner + 'جارى المعالجة...';
                    submitButton.style.opacity = "0.7";
                    submitButton.style.cursor = "not-allowed";
                }
            }
        }
        if (form.dataset.submitting === "true") return;
        form.dataset.submitting = "true";

        ajaxRequest({
            url: this.action,
            type: this.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (config.afterSubmit && typeof config.afterSubmit === "function") {
                    try { config.afterSubmit(response); } catch (e) {}
                }

                // Get redirect URL
                const redirectUrl = response.data?.redirect || response.redirect || config.redirectUrl;

                // Show success toast only if showSuccessToast is true
                if (config.showSuccessToast) {
                    // If redirectImmediately is true, show toast and redirect immediately
                    if (config.redirectImmediately && redirectUrl) {
                        showSuccessAlert(config.successMessage);
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 100);
                    } else {
                        // Default behavior: wait for toast to close before redirecting
                        showSuccessAlert(config.successMessage, function () {
                            if (redirectUrl) {
                                window.location.href = redirectUrl;
                            }
                        });
                    }
                } else {
                    // If not showing toast, redirect immediately if needed
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                    }
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    // Check if there are general errors (not field-specific)
                    const errors = xhr.responseJSON.errors;

                    // If errors object is empty, show message in toastr
                    if (Object.keys(errors).length === 0) {
                        const serverMessage =
                            xhr.responseJSON?.message ||
                            xhr.responseJSON?.error ||
                            config.errorMessage;
                        showErrorAlert(serverMessage);
                        return;
                    }

                    const generalErrors = [];
                    const fieldErrors = {};

                    Object.keys(errors).forEach((field) => {
                        if (
                            field === "location" ||
                            field === "coordinates" ||
                            field === "general"
                        ) {
                            // These are general errors, not field-specific
                            generalErrors.push(
                                ...(Array.isArray(errors[field])
                                    ? errors[field]
                                    : [errors[field]])
                            );
                        } else {
                            fieldErrors[field] = errors[field];
                        }
                    });

                    // Display general errors in modal (use server messages)
                    if (generalErrors.length > 0) {
                        let errorList = '<ul style="text-align:right;">';
                        generalErrors.forEach((error) => {
                            errorList += `<li>${error}</li>`;
                        });
                        errorList += '</ul>';

                        const header = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) || 'خطأ';
                        Swal.close();
                        showAlert({
                            title: header,
                            html: errorList,
                            icon: 'error',
                            timer: null,
                            showConfirmButton: true,
                            confirmButtonText: 'حسناً',
                            allowOutsideClick: false,
                        });
                    }

                    // Handle field-specific errors
                    if (Object.keys(fieldErrors).length > 0) {
                        const modifiedXhr = {
                            ...xhr,
                            responseJSON: {
                                ...xhr.responseJSON,
                                errors: fieldErrors,
                            },
                        };
                        handleValidationErrors(modifiedXhr, form);
                    }

                    // Handle order items validation errors specifically
                    if (Object.keys(fieldErrors).length > 0) {
                        handleOrderItemsValidationErrors(fieldErrors);
                    }
                } else {
                    const serverMessage =
                        xhr.responseJSON?.message ||
                        xhr.responseJSON?.error ||
                        xhr.statusText ||
                        config.errorMessage;
                    showErrorAlert(serverMessage);
                }
            },
            complete: function () {
                delete form.dataset.submitting;

                // Hide loading state
                if (submitButton) {
                    submitButton.removeAttribute("data-kt-indicator");
                    submitButton.disabled = false;

                    const indicatorLabel =
                        submitButton.querySelector(".indicator-label");
                    const indicatorProgress = submitButton.querySelector(
                        ".indicator-progress"
                    );

                    // Support for indicator-label/indicator-progress pattern
                    if (indicatorLabel && indicatorProgress) {
                        indicatorLabel.style.display = "inline-block";
                        indicatorProgress.style.display = "none";
                    } else {
                        // Support for button-text pattern (settings style)
                        // Restore original button content
                        if (submitButton.dataset.originalHtml) {
                            submitButton.innerHTML = submitButton.dataset.originalHtml;
                        }
                        submitButton.style.opacity = "1";
                        submitButton.style.cursor = "pointer";
                    }
                }
            },
        });
    });
}

/* ===============================================================
   ========== VALIDATION HELPERS & FIELD FOCUS ===================
   =============================================================== */

function focusOnField(field, form) {
    if (!field || field.disabled || field.readOnly || field.type === "file") return;
    if (document.activeElement === field) return;

    const tagName = field.tagName.toUpperCase();
    const fieldType = field.type?.toLowerCase();

    scrollToField(field);

    field.focus({ preventScroll: true });

    const focusStrategies = {
        text: () => field.select(),
        email: () => field.select(),
        password: () => field.select(),
        number: () => field.select(),
        tel: () => field.select(),
        url: () => field.select(),
        search: () => field.select(),
        date: () => field.click(),
        "datetime-local": () => field.click(),
        time: () => field.click(),
        month: () => field.click(),
        week: () => field.click(),
        color: () => field.click(),
        checkbox: () => scrollToLabel(field, form),
        radio: () => scrollToLabel(field, form),
        file: () => focusFileInput(field),
        hidden: () => focusHiddenLinkedField(field, form),
    };

    // Run handler if exists
    if (focusStrategies[fieldType]) focusStrategies[fieldType]();

    // Handle by tag
    switch (tagName) {
        case "SELECT":
            if (field.classList.contains("form-select"))
                setTimeout(() => field.click(), 120);
            break;
        case "TEXTAREA":
            field.setSelectionRange(field.value.length, field.value.length);
            break;
        case "DIV":
        case "SPAN":
            const child = field.querySelector("input, select, textarea, button");
            if (child) focusOnField(child, form);
            break;
    }

    // Handle plugins
    handlePluginFocus(field);
}

function handleValidationErrors(xhr, form) {
    if (!xhr.responseJSON?.errors) return;

    clearValidationErrors(form);
    const errors = xhr.responseJSON.errors;
    let firstErrorField = null;

    for (const [field, messages] of Object.entries(errors)) {
        const input = findInputField(form, field);
        if (!input) continue;

        markFieldInvalid(input, messages);
        if (!firstErrorField) firstErrorField = input;
    }

    if (firstErrorField) scrollToErrorField(firstErrorField, form);
}

/* ----------------------- Sub Helpers For Focusing and Scrolling ----------------------- */

function scrollToField(field) {
    const offset = 150;
    const top = field.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top, behavior: "smooth" });
}

function scrollToLabel(field, form) {
    const label = form.querySelector(`label[for="${field.id}"]`);
    if (!label) return;
    const top = label.getBoundingClientRect().top + window.scrollY - 100;
    window.scrollTo({ top, behavior: "smooth" });
}

function focusHiddenLinkedField(field, form) {
    const related = form.querySelector(`[name="${field.name.replace("_type", "_id")}"]`);
    if (related && related.type !== "hidden") focusOnField(related, form);
}

function focusFileInput(field) {
    const wrapper = field.closest(".image-input, .file-input-wrapper");
    const btn = wrapper?.querySelector("button, .btn, .file-input-button");
    if (btn) {
        btn.focus();
        btn.click();
    }
}

function handlePluginFocus(field) {
    // DateRangePicker
    if (field.id === "date_range" || field.classList.contains("daterangepicker-input")) {
        if (typeof $ !== "undefined" && $(field).data("daterangepicker")) $(field).trigger("click");
        else field.click();
    }

    // Select2
    if (field.classList.contains("select2-hidden-accessible")) {
        const container = field.nextElementSibling?.querySelector(".select2-selection");
        if (container) container.focus();
    }
}

/* ------------------ Sub Helpers For Validation ------------------ */

function clearValidationErrors(form) {
    form.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
    form.querySelectorAll(".error-container").forEach(el => el.remove());

    // Clear image input validation
    form.querySelectorAll(".image-input.is-invalid").forEach(el => {
        el.classList.remove("is-invalid");
        const wrapper = el.querySelector(".image-input-wrapper");
        if (wrapper) {
            wrapper.style.border = "";
        }
    });
}

function findInputField(form, field) {
    let input = form.querySelector(`[name="${field}"]`);
    if (input) return input;

    // Handle nested fields like: user.name or images.0
    if (field.includes(".")) {
        const normalized = field.split(".").reduce((acc, key, i) =>
            i === 0 ? key : acc + `[${key}]`, "");
        input = form.querySelector(`[name="${normalized}"]`);
    }

    // Handle images field specifically
    if (!input && field === "images") input = form.querySelector(`[name="images[]"]`);
    if (!input && field.startsWith("images.")) input = form.querySelector(`[name="images[]"]`);
    if (!input && field.startsWith("light_theme_icon.")) input = form.querySelector(`[name="light_theme_icon"]`);
    if (!input && field.startsWith("dark_theme_icon.")) input = form.querySelector(`[name="dark_theme_icon"]`);

    return input;
}

function markFieldInvalid(input, messages) {
    const errorText = Array.isArray(messages) ? messages.join(", ") : messages;
    input.classList.add("is-invalid");

    // Handle Select2
    if (input.classList.contains("select2-hidden-accessible")) {
        input.nextElementSibling?.querySelector(".select2-selection")?.classList.add("is-invalid");
    }

    // Handle file input (image upload)
    if (input.type === "file" && input.closest(".image-input")) {
        const imageInput = input.closest(".image-input");
        imageInput.classList.add("is-invalid");

        // Add red border to image input wrapper
        const wrapper = imageInput.querySelector(".image-input-wrapper");
        if (wrapper) {
            wrapper.style.border = "2px solid #dc3545";
        }
    }

    const errorContainer = document.createElement("div");
    errorContainer.className = "error-container";
    errorContainer.innerHTML = `<span class="error-message text-danger" style="font-size:0.875rem;">${errorText}</span>`;

    const fieldContainer = input.closest(".fv-row") || input.closest(".form-group") || input.parentNode;
    if (fieldContainer) {
        fieldContainer.appendChild(errorContainer);
    } else {
        input.insertAdjacentElement("afterend", errorContainer);
    }

    // Real-time cleanup
    input.addEventListener("input", removeErrorOnChange);
    input.addEventListener("select2:select", removeErrorOnChange);
    input.addEventListener("change", removeErrorOnChange);
}

function removeErrorOnChange(e) {
    const input = e.target;
    input.classList.remove("is-invalid");

    // Handle image input cleanup
    if (input.type === "file" && input.closest(".image-input")) {
        const imageInput = input.closest(".image-input");
        imageInput.classList.remove("is-invalid");

        // Remove red border from image input wrapper
        const wrapper = imageInput.querySelector(".image-input-wrapper");
        if (wrapper) {
            wrapper.style.border = "";
        }
    }

    const fieldContainer = input.closest(".fv-row") || input.closest(".form-group") || input.parentNode;
    const errorContainer = fieldContainer?.querySelector(".error-container");
    if (errorContainer) {
        errorContainer.remove();
    }
}

function scrollToErrorField(field, form) {
    const container = field.closest(".fv-row, .col-md-6, .col-12") || field;
    const offsetTop = container.getBoundingClientRect().top + window.scrollY - window.innerHeight * 0.25;

    window.scrollTo({ top: offsetTop, behavior: "smooth" });
    setTimeout(() => focusOnField(field, form), 700);
}

function handleOrderItemsValidationErrors(errors) {
    // Use orderManager's method if available, otherwise do nothing
    if (window.orderManager && typeof window.orderManager.handleValidationErrors === 'function') {
        window.orderManager.handleValidationErrors(errors);
    }
}

/* ===============================================================
   ========== IMAGE UPLOAD PREVIEW ===============================
   =============================================================== */

function applyImagePreviewStyling(previewImg, uploadDiv, uploadText = null, defaultImageName = 'white_img.png') {
    if (previewImg && previewImg.src && !previewImg.src.includes(defaultImageName)) {
        if (uploadText) {
            uploadText.style.display = "none";
        }
        if (uploadDiv) {
            uploadDiv.style.background = "none";
            uploadDiv.style.border = "none";
            uploadDiv.style.padding = "0";
            uploadDiv.style.width = "max-content";
            uploadDiv.style.margin = "auto";
        }

        previewImg.style.width = "150px";
        previewImg.style.height = "150px";
        previewImg.style.objectFit = "cover";
        previewImg.style.display = "block";
    }
}


function setupImageUpload(uploadDivId, inputId, previewImgId, uploadTextId, defaultImageName = 'white_img.png') {
    const uploadDiv = document.getElementById(uploadDivId);
    const imageInput = document.getElementById(inputId);
    const previewImg = document.getElementById(previewImgId);
    const uploadText = document.getElementById(uploadTextId);

    if (!uploadDiv || !imageInput || !previewImg) {
        console.warn(`setupImageUpload: Missing required elements for ${uploadDivId}`);
        return;
    }

    // Apply preview styling on page load if image exists
    // Check if image is already loaded
    if (previewImg.complete && previewImg.naturalHeight !== 0) {
        applyImagePreviewStyling(previewImg, uploadDiv, uploadText, defaultImageName);
    } else {
        // Wait for image to load
        previewImg.addEventListener('load', function() {
            applyImagePreviewStyling(previewImg, uploadDiv, uploadText, defaultImageName);
        });
        // Also check immediately in case image is already loaded
        setTimeout(function() {
            applyImagePreviewStyling(previewImg, uploadDiv, uploadText, defaultImageName);
        }, 100);
    }

    // Handle click on upload div
    uploadDiv.addEventListener("click", () => {
        imageInput.click();
    });

    // Handle file selection
    imageInput.addEventListener("change", function() {
        // Clear any previous validation errors for this field
        this.classList.remove("is-invalid");
        const errorContainer = this.closest(".div_input_label, .uplode_section")?.querySelector(".error-container");
        if (errorContainer) {
            errorContainer.remove();
        }

        // Clear image input validation
        const imageInputWrapper = this.closest(".image-input");
        if (imageInputWrapper) {
            imageInputWrapper.classList.remove("is-invalid");
            const wrapper = imageInputWrapper.querySelector(".image-input-wrapper");
            if (wrapper) {
                wrapper.style.border = "";
            }
        }

        const file = this.files[0];

        if (file) {
            // Validate file type
            if (!file.type.match('image.*')) {
                if (typeof showErrorAlert === 'function') {
                    showErrorAlert('الملف المحدد ليس صورة');
                }
                this.value = '';
                return;
            }

            // Validate file size using value from PHP enum
            const maxSizeKB = window.IMAGE_MAX_SIZE_KB || 2048; // Fallback to 2048 if not defined
            const maxSizeBytes = maxSizeKB * 1024;
            const maxSizeMB = (maxSizeKB / 1024).toFixed(2);
            if (file.size > maxSizeBytes) {
                if (typeof showErrorAlert === 'function') {
                    showErrorAlert('حجم الصورة يجب أن لا يتجاوز ' + (' ' + maxSizeMB + ' ميجابايت'));
                }
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                applyImagePreviewStyling(previewImg, uploadDiv, uploadText, defaultImageName);
            };
            reader.readAsDataURL(file);
        }
    });
}

// Make functions globally available
window.handleCreate = handleCreate;
window.handleUpdate = handleUpdate;
window.handleDelete = handleDelete;
window.handleBulkDelete = handleBulkDelete;
window.handleRestore = handleRestore;
window.handleForceDelete = handleForceDelete;
window.handleToggle = handleToggle;
window.handleDataLoad = handleDataLoad;
window.handleFormSubmission = handleFormSubmission;
window.setupImageUpload = setupImageUpload;
window.applyImagePreviewStyling = applyImagePreviewStyling;

/* =============================================================== */

// Global debug instrumentation for submit buttons and forms
(function installGlobalSubmitLogger() {
    if (window.__globalSubmitLoggerInstalled) return;
    window.__globalSubmitLoggerInstalled = true;

    // Log any click on submit buttons (capture phase to run early)
    document.addEventListener('click', function (e) {
        try {
            var btn = e.target && (e.target.closest && e.target.closest('button[type="submit"], input[type="submit"]'));
        } catch (_) {}
    }, true);

    // Log any submit captured at the document level
    document.addEventListener('submit', function (e) {
        try {
            var f = e.target;
        } catch (_) {}
    }, true);
})();
