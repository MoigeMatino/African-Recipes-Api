<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
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
        // Todo: return write newsletter form
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|max:255|string',
                'content' => 'string',
            ]);

            $newsletter = Newsletter::create([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return redirect()->route('newsletter.show', $newsletter)->with(['success', 'Newsletter draft created!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error', $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Newsletter $newsletter)
    {
        // Todo: create show newsletter view
        return $newsletter;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Newsletter $newsletter)
    {
        // TOdo: reutn resource edit view
        return $newsletter;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        try {
            $request->validate([
                'title' => 'string|max:255|required',
                'content' => 'string',
            ]);

            $newsletter->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            if ($newsletter->status == 'published') {
                // Todo: Dispatch update email event
                return redirect()->route('newsletter.show', $newsletter)->with(['success' => 'Email updates sent out.']);
            }

            return redirect()->route('newsletter.show', $newsletter)->with(['success' => 'Newsletter draft updated.']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error', $th->getMessage()]);
        }
    }

    /**
     * Publish a newsletter.
     */
    public function publish(Newsletter $newsletter)
    {
        try {
            // Todo: Dispatch newsletter publish event
            $newsletter->update(['status' => 'published']);

            return redirect()->route('newsletter.show', $newsletter)->with(['success', 'Newsletter published.']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error', $th->getMessage()]);
        }
    }
}
