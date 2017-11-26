<?php
use Admin\Facades\AdConst;
?>

@extends('admin::layouts.manage')

@section('title', trans('admin::view.man_menucats'))

@section('head')
<link rel="stylesheet" href="/modules/admin/css/nested_menu.css">
@stop

@section('body_attrs', 'ng-app=ngMenu ng-controller=MenuCtrl')

@section('content')

{!! showMessage() !!}

@include('admin::parts.lang_edit_tabs', ['route' => 'admin::menucat.edit'])

{!! Form::open(['method' => 'put', 'route' => ['admin::menucat.update', $item->id], 'ng-submit' => 'updateOrder($event)']) !!}

<div class="row">
    <div class="col-sm-4">

        <div class="form-group">
            <label>{{trans('admin::view.name')}} (*)</label>
            {!! Form::text('locale[name]', $item->name, ['class' => 'form-control', 'placeholder' => trans('admin::view.name')]) !!}
            {!! errorField('locale.name') !!}
        </div>

        <div class="form-group">
            <label>{{trans('admin::view.slug')}}</label>
            {!! Form::text('locale[slug]', $item->slug, ['class' => 'form-control', 'placeholder' => trans('admin::view.slug')]) !!}
        </div>

        <input type="hidden" name="lang" value="{{$lang}}">
        {!! errorField('lang') !!}


        <div class="panel-group list_types" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#page_type" aria-expanded="true" aria-controls="collapseOne">
                            {{trans('admin::view.pages')}}
                        </a>
                    </h4>
                </div>
                <div id="page_type" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <div class="max-mdh" ng-if="pages.length > 0">
                            <div ng-repeat="page in pages">
                                <label><input type="checkbox" ng-click="addMenu(page, 2)"> <span ng-bind="page.title"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#cat_type" aria-expanded="false" aria-controls="collapseTwo">
                            {{trans('admin::view.category')}}
                        </a>
                    </h4>
                </div>
                <div id="cat_type" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        <div class="max-mdh" ng-if="cats.length > 0">
                            <div ng-repeat="cat in cats">
                                <label><input type="checkbox" ng-click="addMenu(cat, 3)"> <span ng-bind="cat.name"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#post_type" aria-expanded="false" aria-controls="collapseThree">
                            {{ trans('admin::view.posts') }}
                        </a>
                    </h4>
                </div>
                <div id="post_type" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">
                        <div class="max-mdh" ng-if="posts.length > 0">
                            <div ng-repeat="post in posts">
                                <label><input type="checkbox" ng-click="addMenu(post, 1)"> <span ng-bind="post.title"></span></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="paginate">
                        <ul class="pager pagination-sm" ng-if="post_total > post_last_page">
                            <li><a href="javascript:void(0)" ng-click="postGoUrl(post_prev_page_url)"><i class="fa fa-arrow-left"></i> {{trans('admin::view.previous')}}</a></li>
                            <li><a href="javascript:void(0)" ng-click="postGoUrl(post_next_page_url)">{{trans('admin::view.next')}} <i class="fa fa-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#custom_type" aria-expanded="false" aria-controls="collapseThree">
                            {{trans('admin::view.custom')}}
                        </a>
                    </h4>
                </div>
                <div id="custom_type" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">
                        <div class="form-group">
                            <label>{{trans('admin::view.title')}}</label>
                            <input type="text" ng-model="newcustom.title" class="form-control" placeholder="{{trans('admin::view.title')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('admin::view.url')}}</label>
                            <input type="text" ng-model="newcustom.link" class="form-control" placeholder="http://">
                        </div>
                    </div>
                </div>
            </div>

            <br />
            <button type="button" ng-click="createMenus()" class="btn btn-primary btn-sm btn-add-menu"><i class="fa fa-plus"></i> {{trans('admin::view.add_to_menu')}}</button>
        </div>

    </div>

    <div class="col-sm-6">

        <h3 class="box-title"><label>{{trans('admin::view.list_menus')}}</label></h3>

        <script type="text/ng-template" id="items_renderer.html">
            <div class="mi-inner">
                <div ui-nested-sortable-handle class="handle">
                    {% item.title %}
                </div>
                <div class="">
                    <div class="btn-edit">
                        <span class="title">{% item.menu_type | menutype %}</span>
                        <a href="#mi-content-{% item.id %}" role="button" data-toggle="collapse" data-parent="#menus_sortable" aria-expanded="true" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" ng-click="removeMenu(item)" ng-confirm="{{trans('admin::view.remove')}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                    </div>
                    <div class="collapse mi-content" role="tabpanel" id="mi-content-{% item.id %}">
                        <div class="form-group">
                            <label>{{trans('admin::view.target')}}: </label> <span ng-menu-target="{% item.id %}"></span>
                        </div>
                        <div class="form-group">
                            <label>{{trans('admin::view.title')}}</label>
                            <input type="text" name="menus[{% item.id %}][locale][title]" value="{% item.title %}" class="form-control item-title">
                        </div>
                        <div class="form-group" ng-if="item.menu_type==0">
                            <label>{{trans('admin::view.url')}}</label>
                            <input type="text" name="menus[{% item.id %}][locale][link]" value="{% item.link %}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ trans('admin::view.open_type') }}</label>
                            <select name="menus[{% item.id %}][open_type]" class="form-control">
                                <option ng-selected="item.open_type==''" value=" ">{{trans('admin::view.current_tab')}}</option>
                                <option ng-selected="item.open_type=='_blank'" value="_blank">{{trans('admin::view.new_tab')}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{trans('admin::view.icon')}}</label>
                            <input type="text" name="menus[{% item.id %}][icon]" value="{% item.icon %}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ trans('admin::view.status') }}</label>
                            <select name="menus[{% item.id %}][status]" class="form-control">
                                <option ng-selected="item.status=={{ AdConst::STT_PUBLISH }}" value="1">{{trans('admin::view.publish')}}</option>
                                <option ng-selected="item.status=={{ AdConst::STT_DRAFT }}" value="0">{{trans('admin::view.draft')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <ol ui-nested-sortable="options" ng-model="item.childs">
              <li class="menu-item" ng-repeat="item in item.childs" ui-nested-sortable-item="" ng-include="'items_renderer.html'">
              </li>
            </ol>
        </script>

        <div class="panel-group">
            <ol ui-nested-sortable="options" ng-model="menus" id="menus_sortable">
                <li class="menu-item" ng-repeat="item in menus" ui-nested-sortable-item="" ng-include="'items_renderer.html'"></li>
            </ol>
        </div>

        <br />
        <div class="form-group">
            <a href="{{route('admin::menucat.index')}}" class="btn btn-warning"><i class="fa fa-long-arrow-left"></i> {{trans('admin::view.back')}}</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('admin::view.update')}}</button>
        </div>
    </div>
</div>

{!! Form::close() !!}

@stop

@section('foot')

<script src="/plugins/angular/angular.min.js"></script>
<script src="/plugins/angular/angular-nestedSortable.js"></script>
<script src="/modules/admin/js/menu.js"></script>
<script>

    var group_id = '<?php echo ($item) ? $item->id : 0; ?>';
    var menu_nested_url = '<?php echo route('admin::menucat.to_nested', ['group_id' => ($item) ? $item->id : -1]); ?>';
    var menu_type_url = '<?php echo route('admin::menu.get_type') ?>';
    var store_items_url = '<?php echo route('admin::menucat.store_items') ?>';
    var order_items_url = '<?php echo route('admin::menucat.update_order_items') ?>';
    var api_url = '<?php echo route('admin::index') . '/api/'; ?>';
    var menu_type = <?php echo json_encode(AdView::listMenuTypes()); ?>;
    var _lang = '<?php echo $lang; ?>';
    var remove_item_url = '<?php echo route('admin::menu.asyn_destroy'); ?>';

</script>

@stop

