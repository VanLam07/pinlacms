<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Contact;
use PlMenu;
use Breadcrumb;

class ContactController extends BaseController {

    protected $model;
    protected $cap_accept = 'manage_contacts';

    public function __construct() {
        PlMenu::setActive('contacts');
        parent::__construct();
        Breadcrumb::add(trans('admin::view.contacts'), route('admin::contact.index'));
        $this->locale = currentLocale();
        $this->model = Contact::class;
    }

    public function index(Request $request) {
        canAccess($this->cap_accept);
        
        $data = $request->all();
        $items = Contact::getData($data);
        return view('admin::contact.index', compact('items'));
    }

    public function destroy($id) {
        canAccess($this->cap_accept);
        
        if (!Tax::destroy($id)) {
            return redirect()->back()->with('error_mess', trans('admin::message.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }


}
