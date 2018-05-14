@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_albums'))

@section('content')


{!! showMessage() !!}

@include('admin::parts.lang_edit_tabs', ['route' => 'admin::album.edit'])

{!! Form::open(['method' => 'put', 'route' => ['admin::album.update', $item->id]]) !!}

<div class="row">
    <div class="col-sm-8">

        <div class="form-group">
            <label>{{trans('admin::view.name')}} (*)</label>
            {!! Form::text('locale[name]', $item->name, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('locale.name') !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.slug')}}</label>
            {!! Form::text('locale[slug]', $item->slug, ['class' => 'form-control', 'placeholder' => trans('admin::view.slug')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.description')}}</label>
            {!! Form::textarea('locale[description]', $item->description, ['class' => 'form-control', 'rows' => 2, 'placeholder' => trans('admin::view.description')]) !!}
        </div>
        
        <div class="form-group thumb_box">
            <label>
                {{ trans('admin::view.list_images') }} &nbsp;&nbsp;
                <button type="button" class="btn btn-primary btn-files-modal"
                        data-href="{{ route('admin::file.dialog', ['multiple' => 1, 'el_preview' => '#album_list_image', 'thumb_size' => 1, 'file_name' => 'media_ids', 'append' => 1]) }}">
                    {{trans('admin::view.add_image')}}
                </button>
            </label>
            <div class="thumb_group album_media_box well" id="album_list_image">
                @if (!$medias->isEmpty())
                @foreach ($medias as $media)
                <p class="file_item" data-id="{{ $media->id }}">
                    {!! $media->getImage('thumbnail') !!}
                    <a class="f_close"></a>
                    <input type="hidden" name="curr_media_ids[{{ $media->pivot->order }}]" value="{{ $media->id }}">
                </p>
                @endforeach
                @endif
            </div>
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.meta_keyword')}}</label>
            {!! Form::text('locale[meta_keyword]', $item->meta_keyword, ['class' => 'form-control', 'placeholder' => trans('admin::view.meta_keyword')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.meta_desc')}}</label>
            {!! Form::textarea('locale[meta_desc]', $item->meta_desc, ['class' => 'form-control', 'rows' => 2, 'placeholder' => trans('admin::view.meta_desc')]) !!}
        </div>

    </div>
    <div class="col-sm-4">

        <div class="form-group thumb_box" >
            <label>{{trans('admin::view.thumbnail')}}</label>
            <div class="thumb_group" id="album_feature_img">
                <div class="thumb_item">
                    @if ($item->thumbnail)
                    <p class="file_item">
                        {!! $item->getThumbnail('full') !!}
                        <a class="f_close"></a>
                        <input type="hidden" name="file_ids[]" value="{{$item->image_id}}">
                    </p>
                    @endif
                </div>
            </div>
            {!! errorField('file_ids') !!}
            <div><button type="button" class="btn btn-default btn-files-modal"
                         data-href="{{ route('admin::file.dialog', ['el_preview' => '#album_feature_img']) }}">{{trans('admin::view.add_image')}}</button></div>
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(false), $item->status, ['class' => 'form-control']) !!}
        </div>

        <input type="hidden" name="lang" value="{{$lang}}">
        {!! errorField('lang') !!}

        <div class="form-group">
            <a href="{{route('admin::album.index')}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>
        </div>

    </div>

</div>

{!! Form::close() !!}

@stop

@section('foot')

@include('admin::file.manager')

@stop

