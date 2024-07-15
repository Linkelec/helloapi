<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index']);
    }

    public function index(Request $request)
    {
        $profiles = Profile::where('status', 'active')->get([
            'first_name', 
            'last_name', 
            'image',
            'status',
            'created_at',
            'updated_at',
        ]);

        if ($request->user('sanctum')) {
            return response()->json($profiles);
        }

        $profiles = $profiles->makeHidden('status');
        
        return response()->json($profiles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'status' => 'required|in:inactive,pending,active',
        ]);

        $profile = Profile::create($request->all());

        return response()->json($profile, 201);
    }

    public function update(Request $request, Profile $profile)
    {
        $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:inactive,pending,active',
        ]);

        $profile->update($request->all());

        return response()->json($profile);
    }

    public function destroy(Profile $profile)
    {
        $profile->delete();

        return response()->json(null, 204);
    }
}
