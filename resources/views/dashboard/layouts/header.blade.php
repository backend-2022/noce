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
    </div>
</div>
