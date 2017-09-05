@extends('layouts.frontend')

@section('keyword', $page->meta_keyword)
@section('description', $page->meta_desc)

@section('title', $page->title)

@section('content_col3')

<div class="_wrapper _upload_box">
    
    {!! show_messes() !!}
    
    <ul class="nav nav-tabs _upload-tabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#video_upload" role="tab"><i class="fa fa-youtube"></i> Video</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#music_upload" role="tab"><i class="fa fa-music"></i> Nhạc</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#image_upload" role="tab"><i class="fa fa-image"></i> Hình ảnh</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="video_upload" role="tabpanel">
            <div class="upload_container">
                <h3 class="_box_title">{{trans('front.enter_video_link')}} (Youtube)</h3>
                <div class="form-group">
                    <div class="input-group _preview_group">
                        <input type="text" class="form-control _preview_link">
                        <span class="input-group-btn">
                            <button class="btn btn-primary _preview_btn" data-target="._preview_box" post-link="{{route('get_video_info')}}"><i class="fa fa-eye"></i> {{trans('front.preview')}}</button>
                        </span>
                    </div>
                </div>
            </div>
            
            {!! Form::open(['method' => 'post', 'route' => 'add_media_video']) !!}
            <div class="_preview_box {{ Session::has('errors') ? '' : 'hidden' }}">
                <div class="form-group">
                    <label>{{trans('manage.thumbnail')}}</label>
                    <div>
                        <img class="_preview_thumb img-fluid" alt="thumbnail">
                    </div>
                </div>
                
                <input type="hidden" name="media_type_id" value="{{old('media_type_id')}}" class="_preview_video_id">
                <input type="hidden" name="media_type" value="youtube">
                <input type="hidden" name="thumb_type" value="video">
                
                <div class="form-group">
                    <label>{{trans('manage.name')}}</label>
                    <input type="text" name="{{current_locale()}}[name]" value="{{old(current_locale().'.name')}}" class="form-control _preview_name">
                    {!! error_field(current_locale().'.name') !!}
                </div>
                <div class="form-group">
                    <label>{{trans('manage.description')}}</label>
                    <textarea name="{{current_locale()}}[description]" class="form-control _preview_desc" rows="3">{{old(current_locale().'.description')}}</textarea>
                    {!! error_field(current_locale().'.description') !!}
                </div>
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{trans('front.save')}}</button>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="tab-pane" id="music_upload" role="tabpanel">
            <div class="upload_container">
                <h3 class="_box_title">Tải nhạc lên</h3>
                {!! Form::open(['method' => 'post', 'route' => 'upload_drive', 'files' => true]) !!}
                <div class="form-group">
                    <input type="file" name="upload_files[]" multiple>
                    {!! error_field('upload_files') !!}
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="tab-pane" id="image_upload" role="tabpanel">
            <div class="upload_container">
                <h3 class="_box_title">Tải ảnh lên</h3>
                <div class="form-group">
                    <label class="_upload_zone">
                        <input type="file">
                        <span class="_cell">
                            <span class="btn btn-secondary"><i class="fa fa-file"></i> Chọn file</span>
                        </span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
            </div>
        </div>
    </div>
</div>

@stop


