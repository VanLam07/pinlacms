<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\PlException;
use Exception;
use DB;
use Breadcrumb;

class BaseController extends Controller {
    
    protected $cap_edit = null;
    protected $cap_remove = null;
    protected $cap_accept = null;
    
    public function __construct() {
        Breadcrumb::add(trans('admin::view.dashboard'), route('admin::index'));
    }

    public function multiActions(Request $request) {
        try {
            $this->multiPermissAction($request->get('item_ids'), $request->input('action'));
            
            $this->model::actions($request);
            return redirect()->back()->with('succ_mess', trans('admin::message.do_success'));
        } catch (PlException $ex) {
            return redirect()->back()->with('error_mess', $ex->getError());
        }
    }
    
    public function store(Request $request) {
        if ($this->cap_accept) {
            canAccess($this->cap_accept);
        }
        
        DB::beginTransaction();
        try {
            $this->model::insertData($request->all());
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (PlException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }
    
    public function update($id, Request $request) {
        if ($this->cap_accept) {
            canAccess($this->cap_accept);
        }
        
        DB::beginTransaction();
        try {
            $this->model::updateData($id, $request->all());
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
        } catch (PlException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }
    
    public function multiPermissAction($ids, $action) {
        if (!$this->cap_edit && !$this->cap_remove) {
            return;
        }
        if (!$ids) {
            return;
        }
        $items = $this->model::whereIn('id', $ids);
        if ($action === 'delete') {
            $items->withTrashed();
        }
        $items = $items->get();
        if ($items->isEmpty()) {
            return;
        }
        switch ($action) {
            case 'draft':
            case 'publish':
                if (!$this->cap_edit) {
                    break;
                }
                foreach ($items as $item) {
                    if (!canDo($this->cap_edit, $item->authorId())) {
                        throw new PlException (trans('admin::view.authorize'));
                    }
                }
                break;
            case 'trash':
            case 'delete':
            case 'restore':
                if (!$this->cap_remove) {
                    break;
                }
                foreach ($items as $item) {
                    if (!canDo($this->cap_edit, $item->authorId())) {
                        throw new PlException (trans('admin::view.authorize'));
                    }
                }
                break;
            default:
                break;
        }
    }
    
}
