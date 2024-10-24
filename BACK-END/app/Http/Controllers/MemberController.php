<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    /**
     * Display a listing of all members.
     */
    public function index()
    {
        try {
            $members = Member::all();
            return response()->json([
                'status' => 'success',
                'message' => '📋 Members retrieved successfully.',
                'data' => [
                    'members' => $members
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve members.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new member.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Incoming Request Data: ', $request->all());

            // Validate request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:members',
                'password' => 'required|string|min:8|confirmed',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Start a database transaction
            DB::beginTransaction();

            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images/members', 'public');
                $data['image'] = $imagePath;
            }

            // Create the user in the users table
            $user = \App\Models\User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']), // Hash the password
            ]);
            $user->assignRole('member'); // Assuming you have role management in place

            // Create the member
            $member = Member::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'image' => $data['image'] ?? null,
            ]);

            // Commit the transaction
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '✅ Member created successfully.',
                'data' => [
                    'member' => [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'image' => $member->image,
                        'user_id' => $member->user_id,
                    ],
                ]
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
            Log::error('Failed to create member: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create member.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified member.
     */
    public function show($id)
    {
        try {
            $member = Member::find($id);

            if (!$member) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Member not found.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => '🎉 Member retrieved successfully.',
                'data' => [
                    'member' => $member,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve member.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified member.
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the member
            $member = Member::find($id);

            if (!$member) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Member not found.'
                ], 404);
            }

            // Find the associated user
            $user = \App\Models\User::find($member->user_id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Associated user not found.'
                ], 404);
            }

            // Validate request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Prepare data
            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($member->image && Storage::disk('public')->exists($member->image)) {
                    Storage::disk('public')->delete($member->image);
                }

                $imagePath = $request->file('image')->store('images/members', 'public');
                $data['image'] = $imagePath;
            }

            // Update member details
            $member->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'image' => $data['image'] ?? $member->image,
            ]);

            // Update associated user details
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            // If password is provided, update the password
            if (!empty($data['password'])) {
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => '🔄 Member and associated user updated successfully.',
                'data' => [
                    'member' => $member,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update member and user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified member.
     */
    public function destroy($id)
    {
        try {
            // Find the member
            $member = Member::find($id);

            if (!$member) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Member not found.'
                ], 404);
            }

            // Find the associated user
            $user = \App\Models\User::find($member->user_id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Associated user not found.'
                ], 404);
            }

            // Delete member image if it exists
            if ($member->image && Storage::disk('public')->exists($member->image)) {
                Storage::disk('public')->delete($member->image);
            }

            // Delete the member
            $member->delete();

            // Delete the associated user
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => '🗑️ Member and associated user deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete member and user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
