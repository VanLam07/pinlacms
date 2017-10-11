<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Exception;
use DB;

class BaseController extends Controller {
    
    protected $cap_edit = null;
    protected $cap_remove = null;
    protected $cap_accept = null;

    public function multiActions(Request $request) {
        try {
            $this->multiPermissAction($request->get('item_ids'), $request->input('action'));
            
            $this->model->actions($request);
            return redirect()->back()->with('succ_mess', trans('admin::message.do_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withErrors($ex->errors());
        } catch (Exception $ex) {
            return redirect()->back()->with('error_mess', $ex->getMessage());
        }
    }
    
    public function store(Request $request) {
        if ($this->cap_accept) {
            canAccess($this->cap_accept);
        }
        
        DB::beginTransaction();
        try {
            $this->model->insertData($request->all());
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (ValidationException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
    
    public function update($id, Request $request) {
        if ($this->cap_accept) {
            canAccess($this->cap_accept);
        }
        
        DB::beginTransaction();
        try {
            $this->model->updateData($id, $request->all());
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
        } catch (ValidationException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
    
    public function multiPermissAction($ids, $action) {
        if (!$this->cap_edit && !$this->cap_remove) {
            return;
        }
        if (!$ids) {
            return;
        }
        $items = $this->model->whereIn('id', $ids)->get();
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
                        throw new Exception (trans('admin::view.authorize'));
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
                        throw new Exception (trans('admin::view.authorize'));
                    }
                }
                break;
            default:
                break;
        }
    }
    
}
