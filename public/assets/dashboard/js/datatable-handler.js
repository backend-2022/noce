function configureSearchLabel($searchLabel) {
    if (!$searchLabel || !$searchLabel.length) {
        return;
    }

    var $searchInput = $searchLabel.find('input[type="search"]');
    if (!$searchInput.length) {
        return;
    }

    // Remove any leftover text nodes like "Search:"
    $searchLabel
        .contents()
        .filter(function () {
            return this.nodeType === Node.TEXT_NODE;
        })
        .remove();

    var $searchText = $searchLabel.find(".datatable-search-text");
    if (!$searchText.length) {
        $searchText = $('<span class="datatable-search-text"></span>');
        $searchInput.before($searchText);
    }
    // $searchText.text('بحث');

    $searchLabel.css({
        display: "flex",
        "flex-direction": "row-reverse",
        "align-items": "center",
        gap: "0.5rem",
        "margin-bottom": "0",
    });
}

function initDataTable(
    selector,
    ajax,
    columns,
    hiddenPrintColumns = [],
    createdRowCallback = null,
    pageLength = 10,
    customData = null
) {
    let currentRoute = window.location.pathname; // e.g. "/dashboard/sliders"
    let domLayout;

    // ✅ Check route before initializing DataTable
    if (
        currentRoute.includes("/dashboard/merchants") ||
        currentRoute.includes("/dashboard/videoCategories") ||
        currentRoute.includes("/dashboard/discovers/index2")
    ) {
        // Remove search box
        domLayout =
            '<"row marg_row"<"col-12 d-flex justify-content-end"f> >' +
            '<"row table-design"<"col-12  table-design-inner"tr>>' +
            '<"row"<"col-sm-12 d-flex my-3 justify-content-center text-end"p>>' +
            '<"row"<"col-12 d-flex justify-content-center"i>>';
    } else if (currentRouteName === "dashboard.cities.index") {
        // Cities page - no buttons, keep search
        domLayout =
            '<"row marg_row"<"col-12 d-flex justify-content-start"f> >' +
            '<"row table-design"<"col-12  table-design-inner"tr>>' +
            '<"row"<"col-sm-12 d-flex my-3 justify-content-center text-end"p>>' +
            '<"row"<"col-12 d-flex justify-content-center"i>>';
    } else {
        // Keep search box
        domLayout =
            '<"row marg_row"<"col-12 d-flex justify-content-start"f> >' +
            '<"row table-design"<"col-12  table-design-inner"tr>>' +
            '<"row"<"col-sm-12 d-flex my-3 justify-content-center text-end"p>>' +
            '<"row"<"col-12 d-flex justify-content-center"i>>';
    }

    const options = {
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: pageLength,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "الكل"],
        ],
        order: [],
        dom: domLayout, // ✅ Use the conditional DOM layout
        searchDelay: 500, // Delay search by 500ms after user stops typing

        buttons: [],

        ajax: ajax,
        columns: columns,
        language: {
            sProcessing: "جاري المعالجة...",
            sLengthMenu: "عرض _MENU_ سجل",
            sZeroRecords: "لم يتم العثور على نتائج",
            sEmptyTable: "لا توجد بيانات متاحة في الجدول",
            sInfo: "عرض _START_ إلى _END_ من أصل _TOTAL_ سجل",
            sInfoEmpty: "عرض 0 إلى 0 من أصل 0 سجل",
            sInfoFiltered: "(تم تصفية من أصل _MAX_ سجل)",
            sInfoPostFix: "",
            sSearch: "بحث:",
            sUrl: "",
            sInfoThousands: ",",
            sLoadingRecords: "جاري التحميل...",
            oPaginate: {
                sFirst: "الأول",
                sLast: "الأخير",
                sNext: "التالي",
                sPrevious: "السابق",
            },
            oAria: {
                sSortAscending: ": تفعيل لترتيب العمود تصاعدياً",
                sSortDescending: ": تفعيل لترتيب العمود تنازلياً",
            },
            buttons: {
                copy: "نسخ",
                csv: "CSV",
                excel: "Excel",
                pdf: "PDF",
                print: "طباعة",
                colvis: "إظهار الأعمدة",
            },
        },
        createdRow: createdRowCallback,
    };

    var originalDrawCallback = options.drawCallback;
    options.drawCallback = function (settings) {
        // Call original drawCallback if it exists
        if (
            originalDrawCallback &&
            typeof originalDrawCallback === "function"
        ) {
            originalDrawCallback.call(this, settings);
        }

        // Setup search input validation after draw
        setTimeout(function () {
            var $wrapper = $(selector).closest(".dataTables_wrapper");
            var $searchInput = $wrapper.find(
                '.dataTables_filter input[type="search"]'
            );
            var $searchLabel = $wrapper.find(".dataTables_filter label");

            if ($searchInput.length) {
                // Set Arabic placeholder
                $searchInput.attr("placeholder", "ابحث...");
                $searchInput.attr("dir", "rtl");

                // Update label text to Arabic
                configureSearchLabel($searchLabel);

                // Set maxlength
                $searchInput.attr("maxlength", "2000");

                // Remove previous event handlers to avoid duplicates
                $searchInput.off(
                    "keyup.searchvalidation input.searchvalidation"
                );

                // Add validation on input to prevent typing more than 2000 chars
                $searchInput.on("input.searchvalidation", function () {
                    var searchValue = $(this).val();
                    if (searchValue.length > 2000) {
                        $(this).val(searchValue.substring(0, 2000));
                        if (typeof Swal !== "undefined") {
                            Swal.fire({
                                icon: "error",
                                title: "خطأ",
                                text: "لا يمكن البحث بأكثر من 2000 حرف. تم تقصير نص البحث.",
                                confirmButtonText: "حسناً",
                            });
                        }
                    }
                });
            }
        }, 100);
    };

    if (customData && typeof customData === "object") {
        const mergedOptions = $.extend(true, {}, options, customData);
        var table = $(selector).DataTable(mergedOptions);
    } else {
        // ✅ Initialize DataTable
        var table = $(selector).DataTable(options);
    }

    // ✅ Setup initial search validation with debounce
    setTimeout(function () {
        var $wrapper = $(selector).closest(".dataTables_wrapper");
        var $searchInput = $wrapper.find(
            '.dataTables_filter input[type="search"]'
        );
        var $searchLabel = $wrapper.find(".dataTables_filter label");

        if ($searchInput.length) {
            // Set Arabic placeholder and RTL direction
            $searchInput.attr("placeholder", "ابحث...");
            $searchInput.attr("dir", "rtl");

            // Update label text to Arabic
            configureSearchLabel($searchLabel);

            $searchInput.attr("maxlength", "2000");

            // Validate max length on input
            $searchInput.on("input.searchvalidation", function () {
                var searchValue = $(this).val();
                if (searchValue.length > 2000) {
                    $(this).val(searchValue.substring(0, 2000));
                    if (typeof Swal !== "undefined") {
                        Swal.fire({
                            icon: "error",
                            title: "خطأ",
                            text: "لا يمكن البحث بأكثر من 2000 حرف. تم تقصير نص البحث.",
                            confirmButtonText: "حسناً",
                        });
                    }
                }
            });
        }
    }, 200);

    return table;
}
