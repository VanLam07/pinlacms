<ul class="nav nav-tabs lang-tabs">
    @foreach($langs as $lang)
    <li class="{{ localeActive($lang->code) }}">
        <a href="#tab-{{$lang->code}}" data-toggle="tab">{{ $lang->name }}</a>
    </li>
    @endforeach
</ul>
<br />

