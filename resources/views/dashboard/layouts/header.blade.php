<div class="page-header">
    <div class="header-wrapper">
        <div class="header-logo-wrapper width_div">

            <div class="toggle-sidebar toggle-sidebar-styles ">
                <img class="menu_bttn" src="{{ asset('assets/dashboard/images/MyPhotoes/menu.png') }}">
                <div class="profile-nav">
                    <div class="media profile-media">
                        <img class="user_img" src="{{ getFileFullUrl(auth('admin')->user()->image, auth('admin')->user()->id, 'public', 'user.png') }}">
                        <span>أهلا {{ auth('admin')->user()->name }}</span>
                        <img src="{{ asset('assets/dashboard/images/MyPhotoes/arrow-down.png') }}">
                    </div>

                    <ul class="profile-dropdown">
                        <li><a href="{{ route('dashboard.profile.index') }}"><i class="fa fa-user"></i><span>الملف الشخصي</span></a></li>
                    </ul>

                </div>
            </div>
        </div>
        <div class="left_side">
            <ul class="nav-menus">

                <li class="light_dark_section">
                    <img class="sun_img" src="{{ asset('assets/dashboard/images/MyPhotoes/sun.svg') }}">
                    <img class="moon_img" src="{{ asset('assets/dashboard/images/MyPhotoes/moon.svg') }}">
                </li>

                <li class="onhover-dropdown">
                    <div class="notification-box">
                        <span class="badge rounded-pill badge-warning">4 </span>
                        <img src="{{ asset('assets/dashboard/images/MyPhotoes/notifcation.svg') }}">
                    </div>
                    <div class="onhover-show-div notification-dropdown">
                        <div class="dropdown-title">
                            <h3>الاشعارات</h3><a class="f-right" href="cart.html"> <i class="fa fa-bell"> </i></a>
                        </div>
                        <ul class="custom-scrollbar">
                            <li>
                                <div class="media">
                                    <div class="notification-img bg-light-primary"><img
                                            src="{{ asset('assets/dashboard/images/man.png') }}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h5> <a class="f-14 m-0" href="user-profile.html">Allie Grater</a></h5>
                                        <p>Lorem ipsum dolor sit amet...</p><span>10:20</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <div class="notification-img bg-light-secondary"><img
                                            src="{{ asset('assets/dashboard/images/teacher.png') }}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h5> <a class="f-14 m-0" href="user-profile.html">Olive Yew</a></h5>
                                        <p>Lorem ipsum dolor sit amet...</p><span>09:20</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <div class="notification-img bg-light-info"><img
                                            src="{{ asset('assets/dashboard/images/teenager.png') }}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h5> <a class="f-14 m-0" href="user-profile.html">Peg Legge</a></h5>
                                        <p>Lorem ipsum dolor sit amet...</p><span>07:20</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <div class="notification-img bg-light-secondary"><img
                                            src="{{ asset('assets/dashboard/images/teacher.png') }}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h5> <a class="f-14 m-0" href="user-profile.html">Olive Yew</a></h5>
                                        <p>Lorem ipsum dolor sit amet...</p><span>09:20</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="media">
                                    <div class="notification-img bg-light-info"><img
                                            src="{{ asset('assets/dashboard/images/teenager.png') }}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h5> <a class="f-14 m-0" href="user-profile.html">Peg Legge</a></h5>
                                        <p>Lorem ipsum dolor sit amet...</p><span>07:20</span>
                                    </div>
                                </div>
                            </li>
                            <li class="p-0"><a class="view-all" href="#">عرض الكل</a></li>
                        </ul>
                    </div>

                </li>
            </ul>
        </div>
    </div>
</div>
