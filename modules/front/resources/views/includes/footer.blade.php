<div class="foot-content">
    <div class="container">
        <div class="row">
            <div class="col-4">
                <h3 class="foot-title">{{ PlOption::get('blog_title') }}</h3>
            </div>
            <div class="col-8 ml-auto">
                <?php
                $footerMenuId = PlOption::get('footer_menu');
                if ($footerMenuId) {
                    $footerMenuItems = PlMenu::getMenuItems($footerMenuId);
                    if ($footerMenuItems) {
                        ?>
                        <ul class="list-inline footer-menu text-right">
                            @foreach ($footerMenuItems as $item)
                            <li class="list-inline-item"><a href="{{ $item->link }}">{{ $item->title }}</a></li>
                            @endforeach
                        </ul>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="end-foot">
    <div class="container">
        <div class="copyright">Copyright &copy; {{ config('app.url') }} All rights reserved, designed by <a href="{{ PlOption::get('designed_by_url') }}">Pinla</a></div>
    </div>
</div>
