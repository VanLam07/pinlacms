<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;

use Admin\Http\Controllers\BaseController;
use App\Models\Subscribe;
use PlMenu;
use Breadcrumb;

class SubscribeController extends BaseController
{
    protected $cap_accept = 'manage_subscribes';
    protected $model;

    public function __construct() {
        parent::__construct();
        Breadcrumb::add(trans('admin::view.subscribe'), route('admin::subs.index'));
        PlMenu::setActive('subscribes');
        $this->model = Subscribe::class;
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);

        return view('admin::subscribe.index', [
            'items' => Subscribe::getData($request->all())
        ]);
    }

    public function create()
    {
        canAccess($this->cap_accept);

        return view('admin::subscribe.create');
    }
    
    public function redirectEdit($item)
    {
        return redirect()->route('admin::subs.edit', ['id' => $item->id])->with('succ_mess', trans('admin::message.store_success'));
    }
    
    public function edit($id)
    {
        canAccess($this->cap_accept);
        
        return view('admin::subscribe.edit', ['item' => Subscribe::findOrFail($id)]);
    }

}
