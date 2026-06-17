@extends('layouts.app')

@section('title', 'Guide Videos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Support & Training Guide Videos</h1>
            <p class="text-sm text-gray-500">Learn how to perform operations efficiently using our step-by-step videos.</p>
        </div>
        <a href="{{ route('agent-panel.dashboard') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
            ← Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($videos as $video)
            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                @if($video->thumbnail_url)
                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900">{{ $video->title }}</h3>
                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $video->description }}</p>
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <a href="{{ $video->video_url }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            Play Video
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-gray-100 rounded-xl p-12 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <p class="mt-4 font-bold text-gray-700">No Guide Videos Available</p>
                <p class="text-xs mt-1">Please check back later for helpful tutorial videos.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
