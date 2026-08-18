@extends('admin.layouts.admin')

@section('content')
<style>
.chat-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 2px solid #e5e7eb;
    overflow: hidden;
    padding: 24px;
    height: 70vh;
    display: flex;
    flex-direction: column;
}

.chat-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 0;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 20px;
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #059669);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 18px;
}

.user-info h3 {
    margin: 0;
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
}

.user-info p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.message {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    max-width: 80%;
}

.message.admin {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.message.customer {
    align-self: flex-start;
}

.message-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}

.message.admin .message-avatar {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.message.customer .message-avatar {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.message-content {
    background: #f8fafc;
    padding: 12px 16px;
    border-radius: 18px;
    border: 1px solid #e5e7eb;
    position: relative;
}

.message.admin .message-content {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border-color: #3b82f6;
}

.message.customer .message-content {
    background: #f8fafc;
    color: #1f2937;
}

.message-text {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
}

.message-time {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 4px;
}

.message.admin .message-time {
    color: rgba(255, 255, 255, 0.7);
}

.chat-input {
    display: flex;
    gap: 12px;
    padding: 20px 0;
    border-top: 2px solid #e5e7eb;
    margin-top: 20px;
}

.chat-input input {
    flex: 1;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.chat-input input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.send-btn {
    padding: 12px 24px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.send-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #f8fafc;
    color: #374151;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.back-btn:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
}

.empty-chat {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.empty-chat i {
    font-size: 48px;
    color: #10b981;
    margin-bottom: 16px;
}

.empty-chat h3 {
    color: #1f2937;
    font-size: 18px;
    margin-bottom: 8px;
}
</style>

<a href="{{ route('admin.chat.index') }}" class="back-btn">
    <i class="fas fa-arrow-left"></i>
    Back to Conversations
</a>

<div class="chat-container">
    <div class="chat-header">
        <div class="user-avatar">
            {{ substr($user->name, 0, 1) }}
        </div>
        <div class="user-info">
            <h3>{{ $user->name }}</h3>
            <p>{{ $user->email }}</p>
        </div>
    </div>

    <div class="chat-messages" id="chatMessages">
        @forelse($chats as $chat)
        <div class="message {{ $chat->sender_type }}">
            <div class="message-avatar">
                {{ substr($chat->sender->name, 0, 1) }}
            </div>
            <div class="message-content">
                <p class="message-text">{{ $chat->message }}</p>
                <div class="message-time">
                    {{ $chat->created_at->format('M d, Y H:i') }}
                </div>
            </div>
        </div>
        @empty
        <div class="empty-chat">
            <i class="fas fa-comments"></i>
            <h3>No messages yet</h3>
            <p>Start the conversation with {{ $user->name }}</p>
        </div>
        @endforelse
    </div>

    <form action="{{ route('admin.chat.store', $user) }}" method="POST" class="chat-input">
        @csrf
        <input type="text" name="message" placeholder="Type your message..." required>
        <button type="submit" class="send-btn">
            <i class="fas fa-paper-plane"></i>
            Send
        </button>
    </form>
</div>

<script>
let lastMessageId = {{ $chats->last() ? $chats->last()->id : 0 }};

// Auto scroll to bottom
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
});

// Auto scroll when new messages are added
const observer = new MutationObserver(function(mutations) {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
});

observer.observe(document.getElementById('chatMessages'), {
    childList: true,
    subtree: true
});

// Fetch new messages (polling every 3 seconds)
async function fetchNewMessages() {
    try {
        const response = await fetch(`{{ route('admin.chat.fetch', $user) }}?last_message_id=${lastMessageId}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.messages && data.messages.length > 0) {
            const chatMessages = document.getElementById('chatMessages');
            
            data.messages.forEach(message => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${message.sender_type}`;
                messageDiv.innerHTML = `
                    <div class="message-avatar">
                        ${message.sender.name.charAt(0)}
                    </div>
                    <div class="message-content">
                        <p class="message-text">${message.message}</p>
                        <div class="message-time">
                            ${new Date(message.created_at).toLocaleDateString('en-US', { 
                                month: 'short', 
                                day: 'numeric', 
                                year: 'numeric', 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            })}
                        </div>
                    </div>
                `;
                chatMessages.appendChild(messageDiv);
                lastMessageId = message.id;
            });
            
            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    } catch (error) {
        console.error('Error fetching messages:', error);
        // Don't show error notifications for chat polling to avoid spam
    }
}

// Start polling
setInterval(fetchNewMessages, 3000);
</script>
@endsection