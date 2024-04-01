<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class NewsletterController extends ApiController
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        return Newsletter::paginate(20);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: return write newsletter form
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|string',
            'content' => 'string',
        ]);
    
        $newsletter = Newsletter::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);
    
        return response()->json([
            'message' => 'Newsletter draft created',
            'newsletter' => $newsletter,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Newsletter $newsletter)
    {
        // TODO: create show newsletter view
        return $newsletter;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Newsletter $newsletter)
    {
        // TODO: return resource edit view
        return $newsletter;
    }

    /**
     * Accept form submission from the edit newsletter form
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'string',
        ]);

        $newsletter->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if ($newsletter->status === 'published') {
            // TODO: Dispatch update email event
            return response()->json($newsletter)->with(['status', 'Newsletter dispatched']);
        }

        return response()->json(['success' => 'Newsletter updated', 'updated_newsletter' => $newsletter]);

    }

    public function publish(Request $request, Newsletter $newsletter)
    {
        $newsletter->update([
           'status' => 'published',
        ]);

        return response()->json(['success' => 'Newsletter published', 'published_newsletter' => $newsletter]);

    }

}
    