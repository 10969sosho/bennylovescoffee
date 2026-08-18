@extends('admin.layouts.admin')

@section('content')
<style>
.chat-index-header {
    margin-bottom: 24px;
}

.chat-index-title {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

.chat-index-subtitle {
    color: #6b7280;
    font-size: 14px;
}

.conversations-list {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 2px solid #e5e7eb;
    overflow: hidden;
    padding: 24px;
}

.conversation-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.conversation-item:last-child {
    border-bottom: none;
}

.conversation-item:hover {
    background: #f9fafb;
}

.conversation-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #059669);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

.conversation-content {
    flex: 1;
    min-width: 0;
}

.conversation-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 4px;
}

.conversation-name {
    color: #1f2937;
    font-size: 16px;
    font-weight: 600;
}

.conversation-time {
    color: #6b7280;
    font-size: 12px;
}

.conversation-preview {
    color: #6b7280;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-meta {
    display: flex;
    align-items: center;
    gap: 8px;
}

.unread-badge {
    background: #ef4444;
    color: white;
    border-radius: 12px;
    padding: 2px 10px;
    font-size: 12px;
    font-weight: 700;
}

.empty-conversations {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}

.empty-conversations i {
    font-size: 64px;
    color: #10b981;
    margin-bottom: 16px;
}

.empty-conversations h3 {
    color: #1f2937;
    font-size: 20px;
    margin-bottom: 8px;
}

.btn-view-clients {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 16px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    border-radius: 8px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-view-clients:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}
</style>

<div class="chat-index-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="chat-index-title">
                <i class="fas fa-comments"></i>
                Chat Messages
            </h1>
            <p class="chat-index-subtitle">Active conversations with customers</p>
        </div>
        <a href="{{ route('admin.clients.index') }}" class="btn-view-clients">
            <i class="fas fa-users"></i>
            View All Clients
        </a>
    </div>
</div>

<div class="conversations-list">
    {{-- Debug: {{ $users->count() }} users found --}}
    @forelse($users as $user)
        <a href="{{ route('admin.chat.show', $user) }}" class="conversation-item">
            <div class="conversation-avatar">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="conversation-content">
                <div class="conversation-header">
                    <span class="conversation-name">{{ $user->name }}</span>
                    <span class="conversation-time">
                        {{ $user->last_message ? $user->last_message->created_at->diffForHumans() : 'No messages' }}
                    </span>
                </div>
                <div class="conversation-preview">
                    {{ $user->last_message ? \Str::limit($user->last_message->message, 60) : 'No messages yet' }}
                </div>
            </div>
            <div class="conversation-meta">
                @if($user->unread_count > 0)
                    <span class="unread-badge">{{ $user->unread_count }}</span>
                @endif
                <i class="fas fa-chevron-right" style="color: #9ca3af;"></i>
            </div>
        </a>
    @empty
        <div class="empty-conversations">
            <i class="fas fa-inbox"></i>
            <h3>No Conversations Yet</h3>
            <p>Start chatting with your customers</p>
            <a href="{{ route('admin.clients.index') }}" class="btn-view-clients">
                <i class="fas fa-users"></i>
                View All Clients
            </a>
        </div>
    @endforelse
</div>
@endsection
