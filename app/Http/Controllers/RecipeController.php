<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Todo: Check authorization & ownership
        return Recipe::has('comments')->with(['comments' => function ($query) {
            return $query->latest()->limit(10);
        }])->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('recipe.create'); Todo: Create Recipe create web form
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Todo check for authentication and authorization

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'instructions' => 'required|string',
                'prep_time' => 'required|string|max:255',
                'cook_time' => 'required|string|max:255',
                'total_time' => 'required|string|max:255',
                'servings' => 'required|integer|max:255',
                'image_url' => 'url:http,https',
                'video_url' => ['regex:/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube(-nocookie)?\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|live\/|v\/)?)([\w\-]+)(\S+)?$/'], // Must be YT video
                'ingredients' => 'required|string',
                'nutritional_info' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $user = User::first();

            $recipe = $user->recipes()->create([
                'title' => $request->title,
                'description' => $request->description,
                'instructions' => $request->instructions,
                'prep_time' => $request->prep_time,
                'cook_time' => $request->cook_time,
                'total_time' => $request->total_time,
                'servings' => $request->servings,
                'image_url' => $request->image_url,
                'ingredients' => collect(explode('\\n', $request->ingredients))->toJson(),
                'nutritional_info' => collect(explode('\\n', $request->nutritional_info))->toJson(),
            ]);

            // Assign tags to recipe
            if ($request->has('tags')) {
                foreach ($request->tags as $tag) {
                    $recipe->tags()->create(['name' => $tag]);
                }
            }

            return redirect()
                ->route('recipe.show', ['recipe' => $recipe])
                ->with(['success' => 'Recipe created']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        return Recipe::with(['author', 'collaborators', 'tags', 'comments' => function ($query) {
            return $query->latest()->paginate(15);
        }])->find($recipe->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        return $recipe->with('collaborators', 'tags')->find($recipe->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        try {
            // Validate Recipe fields
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'instructions' => 'required|string',
                'prep_time' => 'required|string|max:255',
                'cook_time' => 'required|string|max:255',
                'total_time' => 'required|string|max:255',
                'servings' => 'required|integer|max:255',
                'image_url' => 'url:http,https',
                'video_url' => ['regex:/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube(-nocookie)?\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|live\/|v\/)?)([\w\-]+)(\S+)?$/'], // Must be YT video
                'ingredients' => 'required|string',
                'nutritional_info' => 'required|string',
            ]);

            $recipe->update([
                'title' => $request->title,
                'description' => $request->description,
                'instructions' => $request->instructions,
                'prep_time' => $request->prep_time,
                'cook_time' => $request->cook_time,
                'total_time' => $request->total_time,
                'servings' => $request->servings,
                'image_url' => $request->image_url,
                'ingredients' => collect(explode('\\n', $request->ingredients))->toJson(),
                'nutritional_info' => collect(explode('\\n', $request->nutritional_info))->toJson(),
            ]);

            // Assign tags to recipe
            if ($request->has('tags')) {
                // Delete previous tags
                foreach ($recipe->tags as $tag) {
                    $tag->delete();
                }

                // Recreate new tags
                foreach ($request->tags as $tag) {
                    $recipe->tags()->create(
                        ['tag' => $tag]
                    );
                }
            }

            return redirect()->route('recipe.show', $recipe)->with('success', 'Recipe updated');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        // Todo: Will use events to delete this
    }

    /**
     * Add or remove collaborators from recipes
     */
    public function add_collaborators(Request $request)
    {
        // Todo: Work on collaborators

        // Validate Recipe fields
        // $request->validate([
        //     $request->collaborators => 'string|required'
        // ]);

        // // Assign Collaborators to Recipe

        // // $validator = Validator::make($request->all(),  [

        // foreach(explode(';', $request->collaborators) as $collaborator){ // Expect a string of semicolon delimited email addresses
        //     $user = User::where('email', $collaborator)->first();

        //     if(!$user){

        //     }
        // }

    }

    /**
     * Add a rating to a recipe
     */
    public function rate(Request $request, Recipe $recipe)
    {
        try {
            $request->validate([
                'rating' => 'required|integer|min:1|max:5'
            ]);

            // To get auth user
            $recipe->user_ratings()->attach(User::First(), ['rating' => $request->rating]);

            return redirect()->route('recipe.show', $recipe)->with(['success', 'Recipe rated!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function like(Request $request, Recipe $recipe)
    {
        try {
            // To get auth user
            $recipe->users_liked()->attach(User::First());

            return redirect()->route('recipe.show', $recipe)->with(['success', 'Recipe liked!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
