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
            '<"row marg_row"<"col-6"B> <"col-6 d-flex justify-content-start"f> >' +
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

        buttons: [
            ...(currentRouteName !== "dashboard.orders.index" &&
            currentRouteName !== "dashboard.orders.details" &&
            currentRouteName !== "dashboard.subscriptions.index" &&
            currentRouteName !== "dashboard.cities.index"
                ? [
                      {
                          text: `<svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M10.103 0.000248563C10.4735 0.00124281 10.7969 0.00621407 11.075 0.0310704C11.4441 0.0640804 11.7882 0.13555 12.1224 0.30897C12.2545 0.3775 12.3808 0.45664 12.5001 0.54561C12.802 0.77075 13.0164 1.04917 13.2071 1.36688C13.3867 1.66618 13.5692 2.04263 13.7841 2.4861L14.2756 3.5H18.75C19.1642 3.5 19.5 3.83579 19.5 4.25C19.5 4.66422 19.1642 5 18.75 5H17.9551L17.3765 14.3601C17.2993 15.608 17.2381 16.5982 17.1127 17.3892C16.984 18.2004 16.778 18.876 16.366 19.4669C15.989 20.0074 15.5037 20.4637 14.9409 20.8065C14.3258 21.1813 13.6388 21.3452 12.8212 21.4236C12.024 21.5 11.0318 21.5 9.7815 21.5H9.7039C8.452 21.5 7.45858 21.5 6.66041 21.4235C5.84186 21.3449 5.15416 21.1807 4.53854 20.8053C3.97537 20.4618 3.48991 20.0047 3.11311 19.4633C2.70121 18.8714 2.49587 18.1949 2.36816 17.3825C2.24362 16.5904 2.18377 15.5988 2.10833 14.3491L1.54393 5H0.75C0.33579 5 0 4.66422 0 4.25C0 3.83579 0.33579 3.5 0.75 3.5H5.32055L5.73959 2.58073C5.94915 2.12095 6.12691 1.73095 6.30391 1.42073C6.49173 1.09153 6.70528 0.80248 7.01078 0.56811C7.13136 0.47561 7.25939 0.39326 7.39357 0.32192C7.73355 0.14114 8.0851 0.0667204 8.4626 0.0323604C8.8183 0 9.2469 0 9.7522 0L10.103 0.000248563ZM16.4522 5H3.04666L3.60327 14.22C3.68157 15.517 3.7379 16.4368 3.84996 17.1496C3.96012 17.8503 4.11439 18.2761 4.34431 18.6065C4.60213 18.9769 4.93428 19.2896 5.31961 19.5247C5.66325 19.7342 6.09758 19.8626 6.80365 19.9303C7.52186 19.9992 8.4434 20 9.7428 20C11.0405 20 11.9607 19.9992 12.678 19.9305C13.3832 19.8628 13.8171 19.7348 14.1605 19.5255C14.5456 19.2909 14.8777 18.9788 15.1356 18.6089C15.3656 18.2791 15.5202 17.8539 15.6312 17.1542C15.7441 16.4425 15.8017 15.5241 15.8817 14.2288L16.4522 5ZM7.25 8.5C7.66421 8.5 8 8.8358 8 9.25V15.25C8 15.6642 7.66421 16 7.25 16C6.83579 16 6.5 15.6642 6.5 15.25V9.25C6.5 8.8358 6.83579 8.5 7.25 8.5ZM12.25 8.5C12.6642 8.5 13 8.8358 13 9.25V15.25C13 15.6642 12.6642 16 12.25 16C11.8358 16 11.5 15.6642 11.5 15.25V9.25C11.5 8.8358 11.8358 8.5 12.25 8.5ZM10.1992 1.50071L9.40805 1.50051C9.06322 1.50191 8.8083 1.50709 8.5986 1.52618C8.3331 1.55035 8.1981 1.59298 8.0978 1.64633C8.0368 1.67876 7.9786 1.71619 7.9238 1.75823C7.8337 1.82739 7.73887 1.93253 7.60677 2.16407C7.4676 2.40798 7.31765 2.73523 7.09105 3.23234L6.96904 3.5H12.6087L12.4482 3.16888C12.2157 2.68926 12.062 2.37382 11.9209 2.13884C11.7871 1.91588 11.6926 1.81458 11.6033 1.74801C11.5491 1.70756 11.4917 1.67159 11.4316 1.64044C11.3328 1.58916 11.2004 1.54827 10.9414 1.52511C10.7464 1.50767 10.5117 1.50233 10.1992 1.50071Z" fill="currentColor"/>
</svg> حذف المحدد`,
                          className: "delete-selected",
                          attr: {
                              id: "datatable-delete-selected",
                              disabled: true,
                          },
                          action: function (e, dt, node) {
                              $(document).trigger("datatable:delete-selected", [
                                  selector,
                                  dt,
                                  node,
                              ]);
                          },
                      },
                  ]
                : []),
        ],

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
