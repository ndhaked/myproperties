<div id="chatbot-widget" class="chatbot-widget">
    <!-- Chat Button -->
    <div id="chatbot-button" class="chatbot-button">
        <i class="fa fa-comments"></i>
    </div>

    <!-- Chat Window -->
    <div id="chatbot-window" class="chatbot-window" style="display: none;">
        <div class="chatbot-header">
            <div class="chatbot-header-left">
                <div class="chatbot-avatar">
                    <img src="/images/dpsidebar.jpg" alt="Nirbhay" class="avatar-img">
                    <span class="avatar-online-dot"></span>
                </div>
                <div class="chatbot-title">
                    <div class="main-title">Nirbhay's Assistant</div>
                    <div class="sub-title">
                        <span class="pulse-dot"></span> Online — Ask me anything
                    </div>
                </div>
            </div>
            <div id="chatbot-close" class="chatbot-close">
                <i class="fa fa-close"></i>
            </div>
        </div>
        
        <div id="chatbot-messages" class="chatbot-messages">
            <div class="chatbot-message bot">
                <div class="bot-avatar-mini">
                    <img src="/images/dpsidebar.jpg" alt="Bot">
                </div>
                <div class="message-content">Hello! 👋 I'm Nirbhay's AI assistant. How can I help you regarding his **Laravel expertise** or **projects** today?</div>
            </div>
            <div class="quick-replies" id="initial-quick-replies">
                <button class="quick-reply-btn" data-text="About Nirbhay">👨‍💻 About Nirbhay</button>
                <button class="quick-reply-btn" data-text="View Experience">💼 Experience</button>
                <button class="quick-reply-btn" data-text="View Skills">🛠️ Skills</button>
                <button class="quick-reply-btn" data-text="Download Resume">📄 Resume</button>
            </div>
        </div>

        <div class="chatbot-input-area">
            <div class="chatbot-input-wrapper">
                <input type="text" id="chatbot-input" placeholder="Type your message..." autocomplete="off">
            </div>
            <button id="chatbot-send">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
        <div class="chatbot-powered-by">
            ⚡ Powered by <strong>Nirbhay Dhaked</strong> • Laravel Expert
        </div>
    </div>
</div>

