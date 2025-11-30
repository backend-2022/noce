<!DOCTYPE html>
<html lang="en">

<head>
    @include('dashboard.layouts.head')
</head>

<body>

    <!-- Loader starts-->
    <div class="loader-wrapper">
        <img class="loader_img" src="{{ asset('assets/dashboard/images/logo.svg') }}" alt="">
    </div>

    <div class="tap-top"><i class="fa fa-chevron-up"></i></div>

    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        @include('dashboard.layouts.header')
        <div class="page-body-wrapper">
            @include('dashboard.layouts.sidebar')
            <div class="page-body">

                <div class="container-fluid">
                    @if (View::hasSection('pageSubTitle') || View::hasSection('pageTitle') || View::hasSection('searchPlaceholder'))
                        <div class="fleax_header">
                            <div class="header_titels">
                                <a class="color_link" href="{{ route('dashboard.dashboard') }}">الرئيسية</a>
                                @if (View::hasSection('pageSubTitle'))
                                    <i class="bi bi-slash-lg"></i>
                                    <h3 class="color_link">@yield('pageSubTitle')</h3>
                                @endif
                                @if (View::hasSection('pageTitle'))
                                    <i class="bi bi-slash-lg"></i>
                                    <h3>@yield('pageTitle')</h3>
                                @endif
                            </div>
                            @if (View::hasSection('searchPlaceholder'))
                                <div class="input_search">
                                    <input placeholder="@yield('searchPlaceholder')">
                                    <img src="{{ asset('assets/dashboard/images/search.png') }}">
                                </div>
                            @endif
                        </div>
                    @endif
                    @yield('content')
                </div>
            </div>
            @include('dashboard.layouts.footer')
        </div>

    </div>

    @include('dashboard.inc.scripts')

</body>

</html>
