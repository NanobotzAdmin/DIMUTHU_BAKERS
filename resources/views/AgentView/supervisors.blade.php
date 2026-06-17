@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#EDEFF5]">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#F59E0B] to-[#D97706] rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm text-white">
                            <i class="bi bi-person-check text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Supervisor Management</h1>
                            <p class="text-gray-500 text-xs sm:text-sm">Manage route supervisors, verification agents, and agent associations</p>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 flex gap-2">
                    <button onclick="openSupervisorModal()" class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2.5 rounded-xl flex items-center gap-2 shadow-sm text-sm font-semibold transition-all transform active:scale-95 cursor-pointer">
                        <i class="bi bi-plus-lg"></i>
                        Create Supervisor
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6 max-w-[1800px] mx-auto">
            <!-- Search and Filter Card -->
            <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6 shadow-sm">
                <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                    <div class="flex-1 w-full relative">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search by name, code, nic, contact..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 text-sm bg-white font-medium shadow-sm transition-all"
                            oninput="filterSupervisors()">
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button onclick="filterStatus('all')" id="filterAll" class="px-4 py-2 text-xs rounded-xl font-semibold transition-all bg-gray-900 text-white shadow-sm">All Statuses</button>
                        <button onclick="filterStatus('active')" id="filterActive" class="px-4 py-2 text-xs rounded-xl font-semibold transition-all bg-white text-gray-600 border border-gray-200 hover:bg-gray-50">Active</button>
                        <button onclick="filterStatus('inactive')" id="filterInactive" class="px-4 py-2 text-xs rounded-xl font-semibold transition-all bg-white text-gray-600 border border-gray-200 hover:bg-gray-50">Inactive</button>
                    </div>
                </div>
            </div>

            <!-- Supervisors Grid / Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="supervisorsGrid">
                @forelse($supervisors as $supervisor)
                    <div class="supervisor-card bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden"
                         data-status="{{ $supervisor['status'] }}"
                         data-search="{{ strtolower($supervisor['superviser_name'] . ' ' . $supervisor['superviser_code'] . ' ' . $supervisor['nic_number'] . ' ' . $supervisor['contact_number'] . ' ' . ($supervisor['agent_name'] ?? '')) }}">
                        
                        <!-- Left Status Bar -->
                        <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl transition-colors duration-300
                            {{ $supervisor['status'] == 1 ? 'bg-emerald-500' : 'bg-gray-300' }}"></div>

                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-gray-900 font-bold text-base">{{ $supervisor['superviser_name'] }}</h3>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="inline-flex w-2.5 h-2.5 rounded-full {{ $supervisor['status'] == 1 ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                    <span class="text-xs font-semibold text-gray-500" id="statusText-{{ $supervisor['id'] }}">
                                        {{ $supervisor['status'] == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <!-- Action Dropdown/Buttons -->
                            <div class="flex items-center gap-1">
                                <button onclick="editSupervisor({{ json_encode($supervisor) }})" class="w-8 h-8 rounded-lg flex items-center justify-center text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Supervisor">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button onclick="toggleSupervisorStatus({{ $supervisor['id'] }})" class="w-8 h-8 rounded-lg flex items-center justify-center {{ $supervisor['status'] == 1 ? 'text-red-500 hover:bg-red-50' : 'text-emerald-500 hover:bg-emerald-50' }} transition-colors" id="statusToggleBtn-{{ $supervisor['id'] }}" title="{{ $supervisor['status'] == 1 ? 'Deactivate' : 'Activate' }}">
                                    <i class="bi {{ $supervisor['status'] == 1 ? 'bi-toggle-on' : 'bi-toggle-off' }} text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Supervisor Info -->
                        <div class="space-y-2.5 pt-3 border-t border-gray-100">
                            <!-- Code -->
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <i class="bi bi-hash text-gray-400"></i>
                                <span>Supervisor Code: <strong class="text-gray-800 font-semibold">{{ $supervisor['superviser_code'] }}</strong></span>
                            </div>
                            <!-- Contact -->
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <i class="bi bi-telephone text-gray-400"></i>
                                <span class="font-medium">{{ $supervisor['contact_number'] }}</span>
                            </div>
                            <!-- NIC -->
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <i class="bi bi-card-text text-gray-400"></i>
                                <span>NIC: <strong class="text-gray-800 font-semibold">{{ $supervisor['nic_number'] }}</strong></span>
                            </div>
                            <!-- Address -->
                            <div class="flex items-start gap-2 text-xs text-gray-600">
                                <i class="bi bi-geo-alt text-gray-400 mt-0.5"></i>
                                <span class="line-clamp-2">Address: <span class="text-gray-800">{{ $supervisor['address'] }}</span></span>
                            </div>
                            <!-- Assigned Agent -->
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <i class="bi bi-person-badge text-gray-400"></i>
                                <span>Agent: <strong class="text-amber-700 font-semibold">{{ $supervisor['agent_name'] ?? 'None' }}</strong></span>
                            </div>
                            <!-- Associated User -->
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <i class="bi bi-person-circle text-gray-400"></i>
                                <span>User: <strong class="text-gray-800 font-semibold">{{ $supervisor['user_name'] ?? 'Not Linked' }}</strong></span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border border-gray-200 shadow-sm border-dashed">
                        <i class="bi bi-person-check text-gray-300 text-6xl mb-4 block"></i>
                        <h3 class="text-gray-900 text-lg font-bold mb-1">No supervisors found</h3>
                        <p class="text-gray-500 text-sm max-w-xs leading-relaxed mb-6">Create a supervisor to oversee agent distribution routes and load logs</p>
                        <button onclick="openSupervisorModal()"
                            class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                            <i class="bi bi-plus-lg mr-2"></i>Create Supervisor
                        </button>
                    </div>
                @endforelse
            </div>
            
            <!-- No Search Results -->
            <div id="noSearchResults" class="hidden flex flex-col items-center justify-center py-20 text-center">
                <i class="bi bi-search text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-gray-700 text-base font-bold">No matching supervisors</h3>
                <p class="text-gray-400 text-xs mt-1">Try adjusting your search terms</p>
            </div>
        </div>

        <!-- Supervisor Modal (Create/Edit) -->
        <div id="supervisor-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="supervisor-modal-title" role="dialog" aria-modal="true">
            <div id="supervisor-backdrop" class="fixed inset-0 bg-gray-900/70 transition-opacity opacity-0 duration-300 ease-out" onclick="closeSupervisorModal()"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div id="supervisor-panel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out flex flex-col">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4 flex items-center justify-between text-white flex-shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="bi bi-person-check text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-base" id="supervisorModalTitle">Create Supervisor</h3>
                                <span class="text-[10px] text-amber-100 tracking-wider uppercase font-semibold" id="supervisorModalSubtitle">Add details for a new supervisor</span>
                            </div>
                        </div>
                        <button onclick="closeSupervisorModal()" class="text-white/80 hover:text-white transition-colors">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form id="supervisorForm" method="POST" action="{{ route('agent-panel.supervisors.store-panel') }}">
                        @csrf
                        <input type="hidden" id="supervisorIdField" name="id" value="">
                        
                        <div class="px-6 py-5 space-y-4 overflow-y-auto max-h-[60vh]">
                            <!-- Supervisor Name -->
                            <div>
                                <label for="supervisorNameInput" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Supervisor Name *</label>
                                <input type="text" id="supervisorNameInput" name="superviser_name" required placeholder="e.g. Jane Smith"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 text-sm bg-white font-medium shadow-sm transition-all text-gray-700">
                            </div>

                            <!-- Supervisor Code -->
                            <div>
                                <label for="supervisorCodeInput" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Supervisor Code *</label>
                                <input type="text" id="supervisorCodeInput" name="superviser_code" required placeholder="e.g. SUP-001"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 text-sm bg-white font-medium shadow-sm transition-all text-gray-700">
                            </div>

                            <!-- NIC Number -->
                            <div>
                                <label for="nicInput" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">NIC Number *</label>
                                <input type="text" id="nicInput" name="nic_number" required placeholder="e.g. 199012345678"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 text-sm bg-white font-medium shadow-sm transition-all text-gray-700">
                            </div>

                            <!-- Contact Number -->
                            <div>
                                <label for="contactInput" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Contact Number *</label>
                                <input type="text" id="contactInput" name="contact_number" required placeholder="e.g. 0777654321"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 text-sm bg-white font-medium shadow-sm transition-all text-gray-700">
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="addressInput" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Address *</label>
                                <textarea id="addressInput" name="address" required rows="2" placeholder="e.g. 123 Galle Road, Colombo"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 text-sm bg-white font-medium shadow-sm transition-all text-gray-700"></textarea>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="supervisorStatusInput" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                                <select id="supervisorStatusInput" name="status"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 text-sm bg-white font-medium shadow-sm transition-all text-gray-700">
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100 flex-shrink-0">
                            <button type="button" onclick="closeSupervisorModal()"
                                class="px-4 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl text-sm font-semibold transition-colors">
                                Cancel
                            </button>
                            <button type="submit" id="saveSupervisorBtn"
                                class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors">
                                Save Supervisor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const supervisorModal = document.getElementById('supervisor-modal');
        const supervisorBackdrop = document.getElementById('supervisor-backdrop');
        const supervisorPanel = document.getElementById('supervisor-panel');
        const supervisorForm = document.getElementById('supervisorForm');
        let currentFilter = 'all';

        function openSupervisorModal() {
            supervisorForm.reset();
            supervisorForm.action = "{{ route('agent-panel.supervisors.store-panel') }}";
            document.getElementById('supervisorIdField').value = '';
            document.getElementById('supervisorModalTitle').innerText = 'Create Supervisor';
            document.getElementById('supervisorModalSubtitle').innerText = 'Add details for a new supervisor';
            
            supervisorModal.classList.remove('hidden');
            void supervisorModal.offsetWidth;
            supervisorBackdrop.classList.remove('opacity-0');
            supervisorBackdrop.classList.add('opacity-100');
            supervisorPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            supervisorPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }

        function closeSupervisorModal() {
            supervisorBackdrop.classList.remove('opacity-100');
            supervisorBackdrop.classList.add('opacity-0');
            supervisorPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            supervisorPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => { supervisorModal.classList.add('hidden'); }, 300);
        }

        function editSupervisor(supervisor) {
            openSupervisorModal();
            supervisorForm.action = `/agent-panel/supervisors/${supervisor.id}/update`;
            document.getElementById('supervisorIdField').value = supervisor.id;
            document.getElementById('supervisorNameInput').value = supervisor.superviser_name;
            document.getElementById('supervisorCodeInput').value = supervisor.superviser_code;
            document.getElementById('nicInput').value = supervisor.nic_number;
            document.getElementById('contactInput').value = supervisor.contact_number;
            document.getElementById('addressInput').value = supervisor.address;
            document.getElementById('supervisorStatusInput').value = supervisor.status;

            document.getElementById('supervisorModalTitle').innerText = 'Edit Supervisor';
            document.getElementById('supervisorModalSubtitle').innerText = 'Modify details for this supervisor';
        }

        function toggleSupervisorStatus(supervisorId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Change the status of this supervisor?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D4A017',
                cancelButtonColor: '#E5E7EB',
                confirmButtonText: 'Yes, change status!',
                customClass: {
                    cancelButton: 'text-gray-700 border border-gray-200'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/agent-panel/supervisors/${supervisorId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            Swal.fire({
                                title: 'Updated!',
                                text: res.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', res.message || 'Something went wrong', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'Failed to toggle status', 'error');
                    });
                }
            });
        }

        function filterSupervisors() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.supervisor-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                const status = card.getAttribute('data-status');
                
                const matchesSearch = searchData.includes(searchText);
                const matchesFilter = currentFilter === 'all' ||
                    (currentFilter === 'active' && status === '1') ||
                    (currentFilter === 'inactive' && status !== '1');

                if (matchesSearch && matchesFilter) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const noResults = document.getElementById('noSearchResults');
            if (cards.length > 0 && visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        function filterStatus(status) {
            currentFilter = status;
            ['All', 'Active', 'Inactive'].forEach(s => {
                const btn = document.getElementById('filter' + s);
                if (s.toLowerCase() === status) {
                    btn.classList.remove('bg-white', 'text-gray-600', 'border');
                    btn.classList.add('bg-gray-900', 'text-white', 'shadow-sm');
                } else {
                    btn.classList.remove('bg-gray-900', 'text-white', 'shadow-sm');
                    btn.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200');
                }
            });
            filterSupervisors();
        }

        // Close modal on Escape key press
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !supervisorModal.classList.contains('hidden')) {
                closeSupervisorModal();
            }
        });
    </script>
@endsection
