<!-- jQuery -->
<script src="{{ asset('assets/dashboard/js/jquery-3.5.1.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/jquery-3.5.1.min.js')) ? filemtime(public_path('assets/dashboard/js/jquery-3.5.1.min.js')) : time() }}"></script>
<!-- Toastr JS -->
<script src="{{ asset('assets/dashboard/js/toastr.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/toastr.min.js')) ? filemtime(public_path('assets/dashboard/js/toastr.min.js')) : time() }}"></script>
<!-- ajax-handler.js -->
<script src="{{ asset('assets/dashboard/js/ajax-handler.js') }}?v={{ file_exists(public_path('assets/dashboard/js/ajax-handler.js')) ? filemtime(public_path('assets/dashboard/js/ajax-handler.js')) : time() }}" data-cfasync="false"></script>
<!-- Input Maxlength Handler -->
<script src="{{ asset('assets/dashboard/js/input-maxlength.js') }}?v={{ file_exists(public_path('assets/dashboard/js/input-maxlength.js')) ? filemtime(public_path('assets/dashboard/js/input-maxlength.js')) : time() }}"></script>

@stack('js')
