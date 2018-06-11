<div id="top_head">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 social-col">
                @include('front::includes.social')
            </div>
            <div class="col account-col">
                @if (auth()->check())
                    <div class="dropdown account-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user"></i> {{ $currentUser->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if (canDo('accept_manage'))
                                <a class="dropdown-item" href="{{ route('admin::index') }}" target="_blank">
                                    <i class="fa fa-dashboard"></i> {{ trans('front::view.manage_page') }}
                                </a>
                            @endif
                            <a class="dropdown-item" href="{{ route('front::account.profile') }}">
                                <i class="fa fa-info"></i> {{ trans('front::view.personal_info') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('front::account.logout') }}">
                                <i class="fa fa-power-off"></i> {{ trans('front::view.logout') }}
                            </a>
                        </div>
                    </div>
                @else
                    <a class="logon-box" href="{{ route('front::account.login') }}">
                        <i class="fa fa-sign-in"></i>
                        {{ trans('front::view.login') }}
                    </a>
                    <a class="logon-box" href="{{ route('front::account.register') }}">
                        <i class="fa fa-user"></i>
                        {{ trans('front::view.register') }}
                    </a>
                @endif
                <form class="inline maxw-250 form-search hidden">
                    <input type="text" name="s" value="{{ request()->get('s') }}" class="form-control" placeholder="{{ trans('front::view.search') }}...">
                    <button>
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$primaryMenuId = PlOption::get('primary_menu');
?>
@if ($primaryMenuId)

<div id="main_navbar">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <?php 
            $logoText = PlOption::get('blog_title');
            $logoConvert = '';
            $arrayColor = ['#75b72d', '#ed7f04', '#fcbf02', '#0074d9', '#ffc107', '#00e765', '#000', '#d13030'];
            if ($logoText) {
                $logoText = str_split($logoText);
                foreach ($logoText as $text) {
                    $logoConvert .= '<span style="color: '. $arrayColor[array_rand($arrayColor)] .';">' . e($text) . ' </span>';
                }
            }
            ?>
            <a class="navbar-brand text-uppercase" href="{{ url('/') }}">{!! $logoConvert !!}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_navbar_collapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main_navbar_collapse">
                {!! PlMenu::renderFrontMenu($primaryMenuId) !!}
            </div>
        </nav>
    </div>
</div>

@endif


