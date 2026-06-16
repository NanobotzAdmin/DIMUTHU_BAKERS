@extends('layouts.app')
@section('title', 'Help & Support Videos')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Guide Videos</h2>
            <p class="text-sm text-gray-500 mt-1">Manage help & support guide videos for mobile app users</p>
        </div>
        <button onclick="openCreateModal()"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm font-medium text-sm">
            <i class="bi bi-plus-circle"></i>
            Add New Video
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <i class="bi bi-play-circle text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $videos->count() }}</p>
                    <p class="text-xs text-gray-500">Total Videos</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $videos->where('status', 1)->count() }}</p>
                    <p class="text-xs text-gray-500">Active</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="bi bi-x-circle text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $videos->where('status', 0)->count() }}</p>
                    <p class="text-xs text-gray-500">Inactive</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Videos Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="videos-table">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Video</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">User Roles</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="videos-tbody">
                    @forelse($videos as $index => $video)
                    <tr class="hover:bg-gray-50 transition-colors" id="video-row-{{ $video->id }}">
                        <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div onclick="openViewModal({{ json_encode($video->load('userRoles')) }})" class="w-16 h-10 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0 relative cursor-pointer group">
                                    @if($video->thumbnail_url)
                                        <img src="{{ $video->thumbnail_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    @else
                                        <i class="bi bi-play-btn text-gray-400 text-xl group-hover:text-gray-600 transition-colors"></i>
                                    @endif
                                    <div class="absolute inset-0 bg-black/35 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="bi bi-play-fill text-white text-lg"></i>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate max-w-[200px]">{{ $video->title }}</p>
                                    <button onclick="openViewModal({{ json_encode($video->load('userRoles')) }})" class="text-xs text-indigo-500 hover:text-indigo-700 text-left block max-w-[200px]">
                                        <i class="bi bi-play-circle-fill"></i> Play Video
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-600 text-sm truncate max-w-[250px]">{{ $video->description ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($video->userRoles as $role)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $role->user_role_name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $video->display_order }}</td>
                        <td class="px-6 py-4">
                            <button onclick="toggleVideoStatus({{ $video->id }})"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium cursor-pointer transition-colors
                                {{ $video->status == 1 ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}"
                                id="status-badge-{{ $video->id }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $video->status == 1 ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $video->status == 1 ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openViewModal({{ json_encode($video->load('userRoles')) }})"
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="View & Play">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button onclick="openEditModal({{ json_encode($video->load('userRoles')) }})"
                                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button onclick="deleteVideo({{ $video->id }}, '{{ addslashes($video->title) }}')"
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="bi bi-camera-video text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 font-medium">No guide videos yet</p>
                                <p class="text-gray-400 text-sm">Click "Add New Video" to create your first guide video</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create/Edit Modal --}}
