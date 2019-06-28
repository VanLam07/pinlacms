<div class="paginate">
    <div class="row">
        <div class="col-sm-6">
            {{ trans('admin::view.total') . ' ' . $items->total() 
            .' '. trans('admin::view.entities') .' / '. $items->lastPage() . ' '. trans('admin::view.pages') }} 
        </div>
        <div class="col-sm-6 text-right">
            {!! $items->appends(request()->all())->links() !!}
        </div>
    </div>
</div>

