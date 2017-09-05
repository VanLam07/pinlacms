<nav class="navbar navbar-light" id="_main_bar">
    <div class="container">
        <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#_bs_main_bar"><i class="fa fa-bars"></i></button>
        <div class="collapse navbar-toggleable-md" id="_bs_main_bar">
            <a class="navbar-brand" href="#">
                <img id="_logo" src="/public/images/logo.png" alt="" class="img-fluid">
            </a>
            <ul class="nav navbar-nav">
                {!! $nestedMenus !!}
            </ul>

            <ul class="nav navbar-nav float-xs-right _nav_account">
                @if (auth()->check())
                <?php $currentUser = auth()->user(); ?>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-comments"></i></a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">{{trans('message.no_notify')}}</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i></a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">{{trans('message.no_chat')}}</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle _img_box" data-toggle="dropdown">{!! $currentUser->getAvatar() !!} <span>{{trim_ch($currentUser->name, 10)}}</span></a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item"><span><i class="fa fa-info"></i></span> Thông tin tài khoản</a>
                        <a href="#" class="dropdown-item"><span><i class="fa fa-lock"></i></span> Thay đổi mật khẩu</a>
                        <a href="{{route('logout')}}" class="dropdown-item"><span><i class="fa fa-power-off"></i></span> Đăng xuất</a>
                    </div>
                </li>
                @else
                <li class="nav-item">
                    <a href="#" class="nav-link _login_btn _acc_btn">{{trans('auth.login')}}</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link _register_btn _acc_btn">{{trans('auth.register')}}</a>
                </li>
                @endif
            </ul>

            <form class="form-inline float-xs-right _search_form">
                <input class="form-control" type="text" placeholder="Search">
                <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
</nav>