@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-64px)] -my-6 md:-my-8 flex flex-col bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
    <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-[#D4A017] to-[#B8860B] rounded-xl flex items-center justify-center shadow-md">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900 leading-none">BakeryMate AI Assistant</h1>
                <p class="text-xs text-gray-600 mt-1">Ask me anything about your bakery data</p>
            </div>
        </div>
        <button onclick="clearChat()" class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-red-600 transition-colors border px-4 py-2 rounded-lg bg-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Clear Chat
        </button>
    </div>

    <div class="flex-1 overflow-y-auto px-6 py-6" id="chat-container">
        <div class="max-w-4xl mx-auto space-y-6" id="messages-wrapper">
            
            <div id="quick-prompts-card" class="bg-white border border-blue-100 rounded-2xl p-6 shadow-sm bg-gradient-to-br from-blue-50/50 to-white">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-bold text-blue-900 text-sm mb-3">💡 Try these example questions:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($quickPrompts as $prompt)
                                <button onclick="usePrompt('{{ $prompt }}')" class="px-3 py-1.5 bg-white border border-blue-200 rounded-lg text-xs font-medium text-blue-700 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    {{ $prompt }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @foreach($initialMessages as $msg)
                @include('aiAssistant.partials.message', ['msg' => $msg])
            @endforeach
        </div>
        <div id="anchor"></div>
    </div>

    <div class="bg-white border-t border-gray-200 px-6 py-6 flex-shrink-0">
        <div class="max-w-4xl mx-auto">
            <div class="flex gap-3 items-end">
                <div class="flex-1 relative">
                    <textarea 
                        id="chat-input"
                        rows="1"
                        oninput="autoResize(this)"
                        onkeydown="checkSubmit(event)"
                        placeholder="Ask me anything about your bakery... (Press Enter to send)"
                        class="w-full resize-none border-gray-200 rounded-xl focus:ring-[#D4A017] focus:border-[#D4A017] min-h-[52px] max-h-[200px] py-3.5 px-4 shadow-sm"
                    ></textarea>
                </div>
                <button id="send-btn" onclick="sendMessage()" class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-8 h-[52px] rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all disabled:opacity-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Send
                </button>
            </div>
            <p class="text-[10px] text-gray-400 mt-3 text-center uppercase font-black tracking-widest">
                AI Assistant can answer questions about sales, inventory, products, and finance.
            </p>
        </div>
    </div>
</div>

<script>
    const chatWrapper = document.getElementById('messages-wrapper');
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-btn');

    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }

    function checkSubmit(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    function usePrompt(text) {
        chatInput.value = text;
        sendMessage();
    }

    function sendMessage() {
        const text = chatInput.value.trim();
        if (!text) return;

        // Add User Message
        appendMessage('user', text);
        chatInput.value = '';
        chatInput.style.height = '52px';
        
        // Hide quick prompts
        document.getElementById('quick-prompts-card').classList.add('hidden');

        // Show Thinking
        const loadingId = Date.now();
        appendMessage('assistant', 'Thinking...', true, loadingId);
        
        // Scroll to bottom
        scrollToBottom();

        // Simulate AI Response (You would replace this with an AJAX call to your backend)
        setTimeout(() => {
            document.getElementById(`msg-${loadingId}`).remove();
            appendMessage('assistant', "Based on your records, you sold **Rs. 45,200.00** today. Your best performing category was **Sourdough Bread**.");
            scrollToBottom();
        }, 1500);
    }

    function appendMessage(role, content, isLoading = false, id = Date.now()) {
        const formattedContent = content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        const isUser = role === 'user';
        
        const html = `
            <div id="msg-${id}" class="flex gap-4 ${isUser ? 'justify-end' : 'justify-start'} animate-in slide-in-from-bottom-2 duration-300">
                ${!isUser ? `<div class="w-8 h-8 bg-gradient-to-br from-[#D4A017] to-[#B8860B] rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>` : ''}
                <div class="max-w-2xl ${isUser ? 'bg-[#D4A017] text-white rounded-2xl rounded-tr-md px-5 py-3 shadow-md' : 'bg-white border border-gray-200 rounded-2xl rounded-tl-md px-5 py-4 shadow-sm'}">
                    <div class="text-sm space-y-1 ${isLoading ? 'flex items-center gap-2 text-gray-400 italic' : ''}">
                        ${isLoading ? '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>' : ''}
                        ${formattedContent}
                    </div>
                </div>
                ${isUser ? `<div class="w-8 h-8 bg-gray-300 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm text-gray-600 font-bold text-xs uppercase">Me</div>` : ''}
            </div>
        `;
        chatWrapper.insertAdjacentHTML('beforeend', html);
    }

    function scrollToBottom() {
        document.getElementById('anchor').scrollIntoView({ behavior: 'smooth' });
    }

    function clearChat() {
        if(Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        })) {
        }
    }
</script>
@endsection