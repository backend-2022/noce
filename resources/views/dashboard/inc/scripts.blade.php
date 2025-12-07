<script
    src="{{ asset('assets/dashboard/js/jquery-3.5.1.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/jquery-3.5.1.min.js')) ? filemtime(public_path('assets/dashboard/js/jquery-3.5.1.min.js')) : time() }}">
</script>
<!-- Toastr JS -->
<script
    src="{{ asset('assets/dashboard/js/toastr.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/toastr.min.js')) ? filemtime(public_path('assets/dashboard/js/toastr.min.js')) : time() }}">
</script>
<!-- SweetAlert2 -->
<script
    src="{{ asset('assets/dashboard/js/swal.js') }}?v={{ file_exists(public_path('assets/dashboard/js/swal.js')) ? filemtime(public_path('assets/dashboard/js/swal.js')) : time() }}">
</script>
<script>
    // Image validation constants from PHP enum
    window.IMAGE_MAX_SIZE_KB = {{ \App\Enums\MimesValidationEnums\ImageMimesValidationEnum::MAX_IMAGE_SIZE_KB }};
</script>
<script
    src="{{ asset('assets/dashboard/js/ajax-handler.js') }}?v={{ file_exists(public_path('assets/dashboard/js/ajax-handler.js')) ? filemtime(public_path('assets/dashboard/js/ajax-handler.js')) : time() }}"
    data-cfasync="false"></script>
<script
    src="{{ asset('assets/dashboard/js/phone-codes.js') }}?v={{ file_exists(public_path('assets/dashboard/js/phone-codes.js')) ? filemtime(public_path('assets/dashboard/js/phone-codes.js')) : time() }}"
    data-cfasync="false"></script>
<script
    src="{{ asset('assets/dashboard/js/bootstrap/bootstrap.bundle.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/bootstrap/bootstrap.bundle.min.js')) ? filemtime(public_path('assets/dashboard/js/bootstrap/bootstrap.bundle.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/scrollbar/simplebar.js') }}?v={{ file_exists(public_path('assets/dashboard/js/scrollbar/simplebar.js')) ? filemtime(public_path('assets/dashboard/js/scrollbar/simplebar.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/scrollbar/custom.js') }}?v={{ file_exists(public_path('assets/dashboard/js/scrollbar/custom.js')) ? filemtime(public_path('assets/dashboard/js/scrollbar/custom.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/counter/jquery.waypoints.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/counter/jquery.waypoints.min.js')) ? filemtime(public_path('assets/dashboard/js/counter/jquery.waypoints.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/counter/jquery.counterup.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/counter/jquery.counterup.min.js')) ? filemtime(public_path('assets/dashboard/js/counter/jquery.counterup.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/sidebar-menu.js') }}?v={{ file_exists(public_path('assets/dashboard/js/sidebar-menu.js')) ? filemtime(public_path('assets/dashboard/js/sidebar-menu.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/notify/bootstrap-notify.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/notify/bootstrap-notify.min.js')) ? filemtime(public_path('assets/dashboard/js/notify/bootstrap-notify.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/notify/index.js') }}?v={{ file_exists(public_path('assets/dashboard/js/notify/index.js')) ? filemtime(public_path('assets/dashboard/js/notify/index.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/echarts.js') }}?v={{ file_exists(public_path('assets/dashboard/js/echarts.js')) ? filemtime(public_path('assets/dashboard/js/echarts.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datatable/datatables/jquery.datatables.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datatable/datatables/jquery.datatables.min.js')) ? filemtime(public_path('assets/dashboard/js/datatable/datatables/jquery.datatables.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')) ? filemtime(public_path('assets/dashboard/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datatable/datatable-extension/dataTables.buttons.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datatable/datatable-extension/dataTables.buttons.min.js')) ? filemtime(public_path('assets/dashboard/js/datatable/datatable-extension/dataTables.buttons.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datatable/datatable-extension/jszip.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datatable/datatable-extension/jszip.min.js')) ? filemtime(public_path('assets/dashboard/js/datatable/datatable-extension/jszip.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datatable/datatable-extension/buttons.colVis.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datatable/datatable-extension/buttons.colVis.min.js')) ? filemtime(public_path('assets/dashboard/js/datatable/datatable-extension/buttons.colVis.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datatable/datatable-extension/pdfmake.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datatable/datatable-extension/pdfmake.min.js')) ? filemtime(public_path('assets/dashboard/js/datatable/datatable-extension/pdfmake.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datatable/datatable-extension/vfs_fonts.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datatable/datatable-extension/vfs_fonts.js')) ? filemtime(public_path('assets/dashboard/js/datatable/datatable-extension/vfs_fonts.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datatable/datatable-extension/buttons.bootstrap4.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datatable/datatable-extension/buttons.bootstrap4.min.js')) ? filemtime(public_path('assets/dashboard/js/datatable/datatable-extension/buttons.bootstrap4.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datatable/datatable-extension/buttons.html5.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datatable/datatable-extension/buttons.html5.min.js')) ? filemtime(public_path('assets/dashboard/js/datatable/datatable-extension/buttons.html5.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datatable/datatable-extension/buttons.print.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datatable/datatable-extension/buttons.print.min.js')) ? filemtime(public_path('assets/dashboard/js/datatable/datatable-extension/buttons.print.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/select2/select2.full.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/select2/select2.full.min.js')) ? filemtime(public_path('assets/dashboard/js/select2/select2.full.min.js')) : time() }}">
</script>
<script
    src="{{ asset('assets/dashboard/js/datepicker/date-picker/datepicker.js') }}?v={{ file_exists(public_path('assets/dashboard/js/datepicker/date-picker/datepicker.js')) ? filemtime(public_path('assets/dashboard/js/datepicker/date-picker/datepicker.js')) : time() }}">
</script>
<script>
    window.datatableArabicLangUrl = "{{ asset('assets/dashboard/js/datatable/ar.json') }}";
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const yearSpan = document.querySelector('.year_date');
        const currentYear = new Date().getFullYear();
        yearSpan.textContent = currentYear;
    });
</script>
<script
    src="{{ asset('assets/dashboard/js/script.js') }}?v={{ file_exists(public_path('assets/dashboard/js/script.js')) ? filemtime(public_path('assets/dashboard/js/script.js')) : time() }}">
</script>
<!-- Input Maxlength Handler -->
<script
    src="{{ asset('assets/dashboard/js/input-maxlength.js') }}?v={{ file_exists(public_path('assets/dashboard/js/input-maxlength.js')) ? filemtime(public_path('assets/dashboard/js/input-maxlength.js')) : time() }}">
</script>

@stack('js')
