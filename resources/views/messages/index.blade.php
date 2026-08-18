@extends('layouts.app')

@section('content')
<style>
/* Chat Container */
.chat-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

.chat-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    height: calc(100vh - 200px);
    min-height: 600px;
    display: flex;
    flex-direction: column;
}

/* Chat Header */
.chat-header {
    background: linear-gradient(135deg, #000000, #1a1a1a);
    color: white;
    padding: 24px 28px;
    border-bottom: 3px solid #d4af37;
}

.chat-header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.chat-title {
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.chat-title i {
    color: #d4af37;
}

.admin-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #d4af37;
}

.status-dot {
    width: 10px;
    height: 10px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.chat-subtitle {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.8);
}

/* Messages Area */
.messages-area {
    flex: 1;
    overflow-y: auto;
    padding: 28px;
    background: #f9fafb;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.message-wrapper {
    display: flex;
    gap: 12px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-wrapper.sent {
    flex-direction: row-reverse;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #d4af37, #f0d872);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000;
    font-weight: 700;
    font-size: 16px;
    flex-shrink: 0;
}

.message-wrapper.sent .message-avatar {
    background: linear-gradient(135deg, #000000, #1a1a1a);
    color: #d4af37;
}

.message-content {
    max-width: 60%;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.message-bubble {
    background: white;
    padding: 12px 16px;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    word-wrap: break-word;
    line-height: 1.5;
}

.message-wrapper.sent .message-bubble {
    background: linear-gradient(135deg, #000000, #1a1a1a);
    color: white;
}

.message-time {
    font-size: 11px;
    color: #6b7280;
    padding: 0 8px;
}

.message-wrapper.sent .message-time {
    text-align: right;
}

/* Empty State */
.empty-messages {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #6b7280;
    text-align: center;
}

.empty-messages i {
    font-size: 64px;
    color: #d4af37;
    margin-bottom: 16px;
}

.empty-messages h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #1f2937;
}

/* Input Area */
.input-area {
    padding: 20px 28px;
    background: white;
    border-top: 1px solid #e5e7eb;
}

.input-form {
    display: flex;
    gap: 12px;
}

.message-input {
    flex: 1;
    padding: 14px 20px;
    border: 2px solid #e5e7eb;
    border-radius: 24px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.message-input:focus {
    outline: none;
    border-color: #d4af37;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.send-btn {
    padding: 14px 28px;
    background: linear-gradient(135deg, #000000, #1a1a1a);
    color: #d4af37;
    border: 2px solid #d4af37;
    border-radius: 24px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.send-btn:hover {
    background: linear-gradient(135deg, #1a1a1a, #000000);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}

.send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Success Alert */
.success-alert {
    position: fixed;
    top: 100px;
    right: 20px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 16px 24px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 9999;
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .chat-container {
        margin: 20px auto;
        padding: 0 10px;
    }
    
    .chat-card {
        height: calc(100vh - 150px);
        border-radius: 12px;
    }
    
    .chat-header {
        padding: 20px;
    }
    
    .chat-title {
        font-size: 20px;
    }
    
    .messages-area {
        padding: 20px;
    }
    
    .message-content {
        max-width: 75%;
    }
    
    .input-area {
        padding: 16px;
    }
    
    .send-btn {
        padding: 14px 20px;
    }
    
    .send-btn span {
        display: none;
    }
}
</style>

<div class="chat-container">
    @if(session('success'))
    <div class="success-alert">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="chat-card">
        <!-- Chat Header -->
        <div class="chat-header">
            <div class="chat-header-top">
                <div class="chat-title">
                    <i class="fas fa-headset"></i>
                    Customer Support
                </div>
                <div class="admin-status">
                    <span class="status-dot"></span>
                    Online
                </div>
            </div>
            <div class="chat-subtitle">
                Chat with our admin team for assistance
            </div>
        </div>

        <!-- Messages Area -->
        <div class="messages-area" id="messagesArea">
            @forelse($messages as $message)
                <div class="message-wrapper {{ $message->sender_id == Auth::id() ? 'sent' : 'received' }}">
                    <div class="message-avatar">
                        {{ substr($message->sender->name, 0, 1) }}
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            {{ $message->message }}
                        </div>
                        <div class="message-time">
                            {{ $message->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-messages">
                    <i class="fas fa-comments"></i>
                    <h3>Start a Conversation</h3>
                    <p>Send a message to our support team</p>
                </div>
            @endforelse
        </div>

        <!-- Input Area -->
        <div class="input-area">
            <form class="input-form" id="messageForm">
                @csrf
                <input 
                    type="text" 
                    name="message" 
                    id="messageInput"
                    class="message-input" 
                    placeholder="Type your message..."
                    required
                    autocomplete="off"
                >
                <button type="submit" class="send-btn" id="sendBtn">
                    <i class="fas fa-paper-plane"></i>
                    <span>Send</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const messagesArea = document.getElementById('messagesArea');
const messageForm = document.getElementById('messageForm');
const messageInput = document.getElementById('messageInput');
const sendBtn = document.getElementById('sendBtn');
let lastMessageId = {{ $messages->last()?->id ?? 0 }};

// Auto-scroll to bottom
function scrollToBottom() {
    messagesArea.scrollTop = messagesArea.scrollHeight;
}

// Initial scroll
scrollToBottom();

// Send message
messageForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const message = messageInput.value.trim();
    if (!message) return;
    
    sendBtn.disabled = true;
    
    try {
        const response = await fetch('{{ route('messages.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Add message to UI
            addMessageToUI(data.message, true);
            messageInput.value = '';
            lastMessageId = data.message.id;
        }
    } catch (error) {
        console.error('Error sending message:', error);
    } finally {
        sendBtn.disabled = false;
        messageInput.focus();
    }
});

// Add message to UI
function addMessageToUI(message, isSent) {
    // Remove empty state if exists
    const emptyState = messagesArea.querySelector('.empty-messages');
    if (emptyState) {
        emptyState.remove();
    }
    
    const messageWrapper = document.createElement('div');
    messageWrapper.className = `message-wrapper ${isSent ? 'sent' : 'received'}`;
    
    const avatar = document.createElement('div');
    avatar.className = 'message-avatar';
    avatar.textContent = message.sender.name.charAt(0);
    
    const content = document.createElement('div');
    content.className = 'message-content';
    
    const bubble = document.createElement('div');
    bubble.className = 'message-bubble';
    bubble.textContent = message.message;
    
    const time = document.createElement('div');
    time.className = 'message-time';
    const messageDate = new Date(message.created_at);
    time.textContent = messageDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
    
    content.appendChild(bubble);
    content.appendChild(time);
    messageWrapper.appendChild(avatar);
    messageWrapper.appendChild(content);
    
    messagesArea.appendChild(messageWrapper);
    scrollToBottom();
}

// Fetch new messages (polling every 3 seconds)
async function fetchNewMessages() {
    try {
        const response = await fetch(`{{ route('messages.fetch') }}?last_message_id=${lastMessageId}`);
        const data = await response.json();
        
        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(message => {
                addMessageToUI(message, message.sender_id == {{ Auth::id() }});
                lastMessageId = message.id;
            });
        }
    } catch (error) {
        console.error('Error fetching messages:', error);
    }
}

// Start polling
setInterval(fetchNewMessages, 3000);

// Auto-hide success alert
setTimeout(function() {
    const alert = document.querySelector('.success-alert');
    if (alert) {
        alert.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => alert.remove(), 300);
    }
}, 3000);
</script>
@endsection
