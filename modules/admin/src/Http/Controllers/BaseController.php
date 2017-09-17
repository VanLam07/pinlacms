<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Exception;

class BaseController extends Controller {
    
    public function multiActions(Request $request) {
        try {
            $this->model->actions($request);
            return redirect()->back()->with('succ_mess', trans('admin::message.do_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withErrors($ex->errors());
        } catch (Exception $ex) {
            return redirect()->back()->with('error_mess', $ex->getMessage());
        }
    }
    
    public function navParams() {
        return view()->composer('somePram', 'oke');
    }
    
}
