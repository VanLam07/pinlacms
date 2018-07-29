<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('admin::index') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">Logo</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{{ PlOption::get('cms_title') }}</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!--Languages-->
                <li class="dropdown dropdown-language">
                    @if ($currentLang)
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{!! $currentLang->icon() !!} {{ $currentLang->name }}</a>
                    @endif
                    
                    @if ($langs->count() > 1)
                    <ul class="dropdown-menu">
                        @foreach ($langs as $lang)
                        <li>
                            <a href="{{ $lang->switchUrl() }}">{!! $lang->icon() !!} {{ $lang->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                
                <!-- Notifications: style can be found in dropdown.less -->
<!--                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 10 notifications</li>
                        <li>
                             inner menu: contains the actual data 
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>-->

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!--<img src="dist/img/user2-160x160.jpg" class="user-image" alt="Avatar">-->
                        <i class="fa fa-user"></i>
                        <span class="hidden-xs"> {{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin::account.profile') }}"><i class="fa fa-edit"></i> @lang('admin::view.profile')</a></li>
                        <li><a href="{{ route('admin::auth.logout') }}"><i class="fa fa-power-off"></i> @lang('admin::view.logout')</a></li>
                    </ul>
                </li>
            </ul>
        </div>

    </nav>
</header>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- search form -->
        <form action="{{ route('admin::search') }}" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="search" class="form-control" value="{{ request()->get('search') }}" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        
        {!! PlMenu::renderMenus($menuList) !!}
        
    </section>
    <!-- /.sidebar -->
</aside>