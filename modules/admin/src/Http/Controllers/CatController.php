<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Validation\ValidationException;
use App\Exceptions\DbException;
use DB;

class CatController extends Controller {

    protected $cat;
    protected $locale;

    public function __construct(Tax $cat) {
        canAccess('manage_cats');
        
        $this->cat = $cat;
        $this->locale = current_locale();
    }

    public function index(Request $request) {
        $cats = $this->cat->getData('cat', $request->all()); 
        $tableCats = $this->cat->tableCats($cats); 
        return view('manage.cat.index', ['items' => $cats, 'tableCats' => $tableCats]);
    }

    public function create() {
        $parents = $this->cat->getData('cat', [
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name'],
            'per_page' => -1,
            'orderby' => 'td.name'
        ]);
        return view('manage.cat.create', ['parents' => $parents, 'lang' => $this->locale]);
    }

    public function store(Request $request) {
        DB::beginTransaction();
        try {
            $this->cat->insertData($request->all(), 'cat');
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('manage.store_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->validator);
        } catch (DbException $ex) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error_mess', $ex->getMess());
        }
    }

    public function edit($id, Request $request) {
        $lang = current_locale();
        if($request->has('lang')){
            $lang = $request->get('lang');
        }
        $item = $this->cat->findByLang($id, ['taxs.*', 'td.*'], $lang);
        $parents = $this->cat->getData('cat', [
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name'],
            'exclude' => [$id],
            'per_page' => -1,
            'orderby' => 'td.name'
        ]);
        return view('manage.cat.edit', compact('lang', 'item', 'parents'));
    }

    public function update($id, Request $request) {
        try {
            $this->cat->updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('manage.update_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->validator);
        }
    }

    public function destroy($id) {
        if (!$this->cat->destroy($id)) {
            return redirect()->back()->with('error_mess', trans('manage.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('manage.destroy_success'));
    }

    public function multiAction(Request $request) {
        try {
            $this->cat->actions($request);
            return redirect()->back()->withInput()->with('succ_mess', trans('message.action_success'));
        } catch (\Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }

}
