<div id="msg-{{ $msg['id'] }}" class="flex gap-4 {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }} animate-in slide-in-from-bottom-2 duration-300">
    @if($msg['role'] === 'assistant')
        <div class="w-8 h-8 bg-gradient-to-br from-[#D4A017] to-[#B8860B] rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
    @endif

    <div class="max-w-2xl {{ $msg['role'] === 'user' ? 'bg-[#D4A017] text-white rounded-2xl rounded-tr-md px-5 py-3 shadow-md' : 'bg-white border border-gray-200 rounded-2xl rounded-tl-md px-5 py-4 shadow-sm' }}">
        <div class="text-sm space-y-1">
            {!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', e($msg['content'])) !!}
        </div>
    </div>

    @if($msg['role'] === 'user')
        <div class="w-8 h-8 bg-gray-300 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm text-gray-600 font-bold text-xs uppercase">
            Me
        </div>
    @endif
</div>