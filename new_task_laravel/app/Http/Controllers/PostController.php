<?php

namespace App\Http\Controllers;
use App\Http\Requests\PostRequest;
use App\Models\Post;
class PostController extends Controller
{
    public static function setPost(PostRequest $req){
        $newPost = new Post();

        $newPost->title = $req->title;
        $newPost->post_message = $req->post_message;
        $newPost->website_id = $req->website_id;
        $newPost->save();

        return 200;
    }

}
