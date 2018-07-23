@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_posts'))

@section('content')

{!! showMessage() !!}

{!! Form::open(['method' => 'put', 'route' => ['admin::post.update', $item->id]]) !!}

<div class="row">
    <div class="col-sm-9">

        @include('admin::parts.lang_edit_tabs', ['route' => 'admin::post.edit'])

        <div class="form-group">
            <label>
                {{trans('admin::view.name')}} (*)
                <a href="{{ route('front::post.view', ['id' => $item->id, 'slug' => $item->slug]) }}" target="_blank">{{ trans('admin::view.view') }}</a>
            </label>
            {!! Form::text('locale[title]', $item->title, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('locale.title') !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.slug')}}</label>
            {!! Form::text('locale[slug]', $item->slug, ['class' => 'form-control', 'placeholder' => trans('admin::view.slug')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.content')}}</label>
            <textarea class="form-control editor_content" name="locale[content]" rows="15">{!! htmlentities($item->content) !!}</textarea>
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.excerpt')}}</label>
            {!! Form::textarea('locale[excerpt]', $item->excerpt, ['class' => 'form-control editor_excerpt', 'rows' => 5, 'placeholder' => trans('admin::view.excerpt')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.meta_keyword')}}</label>
            {!! Form::text('locale[meta_keyword]', $item->meta_keyword, ['class' => 'form-control', 'placeholder' => trans('admin::view.meta_keyword')]) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.meta_desc')}}</label>
            {!! Form::textarea('locale[meta_desc]', $item->meta_desc, ['class' => 'form-control', 'rows' => 2, 'placeholder' => trans('admin::view.meta_desc')]) !!}
        </div>
        
        <div class="row">
        
            <div class="col-sm-6">
                <label>{{trans('admin::view.created_at')}}</label>
                <div class="time_group form-group">
                    <div class="t_field">
                        <span>{{trans('admin::view.day')}}</span>
                        <select name="time[day]" class="form-control">
                            {!! rangeOptions(1, 31, $item->created_at->format('d')) !!}
                        </select>
                    </div>
                    <div class="t_field">
                        <span>{{trans('admin::view.month')}}</span>
                        <select name="time[month]" class="form-control">
                            {!! rangeOptions(1, 12, $item->created_at->format('m')) !!}
                        </select>
                    </div>
                    <div class="t_field">
                        <span>{{trans('admin::view.year')}}</span>
                        <select name="time[year]" class="form-control">
                            {!! rangeOptions(2010, 2030, $item->created_at->format('Y')) !!}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{trans('admin::view.author')}}</label>
                    {!! Form::select('author_id', $users, $item->author_id, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group col-sm-6">
                <div class="form-group">
                    <label>
                        {!! Form::checkbox('is_feature', 1, $item->is_feature) !!}
                        {{ trans('admin::view.is_feature') }}
                    </label>
                </div>
                <div class="form-group">
                    <label>{{ trans('admin::view.post_format') }}</label>
                    {!! Form::select('post_format', $listFormats, $item->post_format, ['class' => 'form-control']) !!}
                </div>
            </div>
            
        </div>

    </div>
    <div class="col-sm-3">

        <div class="form-group thumb_box" >
            <label>{{trans('admin::view.thumbnail')}}</label>
            <div class="thumb_group">
                @if ($item->thumbnail)
                <p class="file_item">
                    {!! $item->getThumbnail('full') !!}
                    <a class="f_close"></a>
                    <input type="hidden" name="file_ids[]" value="{{$item->thumb_id}}">
                </p>
                @endif
            </div>
            <div>
                <button type="button" class="btn btn-default btn-files-modal" 
                        data-href="{{route('admin::file.dialog', ['multiple' => false])}}">{{trans('admin::view.add_image')}}</button>
            </div>
        </div>
        
        <div class="form-group">
            <label>{{trans('admin::view.status')}}</label>
            {!! Form::select('status', AdView::getStatusLabel(), $item->status, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.categories')}}</label>
            <ul class="cat-check-lists">
                {!! catCheckLists($cats, $curr_cats) !!}
            </ul>
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.new_tags')}}</label>
            <select name="new_tags[]" multiple class="new_tags form-control" style="width: 97%;">
                @if(old('new_tags'))
                    @foreach(old('new_tags') as $tag)
                    <option selected value="{{$tag}}">{{$tag}}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.available_tags')}}</label>
            <select name="tag_ids[]" multiple class="av_tags form-control" style="width: 97%;">
                <?php $curr_tags = $curr_tags ? $curr_tags : []; ?>
                @foreach($tags as $tag)
                <option value="{{$tag->id}}" {{ in_array($tag->id, $curr_tags) ? 'selected' : '' }}>{{$tag->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.comment_status')}}</label>
            {!! Form::select('comment_status', AdView::commentStatusLabel(), $item->comment_status, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.views')}}</label>
            {!! Form::number('views', $item->views, ['class' => 'form-control']) !!}
        </div>
        
        <input type="hidden" name="lang" value="{{$lang}}">
        {!! errorField('lang') !!}

        <a href="{{route('admin::post.index', ['status' => 1])}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>

    </div>
</div>

{!! Form::close() !!}

@stop

@section('foot')

@include('admin::file.manager')

@include('admin::parts.tinymce-script')

@stop