<div id="videoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeModal()"></div>

        {{-- Modal Content --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Add New Video</h3>
                <button onclick="closeModal()" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="videoForm" class="p-6 space-y-5">
                <input type="hidden" name="id" id="video-id">

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="video-title"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors"
                        placeholder="Enter video title" required>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" id="video-description" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors resize-none"
                        placeholder="Enter video description (optional)"></textarea>
                </div>

                {{-- Video URL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Video URL <span class="text-red-500">*</span></label>
                    <input type="url" name="video_url" id="video-url"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors"
                        placeholder="https://www.youtube.com/watch?v=..." required>
                    <p class="text-xs text-gray-400 mt-1">YouTube, Vimeo, or any video URL</p>
                </div>

                {{-- Thumbnail URL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Thumbnail URL</label>
                    <input type="url" name="thumbnail_url" id="video-thumbnail"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors"
                        placeholder="https://img.youtube.com/vi/.../0.jpg (optional)">
                </div>

                {{-- Display Order --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Display Order</label>
                    <input type="number" name="display_order" id="video-order" min="0" value="0"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors"
                        placeholder="0">
                    <p class="text-xs text-gray-400 mt-1">Lower numbers appear first</p>
                </div>

                {{-- User Roles --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign to User Roles <span class="text-red-500">*</span></label>
                    <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                        @foreach($userRoles as $role)
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-white p-2 rounded-lg transition-colors">
                            <input type="checkbox" name="user_roles[]" value="{{ $role->id }}"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 role-checkbox">
                            <span class="text-sm text-gray-700">{{ $role->user_role_name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-red-500 mt-1 hidden" id="roles-error">Please select at least one user role</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submit-btn"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center gap-2">
                        <i class="bi bi-check-lg"></i>
                        <span id="submit-btn-text">Save Video</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- View Video Modal --}}
<div id="viewVideoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-black/60 transition-opacity" onclick="closeViewModal()"></div>

        {{-- Modal Content --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 truncate pr-6" id="view-modal-title">View Guide Video</h3>
                <button onclick="closeViewModal()" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-6">
                {{-- Video Player Container --}}
                <div id="video-player-container" class="w-full">
                    <!-- Dynamic Video Player Embedded Here -->
                </div>

                {{-- Video details --}}
                <div class="space-y-4">
                    <div>
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Title</h4>
                        <p class="text-base font-semibold text-gray-900 mt-0.5" id="view-title"></p>
                    </div>

                    <div>
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Description</h4>
                        <p class="text-sm text-gray-600 mt-0.5 whitespace-pre-line" id="view-description"></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Target User Roles</h4>
                            <div class="flex flex-wrap gap-1" id="view-roles">
                                <!-- Roles badges here -->
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Display Order</h4>
                                <p class="text-sm text-gray-950 mt-0.5" id="view-order"></p>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</h4>
                                <div class="mt-1" id="view-status-badge">
                                    <!-- Status badge -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                <a id="view-external-link" href="#" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center gap-1.5">
                    <i class="bi bi-box-arrow-up-right"></i> Open original link
                </a>
                <button type="button" onclick="closeViewModal()"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function getVideoPlayerHtml(url) {
        if (!url) return '';
        
        // YouTube Shorts support
        let shortsMatch = url.match(/(?:youtube\.com\/shorts\/|youtu\.be\/shorts\/)([a-zA-Z0-9_-]{11})/i);
        if (shortsMatch) {
            let videoId = shortsMatch[1];
            return `<iframe class="w-full aspect-video rounded-lg shadow-sm border border-gray-200" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        }

        // YouTube Standard
        let youtubeRegex = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        let youtubeMatch = url.match(youtubeRegex);
        if (youtubeMatch && youtubeMatch[2].length === 11) {
            let videoId = youtubeMatch[2];
            return `<iframe class="w-full aspect-video rounded-lg shadow-sm border border-gray-200" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        }
        
        // Vimeo
        let vimeoRegex = /vimeo\.com\/(?:video\/)?([0-9]+)/;
        let vimeoMatch = url.match(vimeoRegex);
        if (vimeoMatch) {
            let videoId = vimeoMatch[1];
            return `<iframe class="w-full aspect-video rounded-lg shadow-sm border border-gray-200" src="https://player.vimeo.com/video/${videoId}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
        }
        
        // Direct video link (mp4, webm, ogg, etc.)
        if (url.match(/\.(mp4|webm|ogg|mov|3gp)($|\?)/i)) {
            return `<video class="w-full aspect-video rounded-lg shadow-sm border border-gray-200" controls autoplay>
                <source src="${url}">
                Your browser does not support the video tag.
            </video>`;
        }
        
        // Fallback URL link
        return `
            <div class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-dashed border-gray-300 rounded-lg text-center">
                <i class="bi bi-link-45deg text-4xl text-gray-400 mb-2"></i>
                <p class="text-sm font-medium text-gray-700 mb-1">Direct playback not available</p>
                <p class="text-xs text-gray-500 mb-4">We couldn't automatically embed this link. You can open it in a new window.</p>
                <a href="${url}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm transition-colors font-medium">
                    <i class="bi bi-box-arrow-up-right"></i> Open Link
                </a>
            </div>
        `;
    }

    function openViewModal(video) {
        document.getElementById('view-modal-title').textContent = video.title || 'View Guide Video';
        document.getElementById('view-title').textContent = video.title;
        document.getElementById('view-description').textContent = video.description || 'No description provided.';
        document.getElementById('view-order').textContent = video.display_order ?? 0;
        document.getElementById('view-external-link').href = video.video_url;

        // Render video player
        const playerContainer = document.getElementById('video-player-container');
        playerContainer.innerHTML = getVideoPlayerHtml(video.video_url);

        // Render roles
        const rolesContainer = document.getElementById('view-roles');
        rolesContainer.innerHTML = '';
        if (video.user_roles && video.user_roles.length > 0) {
            video.user_roles.forEach(role => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700';
                badge.textContent = role.user_role_name;
                rolesContainer.appendChild(badge);
            });
        } else {
            rolesContainer.innerHTML = '<span class="text-xs text-gray-500">None</span>';
        }

        // Status badge
        const statusContainer = document.getElementById('view-status-badge');
        if (video.status == 1) {
            statusContainer.innerHTML = `
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                    Active
                </span>
            `;
        } else {
            statusContainer.innerHTML = `
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    Inactive
                </span>
            `;
        }

        document.getElementById('viewVideoModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewVideoModal').classList.add('hidden');
        // Clear video player to stop playback
        document.getElementById('video-player-container').innerHTML = '';
    }

    // Open Create Modal
    function openCreateModal() {
        document.getElementById('modal-title').textContent = 'Add New Video';
        document.getElementById('submit-btn-text').textContent = 'Save Video';
        document.getElementById('videoForm').reset();
        document.getElementById('video-id').value = '';
        document.getElementById('video-order').value = '0';
        document.querySelectorAll('.role-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('roles-error').classList.add('hidden');
        document.getElementById('videoModal').classList.remove('hidden');
    }

    // Open Edit Modal
    function openEditModal(video) {
        document.getElementById('modal-title').textContent = 'Edit Video';
        document.getElementById('submit-btn-text').textContent = 'Update Video';
        document.getElementById('video-id').value = video.id;
        document.getElementById('video-title').value = video.title;
        document.getElementById('video-description').value = video.description || '';
        document.getElementById('video-url').value = video.video_url;
        document.getElementById('video-thumbnail').value = video.thumbnail_url || '';
        document.getElementById('video-order').value = video.display_order || 0;
        document.getElementById('roles-error').classList.add('hidden');

        // Set role checkboxes
        const roleIds = video.user_roles.map(r => r.id);
        document.querySelectorAll('.role-checkbox').forEach(cb => {
            cb.checked = roleIds.includes(parseInt(cb.value));
        });

        document.getElementById('videoModal').classList.remove('hidden');
    }

    // Close Modal
    function closeModal() {
        document.getElementById('videoModal').classList.add('hidden');
    }

    // Form Submit (Create or Update)
    document.getElementById('videoForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const checkedRoles = document.querySelectorAll('.role-checkbox:checked');
        if (checkedRoles.length === 0) {
            document.getElementById('roles-error').classList.remove('hidden');
            return;
        }
        document.getElementById('roles-error').classList.add('hidden');

        const videoId = document.getElementById('video-id').value;
        const isUpdate = !!videoId;
        const url = isUpdate
            ? '{{ route("helpSupportVideos.update") }}'
            : '{{ route("helpSupportVideos.store") }}';

        const formData = {
            _token: CSRF_TOKEN,
            title: document.getElementById('video-title').value,
            description: document.getElementById('video-description').value,
            video_url: document.getElementById('video-url').value,
            thumbnail_url: document.getElementById('video-thumbnail').value,
            display_order: document.getElementById('video-order').value || 0,
            user_roles: Array.from(checkedRoles).map(cb => cb.value),
        };

        if (isUpdate) {
            formData.id = videoId;
        }

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Saving...';

        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
            success: function(response) {
                if (response.status) {
                    closeModal();
                    toastr.success(response.message);
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    toastr.error(response.message || 'Something went wrong');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    const firstError = Object.values(errors)[0][0];
                    toastr.error(firstError);
                } else {
                    toastr.error('An error occurred. Please try again.');
                }
            },
            complete: function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> <span id="submit-btn-text">' + (isUpdate ? 'Update Video' : 'Save Video') + '</span>';
            }
        });
    });

    // Toggle Status
    function toggleVideoStatus(id) {
        Swal.fire({
            title: 'Toggle Status',
            text: 'Are you sure you want to change this video\'s status?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, toggle it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("helpSupportVideos.toggleStatus") }}',
                    type: 'POST',
                    data: JSON.stringify({ id: id }),
                    contentType: 'application/json',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            setTimeout(() => window.location.reload(), 800);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Failed to toggle status.');
                    }
                });
            }
        });
    }

    // Delete Video
    function deleteVideo(id, title) {
        Swal.fire({
            title: 'Delete Video',
            html: `Are you sure you want to delete <strong>"${title}"</strong>?<br><small class="text-gray-500">This action cannot be undone.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("helpSupportVideos.delete") }}',
                    type: 'DELETE',
                    data: JSON.stringify({ id: id }),
                    contentType: 'application/json',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            setTimeout(() => window.location.reload(), 800);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Failed to delete video.');
                    }
                });
            }
        });
    }
</script>
@endsection
