<div class="text-center skyline">
    <div class="car-container">
        <img class="car" src="/images/animate/car.png">
        <img class="wheel-front car-wheel" src="/images/animate/wheel.png">
        <img class="wheel-behihe car-wheel" src="/images/animate/wheel.png">
    </div>
</div>
<div class="foot-content">
    <div class="container text-center">
        <div>
            <?php
            $footerMenuId = PlOption::get('footer_menu');
            if ($footerMenuId) {
                $footerMenuItems = PlMenu::getMenuItems($footerMenuId);
                if ($footerMenuItems) {
                    ?>
                    <ul class="list-inline footer-menu">
                        @foreach ($footerMenuItems as $item)
                        <li class="list-inline-item"><a href="{{ $item->link }}">{{ $item->title }}</a></li>
                        @endforeach
                    </ul>
                    <?php
                }
            }
            ?>
        </div>
        <div class="footer-social">
            @include('front::includes.social', ['imageIcon' => true])
        </div>
        <h3 class="foot-title">{{ PlOption::get('blog_title') }}</h3>
    </div>
</div>

<div class="end-foot text-center">
    <div class="container">
        <div class="copyright">Copyright &copy; {{ config('app.url') }} All rights reserved, designed by <a href="{{ PlOption::get('designed_by_url') }}">Pinla</a></div>
    </div>
</div>
