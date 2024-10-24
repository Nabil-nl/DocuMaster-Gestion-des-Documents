<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('member')->get();
        return response()->json($categories, 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user(); // or Auth::user()
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Check if the authenticated user is an admin
        if ($user->hasRole('admin')) {
            // Admin logic: create category directly with user ID
            $category = new Category($request->only(['name', 'description']));
            $category->user_id = $user->id; // Use authenticated user ID directly
        } else {
            // For regular members, check for a corresponding member record
            $member = \App\Models\Member::where('user_id', $user->id)->first();
            if (!$member) {
                return response()->json(['error' => 'Member not found for the authenticated user.'], 404);
            }

            // Use member ID for category creation
            $category = new Category($request->only(['name', 'description']));
            $category->user_id = $member->id; // Use member ID instead of user ID
        }

        $category->save();

        return response()->json($category, 201); // Return created category as JSON
    }




    public function show(Category $category)
    {
        return response()->json($category, 200);
    }

    public function update(Request $request, Category $category)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        Log::info('Authenticated User ID:', ['user_id' => $user->id]);
        Log::info('Category ID being updated:', ['category_id' => $category->id]);
        Log::info('Category User ID:', ['category_user_id' => $category->user_id]);
        Log::info('User Roles:', ['roles' => $user->getRoleNames()]);

        // Check if the authenticated user is an admin, owner of the category, or has specific member permissions
        if ($user->hasRole('admin') || $category->user_id === $user->id || $user->hasPermissionTo('edit categories')) {
            $request->validate([
                'name' => 'required',
                'description' => 'nullable',
            ]);

            // Update the category while ignoring user_id
            $category->update($request->only(['name', 'description']));

            return response()->json([
                'message' => 'Category updated successfully.',
                'category' => $category
            ], 200);
        } else {
            return response()->json(['error' => 'You are not authorized to update this category.'], 403);
        }
    }



    public function destroy(Category $category)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Log the user and category information for debugging
        Log::info('Authenticated User ID:', ['user_id' => $user->id]);
        Log::info('Category ID being deleted:', ['category_id' => $category->id ?? 'null']);
        Log::info('Category User ID:', ['category_user_id' => $category->user_id ?? 'null']);
        Log::info('User Roles:', ['roles' => $user->getRoleNames()]);

        // Check if the authenticated user is an admin, owner of the category, or has specific member permissions
        if ($user->hasRole('admin') || $category->user_id === $user->id || $user->hasPermissionTo('delete categories')) {
            $category->delete();
            return response()->json([
                'message' => 'Category deleted successfully! 🎉', // Success message with emoji
            ], 200); // Use 200 OK status instead of 204 No Content
        } else {
            return response()->json(['error' => 'You are not authorized to delete this category.'], 403);
        }
    }
}
