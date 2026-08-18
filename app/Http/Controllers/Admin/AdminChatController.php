<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Note: Admin uses 'admin' guard

class AdminChatController extends Controller
{
    /**
     * Display chat interface for admin (all conversations)
     */
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
            ->get()
            ->sortByDesc(function($user) {
                return $user->chats->first() ? $user->chats->first()->created_at : null;
            });

        return view('admin.chat.index', compact('users'));
    }

    /**
     * Display chat with specific user
     */
    public function show(User $user)
    {
        $adminId = Auth::guard('admin')->id();
        
        // Get chat messages between admin and specific user
        $chats = Chat::where('user_id', $user->id)
            ->where('admin_id', $adminId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Chat::where('user_id', $user->id)
            ->where('admin_id', $adminId)
            ->where('sender_type', 'customer')
            ->update(['is_read' => true]);

        return view('admin.chat.show', compact('user', 'chats'));
    }

    /**
     * Send message to user
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $adminId = Auth::guard('admin')->id();

        Chat::create([
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'sender_id' => $adminId,
            'sender_type' => 'admin',
            'message' => $request->message,
            'is_read' => true
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    /**
     * Fetch new messages from specific user (for AJAX polling)
     */
    public function fetch(Request $request, User $user)
    {
        $adminId = Auth::guard('admin')->id();
        $lastMessageId = $request->input('last_message_id', 0);
        
        $chats = Chat::where('user_id', $user->id)
            ->where('admin_id', $adminId)
            ->where('id', '>', $lastMessageId)
            ->with('sender')
            ->get();

        // Mark new user messages as read
        Chat::where('user_id', $user->id)
            ->where('admin_id', $adminId)
            ->where('id', '>', $lastMessageId)
            ->where('sender_type', 'customer')
            ->update(['is_read' => true]);

        return response()->json(['messages' => $chats]);
    }

    /**
     * Display all clients/users
     */
    public function clients()
    {
        $adminId = Auth::guard('admin')->id();
        $clients = User::where('role', 'client')
            ->withCount(['chats as unread_count' => function($query) use ($adminId) {
                $query->where('admin_id', $adminId)
                      ->where('is_read', false)
                      ->where('sender_type', 'customer');
            }])
            ->latest()
            ->get();

        return view('admin.chat.clients', compact('clients'));
    }

    /**
     * Get total unread message count for admin badge
     */
    public function unreadCount()
    {
        $adminId = Auth::guard('admin')->id();
        $count = Chat::where('admin_id', $adminId)
            ->where('is_read', false)
            ->where('sender_type', 'customer')
            ->count();

        return response()->json(['count' => $count]);
    }
}