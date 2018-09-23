<?php
use Admin\Facades\AdConst;
?>

<div class="wrap pdt-30">
    <h3 class="text-center sub-title">{{ trans('front::view.register_reminder_make_sentence_everyday') }}</h3>

    <div class="mgb-30"></div>

    <div class="post-content">
        {!! $page->content !!}
    </div>

    {!! Form::open([
        'method' => 'post',
        'route' => 'front::quote.register',
    ]) !!}

    <div class="row">
        <div class="col-sm-6 mr-auto ml-auto">

            <div class="form-group">
                <label>{{ trans('front::view.email') }} (*)</label>
                <input type="email" name="email" class="form-control" placeholder="example@mail.com"
                       value="{{ old('email') ? old('email') : null }}">
                {!! errorField('email') !!}
            </div>

            <div class="form-group">
                <label>{{ trans('front::view.full_name') }} (*)</label>
                <input type="text" name="name" class="form-control" placeholder="{{ trans('front::view.full_name') }}"
                       value="{{ old('name') ? old('name') : null }}">
                {!! errorField('name') !!}
            </div>

            <div class="form-group mgb-30">
                <label>{{ trans('front::view.time_receive') }}</label>
                <input type="text" name="time" class="form-control time_picker" placeholder="08:00"
                       value="{{ old('time') ? old('time') : null }}">
                <span class="text-desc">{{ trans('front::view.you_can_fill_multi_time') }}</span>
            </div>

            <div class="form-group text-center">
                <input type="hidden" name="type" value="{{ AdConst::FORMAT_DICT }}">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-send"></i> {{ trans('front::view.Register') }}</button>
            </div>

        </div>
    </div>

    {!! Form::close() !!}

</div>