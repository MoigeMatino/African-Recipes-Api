<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ApiCommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::paginate(10);

        return $comments;
    }

    public function show(Comment $comment)
    {
        return response()->json($comment, 200);
    }

    public function edit(Request $request, Comment $comment)
    {
        return $comment;
    }

    public function destroy($id)
    {
        // Todo: Use events for this
    }
}