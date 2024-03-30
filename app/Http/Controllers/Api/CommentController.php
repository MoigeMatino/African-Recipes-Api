<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::paginate(10);
        return response()->json([
            'comments' => $comments,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
            $validated_data = $request->validate([
                'comment' => 'required|string',
            ]);
            $comment = new Comment;
            $comment->comment = $validated_data['comment'];
            $comment->save();
            return response()->json([
                'message' => 'Comment added successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json(['comment' => $comment], 200);
    }


    public function edit(Request $request, Comment $comment)
    {
        try {
            $request->validate();
            $comment->update(['comment' => $request->comment]);
            return response()->json($comment, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Comment not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
    }


    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error_message' => $e], 404);
        }
    }
}
