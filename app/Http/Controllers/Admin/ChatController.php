<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        // Get users who have chat conversations
        $users = User::where('role', 'client')
            ->whereHas('chats')
            ->withCount(['chats as unread_count' => function($query) {
                $query->where('is_read', false)
                      ->where('sender_type', 'customer');
            }])
            ->with(['chats' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get();

        return view('admin.chat.index', compact('users'));
    }

    public function show(User $user)
    {
        // Get chat messages between admin and specific user
        $chats = Chat::where('user_id', $user->id)
            ->where('admin_id', Auth::guard('admin')->id())
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Chat::where('user_id', $user->id)
            ->where('admin_id', Auth::guard('admin')->id())
            ->where('sender_type', 'customer')
            ->update(['is_read' => true]);

        return view('admin.chat.show', compact('user', 'chats'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        Chat::create([
            'user_id' => $user->id,
            'admin_id' => Auth::guard('admin')->id(),
            'sender_id' => Auth::guard('admin')->id(),
            'sender_type' => 'admin',
            'message' => $request->message,
            'is_read' => true
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}