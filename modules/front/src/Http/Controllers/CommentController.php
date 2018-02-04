<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\PlException;
use App\Models\Comment;

/**
 * Description of CommentController
 *
 * @author Pinla
 */
class CommentController extends Controller
{
    
    public function loadData(Request $request) 
    {
        $valid = Validator::make($request->all(), [
            'post_id' => 'required|numeric'
        ]);
        if ($valid->fails()) {
            return response()->json(trans('front::message.not_found_item'), 422);
        }
        
        $data = $request->all();
        if (!isset($data['parent_id'])) {
            $data['parent_id'] = null;
        }
        
        $comments = Comment::getData($data);
        $commentHtml = $this->renderNested($comments);
        
        $page = 1;
        if (isset($data['page']) && $data['page']) {
            $page = $data['page'];
        }
        $data['page'] = $page + 1;
        
        return [
            'comments' => $commentHtml,
            'has_more' => $comments->hasMorePages(),
            'next_page_url' => route('front::comment.load', $data)
        ];
    }
    
    public function renderNested($comments, $depth = 0)
    {
        $html = '';
        if ($comments->isEmpty()) {
            return $html;
        }
        foreach ($comments as $comment) {
            $html .= view('front::includes.comment-item', ['comment' => $comment, 'depth' => $depth])->render();
        }
        return $html;
    }
    
    public function store(Request $request)
    {   
        $returnJson = $request->ajax() || $request->wantsJson();
        try {
            $comment = Comment::insertData($request->all());
            
            if ($returnJson) {
                return response()->json([
                    'comment' => view('front::includes.comment-item', ['comment' => $comment])->render(),
                    'message' => trans('front::message.do_action_success')
                ]);
            }
            return redirect()->back();
        } catch (PlException $ex) {
            if ($returnJson) {
                return response()->json($ex->getError(), 422);
            }
            return redirect()->back()->with('error_mess', $ex->getError());
        }
    }
    
    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        if (!canDo('remove_comment', $comment->author_id)) {
            return response()->json(trans('admin::view.authorize'), 403);
        }
        return Comment::where('id', $id)->delete();
    }
    
    public function edit($id)
    {
        $comment = Comment::find($id, ['id', 'content']);
        if (!$comment) {
            return response()->json(trans('front::message.not_found_item'), 404);
        }
        return $comment;
    }
    
    public function update(Request $request)
    {
        $id = $request->get('comment_id');
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(trans('front::message.not_found_item'), 404);
        }
        if (!canDo('edit_comment', $comment->author_id)) {
            return response()->json(trans('admin::view.authorize'), 403);
        }
        $comment->update($request->all());
        return [
            'id' => $comment->id,
            'content' => $comment->content
        ];
    }
    
}
