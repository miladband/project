<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use Composer\Command\SearchCommand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function get()
    {
        $comments= Comment::paginate(10);
        return view('admin.comment', ['comments' => $comments, 'active' => 'comment']);

    }
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return ['status' => true];
    }

    public function post(Request $request)
    {
        if ($request->ajax()) {
            dd($request);
            $status = $request->input('status');
            if ($status == 'search') {
                $item = $request->input('item');
                $manage = new SearchCommand();
                $search = $manage->search($item);
                if ($search['status'] == '350') {
                    return response()->json(array('status' => true,
                        'data' => $search['search']));
                } else if ($search['status'] == '300') {
                    return response()->json(array('status' => false));
                } else {
                    return response()->json(array('status' => false, 'msg' => 'خطایی در سیستم رخ داده است لطفا هر چه سریعتر این موضوع را به بخش فنی گزارش دهید.'));
                }
            }
        }
    }

}
