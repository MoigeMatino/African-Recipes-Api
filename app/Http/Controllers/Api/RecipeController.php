<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RecipeController extends ApiController
{
    public function index()
    {
        // TODO: Implement authentication and authorization
        return Recipe::with(['comments' => function ($query) { // eager load comments
            return $query->latest()->limit(10);
        }])->paginate(10);
    }

    public function show(Recipe $recipe)
    {
        $recipe = Recipe::with([
            'author', 
            'users_liked', 
            'user_ratings', 
            'collaborators', 
            'tags', 'comments' => function ($query) {
                return $query->latest()->paginate(15);
            }
            ])->find($recipe->id);// !Can this be $recipe
        return response()->json([
            'recipe' => $recipe, 
            'likes' => $recipe->likes(), 
            'rating' => $recipe->rating()], 200);
    }

    public function store(Request $request)
    {
        try {
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
                return response()->json($validator->errors(), 422);
            };
            //* TODO change this to the currently logged in users 
            $user = User::find(2);
            $recipe = $user->recipes()->create([
                'title' => $request->title,
                'description' => $request->description,
                'instructions' => $request->instructions,
                'prep_time' => $request->prep_time,
                'cook_time' => $request->cook_time,
                'total_time' => $request->total_time,
                'servings' => $request->servings,
                'image_url' => $request->image_url,
                'video_url' => $request->video_url,
                'ingredients' => collect(explode('\\n', $request->ingredients))->toJson(),
                'nutritional_info' => collect(explode('\\n', $request->nutritional_info))->toJson()
            ]);
            // Add tags to recipe
            if ($request->has('tags')) {
                foreach ($request->tags as $tag) {
                    $recipe->tags()->create([
                        'tag' => $tag
                    ]);
                }
            }
            // Add collaborators
            if ($request->has('collaborators')) {
                $request->validate([
                    
                    'collaborators' => 'string'                
                ]);

                $collaborators = $request->collaborators;

                // Ensure that the collaborators are existing users
                $validator = Validator::make($collaborators, [
                    '*' => 'string|exists:users,username',
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                // Assign collaborators to recipe
                foreach ($collaborators as $collaborator) {
                    $user = User::whereNot('username', $recipe->author->username)
                        ->where('username', $collaborator)
                        ->first();

                    if ($user) {
                        $recipe->collaborators()->attach($user);
                    }
                }
            }

            return response()->json($recipe, 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


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

            // Update tags to recipe
            if ($request->has('tags')) {
                // Delete previous tags
                //* FIXME what if tags are being updated through adding new ones, no need to delete existing
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

            // Update collaborators to recipe
            if ($request->has('collaborators')) {

                // Validate collaborator field
                //* FIXME:Shouldn't the collaborators be an array, not string
                $request->validate([
                    'collaborators' => 'string',
                ]);

                // Assign Collaborators to Recipe
                // $collaborators = array_map(function ($val) {
                //     return trim($val);
                // }, explode(';', $request->collaborators));
                $collaborators = $request->collaborators;
                $validator = Validator::make($collaborators, [
                    '*' => 'string|exists:users,username',
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
                
                foreach ($collaborators as $collaborator) {
                    $user = User::whereNot('username', $recipe->author->username)
                        ->where('username', $collaborator)
                        ->first();
    
                    if ($user) {
                        $recipe->collaborators()->syncWithoutDetaching($user); // Attach without detaching existing
                    }
                }
            }

            return response()->json($recipe, 201)->with('success', 'Recipe updated');
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}   

