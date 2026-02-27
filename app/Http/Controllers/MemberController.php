<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::all()->map(function ($member) {
            return [
                'id' => $member->id,
                'firstName' => $member->first_name,
                'lastName' => $member->last_name,
                'email' => $member->email,
                'role' => $member->role,
            ];
        });

        return response()->json($members);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'role' => 'required|string|in:Admin,Editor,Author,Viewer',
        ]);

        $member = Member::create([
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        return response()->json([
            'id' => $member->id,
            'firstName' => $member->first_name,
            'lastName' => $member->last_name,
            'email' => $member->email,
            'role' => $member->role,
        ], 201);
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'role' => 'required|string|in:Admin,Editor,Author,Viewer',
        ]);

        $member->update([
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        return response()->json([
            'id' => $member->id,
            'firstName' => $member->first_name,
            'lastName' => $member->last_name,
            'email' => $member->email,
            'role' => $member->role,
        ]);
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return response()->json(['message' => 'Member deleted successfully']);
    }
}

