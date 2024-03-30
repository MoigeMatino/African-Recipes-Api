<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Todo: Check permissions & auth
        return Subscriber::paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Todo: Return subscribe form
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => "required|string|max:255",
                "email" => "required|unique:users,email",
            ]);

            $subscriber = Subscriber::create([
                "name" => $request->name,
                "email" => $request->email,
            ]);

            return redirect()->route('subscriber.show', $subscriber)->with(["success", "Sucessfully subscribed!"]);
        } catch (\Throwable $th) {
            redirect()->back()->with("error", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscriber $subscriber)
    {
        // Todo: Show subscribers details form
        return $subscriber;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscriber $subscriber)
    {
        // Todo: Return edit name field view
        return $subscriber;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        $request->validate(['name' => 'required|string']);

        $subscriber->update(['name' => $request->name]);

        return redirect()->route('subscriber.show', $subscriber)->with('success', 'Field updated.';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscriber $subscriber)
    {
        return $subscriber->delete();
        // Todo: Dispatch Events
    }
}
