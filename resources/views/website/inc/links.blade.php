<link rel="stylesheet" href="{{ asset('assets/website/style.css') }}?v={{ file_exists(public_path('assets/website/style.css')) ? filemtime(public_path('assets/website/style.css')) : time() }}">
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/toastr.min.css') }}?v={{ file_exists(public_path('assets/dashboard/css/toastr.min.css')) ? filemtime(public_path('assets/dashboard/css/toastr.min.css')) : time() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&family=Noto+Kufi+Arabic:wght@100..900&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="shortcut icon" href="{{ getFileFullUrl(setting('logo'), null, 'public', 'favicon.ico') }}" type="image/x-icon">
@stack('css')
