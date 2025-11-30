<link rel="shortcut icon" href="{{ asset('assets/dashboard/images/clock.png') }}" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/vendors/font-awesome.css') }}?v={{ file_exists(public_path('assets/dashboard/css/vendors/font-awesome.css')) ? filemtime(public_path('assets/dashboard/css/vendors/font-awesome.css')) : time() }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/vendors/themify.css') }}?v={{ file_exists(public_path('assets/dashboard/css/vendors/themify.css')) ? filemtime(public_path('assets/dashboard/css/vendors/themify.css')) : time() }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/vendors/scrollbar.css') }}?v={{ file_exists(public_path('assets/dashboard/css/vendors/scrollbar.css')) ? filemtime(public_path('assets/dashboard/css/vendors/scrollbar.css')) : time() }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/vendors/datatables.css') }}?v={{ file_exists(public_path('assets/dashboard/css/vendors/datatables.css')) ? filemtime(public_path('assets/dashboard/css/vendors/datatables.css')) : time() }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/vendors/datatable-extension.css') }}?v={{ file_exists(public_path('assets/dashboard/css/vendors/datatable-extension.css')) ? filemtime(public_path('assets/dashboard/css/vendors/datatable-extension.css')) : time() }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/vendors/bootstrap.css') }}?v={{ file_exists(public_path('assets/dashboard/css/vendors/bootstrap.css')) ? filemtime(public_path('assets/dashboard/css/vendors/bootstrap.css')) : time() }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/vendors/select2.css') }}?v={{ file_exists(public_path('assets/dashboard/css/vendors/select2.css')) ? filemtime(public_path('assets/dashboard/css/vendors/select2.css')) : time() }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/vendors/date-picker.css') }}?v={{ file_exists(public_path('assets/dashboard/css/vendors/date-picker.css')) ? filemtime(public_path('assets/dashboard/css/vendors/date-picker.css')) : time() }}">
<link rel="stylesheet" href="{{ asset('assets/dashboard/fonts/fonts.css') }}?v={{ file_exists(public_path('assets/dashboard/fonts/fonts.css')) ? filemtime(public_path('assets/dashboard/fonts/fonts.css')) : time() }}" >
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/style-rtl.css') }}?v={{ file_exists(public_path('assets/dashboard/css/style-rtl.css')) ? filemtime(public_path('assets/dashboard/css/style-rtl.css')) : time() }}">

<!-- Toastr CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/toastr.min.css') }}?v={{ file_exists(public_path('assets/dashboard/css/toastr.min.css')) ? filemtime(public_path('assets/dashboard/css/toastr.min.css')) : time() }}">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/sweetalert2.min.css') }}?v={{ file_exists(public_path('assets/dashboard/css/sweetalert2.min.css')) ? filemtime(public_path('assets/dashboard/css/sweetalert2.min.css')) : time() }}">

@stack('css')
