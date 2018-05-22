<div class="socials-list">
    @if (isset($imageIcon))
        <a href="{{ PlOption::get('facebook_link') }}" target="_blank"><i class="icon icon-32 icon-facebook"></i></a>
        <a href="{{ PlOption::get('googleplus_link') }}" target="_blank"><i class="icon icon-32 icon-google-plus"></i></a>
        <a href="{{ PlOption::get('youtube_link') }}" target="_blank"><i class="icon icon-32 icon-youtube"></i></a>
    @else
        <a href="{{ PlOption::get('facebook_link') }}" target="_blank"><i class="fa fa-facebook"></i></a>
        <a href="{{ PlOption::get('googleplus_link') }}" target="_blank"><i class="fa fa-google-plus"></i></a>
        <a href="{{ PlOption::get('youtube_link') }}" target="_blank"><i class="fa fa-youtube"></i></a>
    @endif
</div>

