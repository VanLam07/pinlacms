<?php

namespace Dict\Http\Controllers;

use Front\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Dict\Models\DictEnVn;
use Dict\Models\DictSentence;
use PlPost;
use Validator;

class DictController extends BaseController
{
    
    public function index()
    {
        
    }
    
    public function makeWord()
    {
        return DictEnVn::makeRandWord();
    }
    
    public function makeSentence(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->withInput();
        }
        $data = $request->all();
        $valid = Validator::make($data, [
            'sentence' => 'required',
            'word_id' => 'required'
        ]);
        $word = DictEnVn::findOrFail($data['word_id']);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        $data['user_id'] = auth()->id();
        DictSentence::create($data);
        return redirect()->to(route('dict::word.view_word', ['word' => str_slug($word->word), 'id' => $word->id]));
    }
    
    public function viewWord($slug, $id)
    {
        $word = DictEnVn::findOrFail($id);
        $page = PlPost::getTemplatePage('generate-word');
        $sentences = DictSentence::getData(['word_id' => $id]);
        return view('front::templates.generate-word', compact('word', 'page', 'sentences'));
    }

    public function deleteWord($id)
    {
        $word = DictEnVn::find($id);
        if (!$word) {
            return response()->json('Not found item', 404);
        }
        $word->delete();
        return response()->json('Delete successful!');
    }

}
