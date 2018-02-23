<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function get($page_num)
    {
        $comments= Comment::paginate(2);
        return view('admin.comment', ['comments' => $comments, 'comment' => false, 'page_now' => $page_num, 'all_page' => $comments['count'], 'active' => 'comment']);


        $limit = 10;
        $obj_pagination = new pagination("comments", $page_num, $limit);
        $comments = $obj_pagination->paginate('id');
        $counter_comments = $page_num * $limit - $limit;
        if ($comments['status'] == '350') {
            return view('admin.comment', ['counter_news' => $counter_comments, 'news' => $comments['data'], 'page_now' => $page_num, 'all_page' => $comments['count'], 'active' => 'news']);
        } elseif ($comments['status'] == '300') {
            return view('admin.comment', ['counter_news' => $counter_comments, 'news' => false, 'page_now' => $page_num, 'all_page' => '', 'active' => 'news']);

        } else {
            //error baraye safhe moshke fani
        }
    }
}