<!-- Google Fonts for Premium UI -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    .chatbot-widget {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 10000;
        font-family: 'Outfit', sans-serif;
    }

    .chatbot-button {
        width: 65px;
        height: 65px;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid rgba(255, 255, 255, 0.2);
        animation: gentlePulse 3s ease-in-out infinite;
    }

    @keyframes gentlePulse {
        0%, 100% { box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4); }
        50% { box-shadow: 0 10px 35px rgba(99, 102, 241, 0.6); }
    }

    .chatbot-button:hover {
        transform: scale(1.1) rotate(10deg);
        box-shadow: 0 15px 30px rgba(99, 102, 241, 0.6);
        animation: none;
    }

    .chatbot-window {
        position: absolute;
        bottom: 85px;
        right: 0;
        width: 390px;
        height: 580px;
        max-height: calc(100vh - 120px);
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transform-origin: bottom right;
        transition: all 0.4s ease;
    }

    /* Header */
    .chatbot-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
        padding: 14px 18px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .chatbot-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chatbot-avatar {
        position: relative;
        width: 42px;
        height: 42px;
    }

    .avatar-img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.4);
    }

    .avatar-online-dot {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 12px;
        height: 12px;
        background: #22c55e;
        border-radius: 50%;
        border: 2px solid #6366f1;
        animation: onlinePulse 2s ease-in-out infinite;
    }

    @keyframes onlinePulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
        50% { box-shadow: 0 0 0 4px rgba(34, 197, 94, 0); }
    }

    .chatbot-title {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .chatbot-title .main-title {
        font-weight: 700;
        font-size: 16px;
        letter-spacing: -0.01em;
    }

    .chatbot-title .sub-title {
        font-size: 11.5px;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .pulse-dot {
        width: 6px;
        height: 6px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
    }

    .chatbot-close {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
        font-size: 14px;
    }

    .chatbot-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Messages Area */
    .chatbot-messages {
        flex: 1;
        padding: 20px 16px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background: linear-gradient(180deg, #f8f9ff 0%, #fdfdff 100%);
        scroll-behavior: smooth;
    }

    .chatbot-messages::-webkit-scrollbar {
        width: 4px;
    }
    .chatbot-messages::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .chatbot-message {
        max-width: 88%;
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 13.5px;
        line-height: 1.65;
        position: relative;
        animation: slideUp 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        word-wrap: break-word;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(15px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .chatbot-message.bot {
        align-self: flex-start;
        background: white;
        color: #1e293b;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #f1f5f9;
        display: flex;
        gap: 10px;
        max-width: 92%;
    }

    .bot-avatar-mini {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        margin-top: 2px;
    }

    .bot-avatar-mini img {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid #e2e8f0;
    }

    .chatbot-message.bot .message-content {
        flex: 1;
    }

    .chatbot-message.user {
        align-self: flex-end;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border-bottom-right-radius: 4px;
        box-shadow: 0 6px 15px -3px rgba(99, 102, 241, 0.3);
    }

    /* Quick Reply Chips */
    .quick-replies {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 4px 0 4px 38px;
        animation: slideUp 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .quick-reply-btn {
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px;
        padding: 7px 14px;
        font-size: 12px;
        font-family: 'Outfit', sans-serif;
        font-weight: 500;
        color: #6366f1;
        cursor: pointer;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .quick-reply-btn:hover {
        background: #6366f1;
        color: white;
        border-color: #6366f1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
    }

    /* Links */
    .chat-link {
        display: inline-block;
        margin-top: 8px;
        padding: 8px 16px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white !important;
        border-radius: 12px;
        text-decoration: none !important;
        font-weight: 600;
        font-size: 12.5px;
        transition: all 0.2s;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
    }

    .chat-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(99, 102, 241, 0.35);
    }

    .chatbot-message b, .chatbot-message strong {
        font-weight: 700;
        color: inherit;
    }

    /* Images */
    .chat-image-wrapper {
        margin-top: 12px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: transform 0.3s;
    }

    .chat-image-wrapper:hover {
        transform: scale(1.02);
    }

    .chat-inline-image {
        width: 100%;
        display: block;
        cursor: pointer;
    }

    .image-caption {
        padding: 6px;
        font-size: 10.5px;
        text-align: center;
        color: #64748b;
        background: white;
        border-top: 1px solid #f1f5f9;
    }

    /* Interactive Timeline */
    .chat-timeline {
        margin: 12px 0;
        padding-left: 14px;
        border-left: 2px dashed #e2e8f0;
        position: relative;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 16px;
        padding-left: 18px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -21px;
        top: 4px;
        width: 10px;
        height: 10px;
        background: #6366f1;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
    }

    .timeline-content {
        background: #f8fafc;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 12px;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .timeline-content:hover {
        background: white;
        transform: translateX(4px);
        box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.05);
        border-color: #6366f1;
    }

    .timeline-content strong {
        color: #1e293b;
        display: block;
        margin-bottom: 1px;
        font-size: 12px;
    }

    .timeline-content small {
        color: #64748b;
        font-weight: 500;
        font-size: 10.5px;
    }

    /* Input Area */
    .chatbot-input-area {
        padding: 14px 16px;
        background: white;
        border-top: 1px solid #f1f5f9;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .chatbot-input-wrapper {
        flex: 1;
        position: relative;
    }

    #chatbot-input {
        width: 100%;
        border: 1.5px solid #e2e8f0;
        padding: 11px 16px;
        border-radius: 25px;
        outline: none;
        font-size: 13.5px;
        font-family: 'Outfit', sans-serif;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    #chatbot-input:focus {
        border-color: #6366f1;
        background: white;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
    }

    #chatbot-send {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        flex-shrink: 0;
    }

    #chatbot-send:hover {
        transform: scale(1.1) rotate(-5deg);
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.4);
    }

    #chatbot-send i {
        font-size: 16px;
        margin-left: 1px;
    }

    /* Powered By Footer */
    .chatbot-powered-by {
        padding: 6px 16px;
        background: #f8fafc;
        text-align: center;
        font-size: 10px;
        color: #94a3b8;
        border-top: 1px solid #f1f5f9;
    }

    .chatbot-powered-by strong {
        color: #6366f1;
    }

    /* Mobile Responsiveness */
    @media (max-width: 480px) {
        .chatbot-widget {
            bottom: 20px;
            right: 20px;
        }
        .chatbot-window {
            width: calc(100vw - 40px);
            height: calc(100vh - 100px);
            bottom: 75px;
        }
        .chatbot-button {
            width: 55px;
            height: 55px;
            font-size: 24px;
        }
    }

    /* Typing indicator */
    .typing {
        display: flex;
        gap: 4px;
        padding: 4px 0;
    }

    .typing span {
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        animation: typingBounce 1.4s infinite ease-in-out both;
    }

    .typing span:nth-child(1) { animation-delay: -0.32s; }
    .typing span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typingBounce {
        0%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-6px); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const botBtn = document.getElementById('chatbot-button');
        const botWin = document.getElementById('chatbot-window');
        const botClose = document.getElementById('chatbot-close');
        const botInput = document.getElementById('chatbot-input');
        const botSend = document.getElementById('chatbot-send');
        const botMessages = document.getElementById('chatbot-messages');

        // Markdown-like formatter
        function formatText(text) {
            if (!text) return '';
            
            let formatted = text.trim();
            
            // Handle Bold (**text**)
            formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            
            // Handle Italic (*text* but not **text**)
            formatted = formatted.replace(/(?<!\*)\*([^*]+)\*(?!\*)/g, '<em>$1</em>');
            
            // Handle Images (![text](url))
            formatted = formatted.replace(/!\[([^\]]*)\]\(([^)]+)\)/g, function(match, alt, url) {
                return '<div class="chat-image-wrapper"><a href="' + url.trim() + '" download><img src="' + url.trim() + '" alt="' + alt + '" class="chat-inline-image"></a><div class="image-caption">📥 Click image to download</div></div>';
            });
            
            // Handle Links ([text](url))
            formatted = formatted.replace(/\[([^\]]+)\]\(([^)]+)\)/g, function(match, label, url) {
                return '<a href="' + url.trim() + '" target="_blank" class="chat-link">' + label.trim() + '</a>';
            });
            
            // Handle lists (- item)
            formatted = formatted.replace(/^\s*-\s+(.*)$/gm, '• $1');
            
            // Handle newlines
            formatted = formatted.replace(/\n/g, '<br>');
            
            return formatted;
        }

        // Quick reply click handler - use event delegation
        botMessages.addEventListener('click', function(e) {
            if (e.target.classList.contains('quick-reply-btn')) {
                const text = e.target.getAttribute('data-text');
                botInput.value = text;
                sendMessage();
                
                // Remove the clicked quick replies container
                const quickRepliesContainer = e.target.closest('.quick-replies');
                if (quickRepliesContainer) {
                    quickRepliesContainer.remove();
                }
            }
        });

        // Toggle Chat Window
        botBtn.addEventListener('click', () => {
            if (botWin.style.display === 'none') {
                botWin.style.display = 'flex';
                if (typeof gsap !== 'undefined') {
                    gsap.fromTo(botWin, 
                        { scale: 0.5, opacity: 0, y: 50, x: 20 }, 
                        { scale: 1, opacity: 1, y: 0, x: 0, duration: 0.5, ease: "expo.out" }
                    );
                }
                botInput.focus();
            } else {
                closeChat();
            }
        });

        botClose.addEventListener('click', closeChat);

        function closeChat() {
            if (typeof gsap !== 'undefined') {
                gsap.to(botWin, { 
                    scale: 0.8, 
                    opacity: 0, 
                    y: 30,
                    duration: 0.3, 
                    ease: "power2.in",
                    onComplete: () => botWin.style.display = 'none'
                });
            } else {
                botWin.style.display = 'none';
            }
        }

        // Send Message
        function sendMessage() {
            const message = botInput.value.trim();
            if (!message) return;

            // Remove any existing quick replies before sending
            const existingChips = botMessages.querySelectorAll('.quick-replies');
            existingChips.forEach(el => el.remove());

            addMessage(message, 'user');
            botInput.value = '';

            const typingId = showTyping();

            fetch('{{ route("chatbot.message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                removeTyping(typingId);
                addMessage(data.message, 'bot');
                
                // Add quick reply chips if available
                if (data.quick_replies && data.quick_replies.length > 0) {
                    addQuickReplies(data.quick_replies);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                removeTyping(typingId);
                addMessage('I encountered a slight technical glitch. Let\'s try that again! 🔄', 'bot');
            });
        }

        botSend.addEventListener('click', sendMessage);
        botInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        function addMessage(text, side) {
            const msgDiv = document.createElement('div');
            msgDiv.className = `chatbot-message ${side}`;
            
            if (side === 'bot') {
                msgDiv.innerHTML = `<div class="bot-avatar-mini"><img src="/images/dpsidebar.jpg" alt="Bot"></div><div class="message-content">${formatText(text)}</div>`;
            } else {
                msgDiv.innerHTML = `<div class="message-content">${text}</div>`;
            }
            
            botMessages.appendChild(msgDiv);
            
            // Auto scroll
            botMessages.scrollTo({
                top: botMessages.scrollHeight,
                behavior: 'smooth'
            });
        }

        function addQuickReplies(replies) {
            const emojiMap = {
                'View Experience': '💼',
                'Full Experience': '💼',
                'View Skills': '🛠️',
                'View Projects': '🚀',
                'View All Projects': '🚀',
                'Contact Nirbhay': '📱',
                'Contact Info': '📱',
                'Download Resume': '📄',
                'Download CV': '📄',
                'Book Meeting': '📅',
                'View Services': '🏆',
                'About Nirbhay': '👨‍💻',
            };

            const chipsDiv = document.createElement('div');
            chipsDiv.className = 'quick-replies';
            
            replies.forEach(reply => {
                const btn = document.createElement('button');
                btn.className = 'quick-reply-btn';
                btn.setAttribute('data-text', reply);
                const emoji = emojiMap[reply] || '💡';
                btn.textContent = `${emoji} ${reply}`;
                chipsDiv.appendChild(btn);
            });
            
            botMessages.appendChild(chipsDiv);
            
            botMessages.scrollTo({
                top: botMessages.scrollHeight,
                behavior: 'smooth'
            });
        }

        function showTyping() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'chatbot-message bot typing-indicator';
            typingDiv.innerHTML = '<div class="bot-avatar-mini"><img src="/images/dpsidebar.jpg" alt="Nirbhay"></div><div class="typing"><span></span><span></span><span></span></div>';
            botMessages.appendChild(typingDiv);
            botMessages.scrollTop = botMessages.scrollHeight;
            return typingDiv;
        }

        function removeTyping(el) {
            if (el && el.parentNode) {
                el.parentNode.removeChild(el);
            }
        }

        // Initialize welcome message with formatting
        const firstMsg = botMessages.querySelector('.chatbot-message.bot .message-content');
        if (firstMsg) {
            firstMsg.innerHTML = formatText(firstMsg.innerText);
        }
    });
</script>
