<div class="sidebar-wrapper">
    <div class="logo-wrapper">
        <a href="{{ route('dashboard.dashboard') }}">
            @php
                $logo = setting('logo');
                $logoUrl = $logo ? getFileFullUrl($logo, null, 'public', 'white_img.png') : null;
            @endphp
            @if($logoUrl && $logo)
                <img src="{{ $logoUrl }}" alt="{{ setting('site_name') ?? 'NOCE - نوتش' }}" style="max-width: 100%; max-height: 60px; object-fit: contain;">
            @else
                <h1>{{ setting('site_name') ?? 'NOCE - نوتش' }}</h1>
            @endif
        </a>
        <a href="{{ route('dashboard.dashboard') }}" class="back-btn" title="العودة للرئيسية">
        </a>
    </div>

    <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i class="fa fa-arrow-left"></i></div>
        <div id="sidebar-menu">
            <ul class="sidebar-links" id="simple-bar">

                <div class="uper_content">

                    <button class="sidebar-close">
                        <div class="w_div">
                            <img src="{{ asset('assets/dashboard/images/MyPhotoes/5.png') }}" alt="">
                        </div>
                        <span class="hide_span">عرض اقل</span>
                    </button>

                    <li class="sidebar-list {{ isActiveRouteGroup('dashboard.cities.') }}">
                        <a class="sidebar-link sidebar-title link-nav {{ isActiveRouteGroup('dashboard.cities.') }}" href="{{ route('dashboard.cities.index') }}">
                            <div class="img_div">
                                <img class="img_color" src="{{ asset('assets/dashboard/images/MyPhotoes/2.png') }}" alt="">
                            </div>
                            <span class="hide_span">إدارة المدن</span>
                        </a>

                    </li>

                    <li class="sidebar-list {{ isActiveRouteGroup('dashboard.services.') }}">
                        <a class="sidebar-link sidebar-title link-nav {{ isActiveRouteGroup('dashboard.services.') }}" href="{{ route('dashboard.services.index') }}">
                            <div class="img_div">
                                <img class="img_color" src="{{ asset('assets/dashboard/images/MyPhotoes/2.png') }}" alt="">
                            </div>
                            <span class="hide_span">إدارة الخدمات</span>
                        </a>

                    </li>

                    <li class="sidebar-list {{ isActiveRouteGroup('dashboard.free-designs.') }}">
                        <a class="sidebar-link sidebar-title link-nav {{ isActiveRouteGroup('dashboard.free-designs.') }}" href="{{ route('dashboard.free-designs.index') }}">
                            <div class="img_div">
                                <img class="img_color" src="{{ asset('assets/dashboard/images/MyPhotoes/3.png') }}" alt="">
                            </div>
                            <span class="hide_span">إدارة الخدمات المجانية</span>
                        </a>

                    </li>

                    <li class="sidebar-list {{ isActiveRouteGroup('dashboard.admins.') }}">
                        <a class="sidebar-link sidebar-title link-nav {{ isActiveRouteGroup('dashboard.admins.') }}" href="{{ route('dashboard.admins.index') }}">
                            <div class="img_div">
                                <img class="img_color" src="{{ asset('assets/dashboard/images/MyPhotoes/2.png') }}" alt="">
                            </div>
                            <span class="hide_span">إدارة المشرفين</span>
                        </a>

                    </li>

                    <li class="sidebar-list {{ isActiveRouteGroup('dashboard.backups.') }}">
                        <a class="sidebar-link sidebar-title link-nav {{ isActiveRouteGroup('dashboard.backups.') }}" href="{{ route('dashboard.backups.index') }}">
                            <div class="img_div">
                                <img class="img_color" src="{{ asset('assets/dashboard/images/MyPhotoes/2.png') }}" alt="">
                            </div>
                            <span class="hide_span">النسخ الاحتياطية</span>
                        </a>

                    </li>

                </div>


                <div class="bottom_div">
                    <li class="sidebar-list {{ isActiveRouteGroup('dashboard.settings.') }}">
                        <a class="sidebar-link sidebar-title link-nav {{ isActiveRouteGroup('dashboard.settings.') }}" href="{{ route('dashboard.settings.index') }}">
                            <div class="img_div">
                                <img class="img_color" src="{{ asset('assets/dashboard/images/MyPhotoes/4.png') }}" alt="">
                            </div>
                            <span class="hide_span">الاعدادات </span>
                        </a>

                    </li>

                    <li class="sidebar-list ">
                        <form action="{{ route('dashboard.logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="sidebar-link sidebar-title link-nav logout_bg" style="border: none; background: none; width: 100%; text-align: right; padding: 0;">
                                <div class="img_div">
                                    <img class="img_color" src="{{ asset('assets/dashboard/images/small_icons/logut.png') }}" alt="">
                                </div>
                                <span class="hide_span">تسجيل الخروج</span>
                            </button>
                        </form>
                    </li>
                </div>


            </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i class="fa fa-arrow-left"></i></div>
    </nav>
</div>
