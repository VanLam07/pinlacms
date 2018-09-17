<div class="row">
    <div class="col-sm-6">
        
        <div class="form-group">
            <label>{{ trans('dict::view.word') }} (*)</label>
            {!! Form::text('word', $item ? $item->word : null, ['class' => 'form-control', 'placeholder' => trans('dict::view.word')]) !!}
            {!! errorField('word') !!}
        </div>

        <div class="form-group">
            <label>{{ trans('dict::view.type') }}</label>
            {!! Form::text('type', $item ? $item->type : null, ['class' => 'form-control', 'placeholder' => trans('dict::view.type')]) !!}
            {!! errorField('type') !!}
        </div>

        <div class="form-group">
            <label>{{ trans('dict::view.origin') }} (*)</label>
            {!! Form::text('origin', $item ? $item->origin : null, ['class' => 'form-control', 'placeholder' => trans('dict::view.origin')]) !!}
            {!! errorField('origin') !!}
        </div>

        <div class="form-group">
            <label>{{ trans('dict::view.pronun') }}</label>
            {!! Form::text('pronun', $item ? $item->pronun : null, ['class' => 'form-control', 'placeholder' => trans('dict::view.pronun')]) !!}
            {!! errorField('pronun') !!}
        </div>

        <div class="form-group">
            <label>{{ trans('dict::view.mean') }}</label>
            {!! Form::text('mean', $item ? $item->mean : null, ['class' => 'form-control', 'placeholder' => trans('dict::view.mean')]) !!}
            {!! errorField('mean') !!}
        </div>
        
    </div>
    <div class="col-sm-6">

        <div class="form-group">
            <label>{{ trans('dict::view.detail') }}</label>
            {!! Form::textarea('detail', $item ? $item->detail : null, ['class' => 'form-control editor_short no-resize', 'placeholder' => trans('dict::view.detail'), 'rows' => 6]) !!}
            {!! errorField('detail') !!}
        </div>

        <div class="form-group">
            <label>{{ trans('dict::view.detail_origin') }}</label>
            {!! Form::textarea('detail_origin', $item ? $item->detail_origin : null, ['class' => 'form-control editor_short no-resize', 'placeholder' => trans('dict::view.detail_origin'), 'rows' => 7]) !!}
            {!! errorField('detail_origin') !!}
        </div>
        
    </div>
</div>


