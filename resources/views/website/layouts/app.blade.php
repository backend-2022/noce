<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    @include('website.layouts.head')
</head>

<body>
    <div class="page_content">

        {{-- @include('website.layouts.navbar') --}}

        @yield('content')

        @include('website.layouts.footer')
    </div>
    @include('website.inc.scripts')

</body>

</html>
