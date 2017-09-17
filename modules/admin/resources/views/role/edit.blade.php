@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_roles'))

@section('content')

    {!! showMessage() !!}

    {!! Form::open(['method' => 'put', 'route' => ['admin::role.update', $item->id]]) !!}

        <div class="form-group row">
            <label class="col-sm-2">{{ trans('admin::view.name') }} (*)</label>
            <div class="col-sm-6">
                {!! Form::text('name', $item->name, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
                {!! errorField('name') !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2">{{ trans('admin::view.description') }}</label>
            <div class="col-sm-6">
                {!! Form::text('label', $item->label, ['class' => 'form-control', 'placeholder' => trans('admin::view.description')]) !!}
                {!! errorField('label') !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2">{{ trans('admin::view.default') }}</label>
            <div class="col-sm-6">
                {!! Form::select('default', [0 => 'No', 1 => 'Yes'], $item->default, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-2">
                <h4>@lang('admin::view.list_caps')</h4>
            </div>
            <div class="col-sm-6">
                <div class="table-responsive">
                    @if(!$caps->isEmpty())
                    <?php 
                    $userCaps = $item->listCapsLevel();
                    $levelCaps = AdView::getCapsLevel();
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('admin::view.permission')</th>
                                @foreach($levelCaps as $key => $label)
                                <th>
                                    <label><input type="checkbox" class="item-all" data-level="{{ $key }}"> {{ $label }}</label>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($caps as $cap)
                            <tr>
                                <td>
                                    <label title="{{ $cap->label }}">
                                        {{ $cap->name }}
                                    </label>
                                </td>
                                @foreach(array_keys($levelCaps) as $keyCap)
                                <td>
                                    <?php 
                                    $capChecked = ''; 
                                    if (isset($userCaps[$cap->name]) && $userCaps[$cap->name] == $keyCap) {
                                        $capChecked = 'checked';
                                    }
                                    ?>
                                    <label class="display_block">
                                        <input type="radio" name="caps_level[{{ $cap->name }}]" 
                                               value="{{ $keyCap }}" {{ $capChecked }} 
                                               class="item-role" data-level="{{ $keyCap }}">
                                    </label>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('admin::role.index') }}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{ trans('admin::view.back') }}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('admin::view.update') }}</button>
        </div>

    {!! Form::close() !!}

@stop

@section('foot')
<script>
    var MAX_LEVEL = <?php echo Admin\Facades\AdView\AdView::CAP_OTHER; ?>;
    (function ($) {
        $('.item-all').click(function () {
            var level = $(this).data('level');
            $('.item-role[data-level="'+ level +'"]').prop('checked', $(this).is(':checked'));
        });
        $('.item-role').change(function () {
            for (var level = 0; level <= MAX_LEVEL; level++) {
                $('.item-all[data-level="'+ level +'"]').prop('checked', 
                        $('.item-role[data-level="'+ level +'"]:checked').length === $('.item-role[data-level="'+ level +'"]').length);
            }
        }).change();
    })(jQuery);
</script>
@stop

