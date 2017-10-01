@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_medias'))

@section('content')

{!! showMessage() !!}

{!! Form::open(['method' => 'put', 'route' => ['admin::media.update', $item->id]]) !!}

<div class="row">
    <div class="col-sm-8">

        @include('admin::parts.lang_edit_tabs', ['route' => 'admin::media.edit'])

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
            <label>{{trans('admin::view.content')}}</label>
            {!! Form::textarea('locale[description]', $item->description, ['class' => 'form-control', 'rows' => 15, 'placeholder' => trans('admin::view.content')]) !!}
        </div>
        

        <div class="form-group">
            <label>{{trans('admin::view.created_at')}}</label>
            <div class="time_group">
                <div class="t_field">
                    <span>{{trans('admin::view.day')}}</span>
                    <select name="time[day]">
                        {!! rangeOptions(1, 31, $item->created_at->format('d')) !!}
                    </select>
                </div>
                <div class="t_field">
                    <span>{{trans('admin::view.month')}}</span>
                    <select name="time[month]">
                        {!! rangeOptions(1, 12, $item->created_at->format('m')) !!}
                    </select>
                </div>
                <div class="t_field">
                    <span>{{trans('admin::view.year')}}</span>
                    <select name="time[year]">
                        {!! rangeOptions(2010, 2030, $item->created_at->format('Y')) !!}
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.author')}}</label>
            {!! Form::select('author_id', $users, $item->author_id, ['class' => 'form-control']) !!}
        </div>


    </div>
    <div class="col-sm-4">

        <div class="form-group thumb_box" >
            <label>{{trans('admin::view.thumbnail')}}</label>
            <div class="thumb_group">
                <div class="thumb_item">
                    @if ($item->thumbnail)
                    <p class="file_item">
                        {!! $item->getThumbnail('full') !!}
                        <a class="f_close"></a>
                        <input type="hidden" name="file_ids[]" value="{{$item->thumb_id}}">
                    </p>
                    @endif
                </div>
            </div>
            {!! errorField('file_ids') !!}
            <div><button type="button" class="btn btn-default btn-files-modal" data-href="{{route('admin::file.dialog')}}">{{trans('admin::view.add_image')}}</button></div>
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(false), $item->status, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.categories')}}</label>
            <ul class="cat-check-lists">
                @if($albums)
                @foreach($albums as $al)
                <li><label>{!! Form::checkbox('cat_ids[]', $al->id, in_array($al->id, $currAlbums)) !!} {{$al->name}}</label></li>
                @endforeach
                @endif
            </ul>
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.views')}}</label>
            {!! Form::number('views', $item->views, ['class' => 'form-control']) !!}
        </div>
        
        <input type="hidden" name="lang" value="{{$lang}}">
        {!! errorField('lang') !!}

        <a href="{{route('admin::media.index', ['status' => 1])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>

    </div>
</div>

{!! Form::close() !!}

@stop

@section('foot')
<script src="/public/plugins/tinymce/tinymce.min.js"></script>
<script src="/public/modules/admin/js/tinymce_script.js"></script>

@include('admin::file.manager')

@stop

