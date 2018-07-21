<li>
    <a href="{{ $file->getSrc('full', false) }}" data-id="{{ $file->id }}" title="{{ $file->title }}" data-created-at="{{ $file->created_at }}">
        {!! $file->getImage('thumbnail') !!}
    </a>
</li>
