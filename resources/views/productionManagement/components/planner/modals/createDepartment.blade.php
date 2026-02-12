<!-- Create Department Modal -->
<div id="modal-create-department" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
            onclick="$('#modal-create-department').addClass('hidden')"></div>

        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Create New Department</h3>
                <button onclick="$('#modal-create-department').addClass('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="form-create-department">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                        <input type="text" name="name" required placeholder="e.g., Pastry Station"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4A017] focus:border-[#D4A017]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color Theme</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'pink', 'indigo', 'gray'];
                            @endphp
                            @foreach($colors as $color)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $color }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                    <div
                                        class="w-8 h-8 rounded-full bg-{{ $color }}-500 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-{{ $color }}-600 hover:opacity-80 transition-opacity">
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                        <div class="grid grid-cols-4 gap-2">
                            @php
                                $icons = [
                                    'box' => 'bi-box',
                                    'fire' => 'bi-fire',
                                    'cake' => 'bi-cake',
                                    'brush' => 'bi-brush',
                                    'tools' => 'bi-tools',
                                    'people' => 'bi-people',
                                    'shop' => 'bi-shop',
                                    'truck' => 'bi-truck'
                                ];
                            @endphp
                            @foreach($icons as $value => $iconClass)
                                <label class="cursor-pointer">
                                    <input type="radio" name="icon" value="{{ $value }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                    <div
                                        class="flex flex-col items-center justify-center p-2 border rounded-md peer-checked:border-[#D4A017] peer-checked:bg-amber-50 hover:bg-gray-50 transition-colors">
                                        <i
                                            class="bi {{ $iconClass }} text-xl mb-1 text-gray-600 peer-checked:text-[#D4A017]"></i>
                                        <span class="text-[10px] text-gray-500 capitalize">{{ $value }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-[#D4A017] text-white px-4 py-2 rounded-md hover:bg-[#B8860B] transition-colors">
                        Create Department
                    </button>
                    <button type="button" onclick="$('#modal-create-department').addClass('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Handle form submission
        $('#form-create-department').on('submit', function (e) {
            e.preventDefault();

            const formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: $(this).find('[name="name"]').val(),
                color: $(this).find('[name="color"]:checked').val(),
                icon: $(this).find('[name="icon"]:checked').val()
            };

            // Show loading state
            const btn = $(this).find('button[type="submit"]');
            const originalText = btn.html();
            btn.prop('disabled', true).html('<i class="bi bi-arrow-repeat animate-spin"></i> Creating...');

            $.ajax({
                url: '/api/departments/store',
                method: 'POST',
                data: formData,
                success: function (response) {
                    toastr.success('Department created successfully!');
                    $('#modal-create-department').addClass('hidden');
                    $('#form-create-department')[0].reset();
                    // Reload page to show new department
                    setTimeout(() => location.reload(), 800);
                },
                error: function (xhr) {
                    toastr.error('Failed to create department: ' + (xhr.responseJSON?.message || 'Unknown error'));
                },
                complete: function () {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
</script>