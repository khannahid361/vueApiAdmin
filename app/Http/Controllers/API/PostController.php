<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends APIController
{
    public function postList()
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;

        $posts = DB::table('posts')
            ->select('title', 'author_name', 'id')
            ->get();

        if (!$posts->isEmpty()) {
            $data = $posts;
        } else {
            $message = 'No data found!';
            $status = false;
        }
        return $this->sendResult($message, $data, $errors, $status);
    }

    public function postAdd(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;

        DB::beginTransaction();
        try {
            $postData = [
                'title' => $request->title,
                'author_name' => $request->author_name,
                'description' => $request->description
            ];
            Post::create($postData);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $message = $e->getMessage();
            $status = false;
        }
        return $this->sendResult($message,$data,$errors,$status);
    }
}
