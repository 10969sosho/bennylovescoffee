<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display chat interface for client (chat with admin only)
     */
    public function index()
    {
        // Get admin user (role = 'superadmin')
        $admin = User::where('role', 'superadmin')->first();
        
        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not available at the moment.');
        }
        
        // Get all chat messages between current user and admin
        $messages = Chat::where('user_id', Auth::id())
            ->where('admin_id', $admin->id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark all admin messages as read
        Chat::where('user_id', Auth::id())
            ->where('admin_id', $admin->id)
            ->where('sender_type', 'admin')
            ->update(['is_read' => true]);
        
        return view('messages.index', compact('messages', 'admin'));
    }

    /**
     * Send message to admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Get admin user
        $admin = User::where('role', 'superadmin')->first();
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not available'], 404);
        }

        $message = Chat::create([
            'user_id' => Auth::id(),
            'admin_id' => $admin->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'customer',
            'message' => $request->message,
            'is_read' => false
        ]);

        $message->load('sender');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('messages.index')->with('success', 'Message sent!');
    }

    /**
     * Fetch new messages (for AJAX polling)
     */
    public function fetch(Request $request)
    {
        $admin = User::where('role', 'superadmin')->first();
        
        if (!$admin) {
            return response()->json(['messages' => []]);
        }

        $lastMessageId = $request->input('last_message_id', 0);
        
        $messages = Chat::where('user_id', Auth::id())
            ->where('admin_id', $admin->id)
            ->where('id', '>', $lastMessageId)
            ->with('sender')
            ->get();

        // Mark new admin messages as read
        Chat::where('user_id', Auth::id())
            ->where('admin_id', $admin->id)
            ->where('id', '>', $lastMessageId)
            ->where('sender_type', 'admin')
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }

    /**
     * Get unread message count for navbar badge
     */
    public function unreadCount()
    {
        $admin = User::where('role', 'superadmin')->first();
        
        if (!$admin) {
            return response()->json(['count' => 0]);
        }

        $count = Chat::where('user_id', Auth::id())
            ->where('admin_id', $admin->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}