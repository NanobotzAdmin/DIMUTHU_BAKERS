@extends('layouts.app')

@section('content')
    <!-- FullCalendar CDN -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <style>
        /* Custom FullCalendar Overrides */
        .fc {
            font-family: 'Inter', sans-serif;
            --fc-border-color: #f1f5f9;
            --fc-button-bg-color: #d97706;
            --fc-button-border-color: #d97706;
            --fc-button-hover-bg-color: #b45309;
            --fc-button-hover-border-color: #b45309;
            --fc-button-active-bg-color: #92400e;
            --fc-button-active-border-color: #92400e;
            --fc-today-bg-color: #fef3c7;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        .fc .fc-col-header-cell-cushion {
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 8px 0;
        }

        .fc .fc-daygrid-day-number {
            font-size: 0.875rem;
            font-weight: 500;
            color: #334155;
            padding: 8px;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: #fffbeb !important;
        }

        .fc-event {
            border-radius: 6px !important;
            padding: 4px 8px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            border: none !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: transform 0.1s ease, box-shadow 0.1s ease;
            margin-bottom: 2px !important;
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            cursor: pointer;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Holiday & Operations Calendar</h2>
                <p class="text-sm text-gray-500 mt-1">Configure company holidays and non-operational days. System scheduling
                    automatically skips these dates.</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Year Filter -->
                <form method="GET" action="{{ route('holidays.index') }}" class="flex items-center">
                    <select name="year" onchange="this.form.submit()"
                        class="rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-gray-700 py-2 pl-3 pr-10">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }} Calendar</option>
                        @endforeach
                    </select>
                </form>
                @if(Auth::user()->hasPermission('add_api_holidays'))
                    <button onclick="saveAllApiHolidaysForYear('{{ $year }}')"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150 transform hover:scale-[1.02]">
                        <i class="bi bi-cloud-download mr-2"></i> Save All API Holidays
                    </button>
                @endif
                <button onclick="openAddModal()"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-150 transform hover:scale-[1.02]">
                    <i class="bi bi-plus-lg mr-2"></i> Add Holiday
                </button>
            </div>
        </div>

        <!-- Quick Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="p-3 bg-amber-50 rounded-lg text-amber-600">
                    <i class="bi bi-calendar-check text-2xl"></i>
                </div>
                <div>
                    <span class="text-sm text-gray-500 block">Total Holidays for {{ $year }}</span>
                    <span class="text-2xl font-bold text-gray-800" id="statTotalHolidays">{{ $holidays->count() }}
                        Days</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                    <i class="bi bi-clock-history text-2xl"></i>
                </div>
                <div>
                    <span class="text-sm text-gray-500 block">Weekly Non-Working Day</span>
                    <span class="text-lg font-bold text-gray-800">Every Sunday</span>
                    <span class="text-xs text-gray-400 block mt-0.5">Mandatory System Holiday</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                    <i class="bi bi-shield-check text-2xl"></i>
                </div>
                <div>
                    <span class="text-sm text-gray-500 block">Order Scheduling Policy</span>
                    <span class="text-xs text-gray-600 block mt-1">Orders before 1 PM &rarr; Next working day 8 AM</span>
                    <span class="text-xs text-gray-600 block">Orders after 1 PM &rarr; Next working day 10 AM</span>
                </div>
            </div>
        </div>

        <!-- View Switcher Tabs -->
        <div
            class="flex items-center justify-between border-b border-gray-200 mb-6 bg-white p-2 rounded-xl shadow-sm border border-gray-100">
            <div class="flex gap-2">
                <button id="btnCalendarView" onclick="switchView('calendar')"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-150 bg-amber-50 text-amber-700 border border-amber-200/50 shadow-sm">
                    <i class="bi bi-calendar3"></i> Calendar View
                </button>
                <button id="btnListView" onclick="switchView('list')"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-150 text-gray-500 hover:text-gray-700 hover:bg-gray-50 border border-transparent">
                    <i class="bi bi-list-ul"></i> List View
                </button>
            </div>
            <div class="text-xs text-gray-400 font-medium px-3 hidden md:block">
                <i class="bi bi-info-circle mr-1"></i> Tip: Click on a date in the calendar to manage holidays.
            </div>
        </div>

        <!-- Calendar View Container -->
        <div id="calendarViewContainer"
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 transition-opacity duration-200">
            <!-- Calendar Legend -->
            <div
                class="flex flex-wrap items-center gap-4 mb-5 pb-3 border-b border-gray-100 text-xs font-semibold text-gray-600">
                <span class="text-gray-400 uppercase tracking-wider mr-1 text-[10px]">Calendar Legend:</span>
                <span
                    class="flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-800 rounded-lg border border-amber-100/50">
                    <span class="inline-block w-2.5 h-2.5 rounded bg-amber-500"></span> Saved Holidays
                </span>
            </div>
            <div id="holidayCalendar" class="min-h-[600px]"></div>
        </div>

        <!-- List View Container (Hidden by default) -->
        <div id="listViewContainer" class="hidden transition-opacity duration-200">
            @if($holidays->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 py-16 text-center">
                    <div
                        class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 text-amber-500">
                        <i class="bi bi-calendar-x text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No holidays configured</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">There are no custom holidays defined for the year
                        {{ $year }} yet. Add one to begin.</p>
                    <button onclick="openAddModal()"
                        class="mt-4 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors">
                        Configure a Holiday
                    </button>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Day of Week</th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Description</th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($holidays as $holiday)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex flex-col items-center justify-center w-12 h-12 bg-amber-50 rounded-lg text-amber-700 border border-amber-100/50">
                                                    <span
                                                        class="text-xs font-semibold uppercase leading-none">{{ $holiday->date->format('M') }}</span>
                                                    <span
                                                        class="text-lg font-bold leading-none mt-1">{{ $holiday->date->format('d') }}</span>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 block">{{ $holiday->date->format('F d, Y') }}</span>
                                                    <span class="text-xs text-gray-400">Custom configured holiday</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $holiday->date->format('l') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 font-semibold">
                                                {{ $holiday->summary ?? $holiday->description }}</div>
                                            @if($holiday->summary && $holiday->description)
                                                <div class="text-xs text-gray-500 mt-0.5">{{ $holiday->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <button onclick="openManageModal('{{ $holiday->date->format('Y-m-d') }}')"
                                                    class="p-1.5 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                                    title="Manage Date">
                                                    <i class="bi bi-gear text-lg"></i>
                                                </button>
                                                <button
                                                    onclick="confirmDeleteIndividual({{ $holiday->id }}, '{{ $holiday->date->format('F d, Y') }}')"
                                                    class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Delete Holiday">
                                                    <i class="bi bi-trash-fill text-lg"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Holiday Management Modal -->
    <div id="holidayModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-100 w-full">
                <div class="bg-white px-6 py-6">
                    <!-- Title & Header -->
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-gray-950" id="modalTitle">Manage Holidays</h3>
                        <button onclick="closeModal()"
                            class="text-gray-400 hover:text-gray-500 rounded-lg p-1 hover:bg-gray-100 transition-colors">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>

                    <!-- Main Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                        <!-- Left Column: Mini Calendar Picker -->
                        <div class="md:col-span-5 border-b md:border-b-0 md:border-r border-gray-100 pb-6 md:pb-0 md:pr-6">
                            <span class="block text-sm font-semibold text-gray-700 mb-3">Select Date from Calendar</span>
                            <div id="miniCalendarContainer"></div>
                            <div class="mt-4 p-3 bg-indigo-50/50 rounded-lg border border-indigo-100/50">
                                <span class="text-xs text-indigo-700 block font-semibold leading-relaxed">
                                    <i class="bi bi-info-circle mr-1"></i> Calendar & Dots:
                                </span>
                                <span class="text-xs text-gray-500 block mt-1 leading-relaxed">
                                    Click any date to view and manage holidays.
                                    <span class="block mt-1">
                                        <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1.5"></span> Saved
                                        Holidays
                                    </span>
                                </span>
                            </div>
                        </div>

                        <!-- Right Column: Holidays List & Form -->
                        <div class="md:col-span-7 flex flex-col justify-between">
                            <div>
                                <!-- Selected Date Header -->
                                <div class="flex items-center justify-between mb-4 border-b border-gray-50 pb-2">
                                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider"
                                        id="selectedDateTitle">
                                        Holidays on June 15, 2026
                                    </h4>
                                    <span id="selectedDateSundayBadge"
                                        class="hidden items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-550 text-red-700 border border-red-100">
                                        Sunday
                                    </span>
                                </div>

                                <!-- Holidays List Container -->
                                <div id="dateHolidaysList" class="space-y-3 max-h-[160px] overflow-y-auto pr-1 mb-4">
                                    <!-- Dynamically loaded list of holidays -->
                                </div>
                            </div>

                            <!-- Add Holiday Form -->
                            <div class="border-t border-gray-100 pt-4">
                                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Add Holiday to
                                    Selected Date</h5>
                                <form id="holidayForm" onsubmit="saveHoliday(event)">
                                    @csrf
                                    <input type="hidden" id="date" name="date">

                                    <div class="space-y-3">
                                        <div>
                                            <label for="summary"
                                                class="block text-xs font-semibold text-gray-600 mb-1">Holiday Summary /
                                                Name</label>
                                            <input type="text" id="summary" name="summary"
                                                placeholder="e.g., Binara Full Moon Poya Day" required
                                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2">
                                        </div>
                                        <div>
                                            <label for="description"
                                                class="block text-xs font-semibold text-gray-600 mb-1">Description /
                                                Types</label>
                                            <div class="flex gap-2">
                                                <input type="text" id="description" name="description"
                                                    placeholder="e.g., Public, Bank, Poya"
                                                    class="block flex-1 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2">
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-amber-600 hover:bg-amber-700 focus:outline-none transition-colors whitespace-nowrap shadow-sm"
                                                    id="submitBtn">
                                                    <i class="bi bi-plus-lg mr-1.5"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Modal Close Footer -->
                            <div class="flex items-center justify-end mt-4 border-t border-gray-50 pt-3">
                                <button type="button" onclick="closeModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm focus:outline-none">
                                    Close Window
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Global State variables
        window.holidaysData = @json($holidays);
        window.hasChanges = false;
        let mainCalendar = null;
        let miniCalendarInstance = null;

        // Group holidays initially by date
        const holidaysGroupedByDate = {};

        function rebuildHolidaysGroup() {
            // Clear old keys
            for (const k in holidaysGroupedByDate) delete holidaysGroupedByDate[k];

            window.holidaysData.forEach(h => {
                const dateStr = h.date.split('T')[0];
                if (!holidaysGroupedByDate[dateStr]) {
                    holidaysGroupedByDate[dateStr] = [];
                }
                holidaysGroupedByDate[dateStr].push(h);
            });
        }
        rebuildHolidaysGroup();

        // Custom Mini Calendar Class
        class MiniCalendar {
            constructor(elementId, options = {}) {
                this.element = document.getElementById(elementId);
                this.currentDate = new Date();
                this.selectedDate = new Date();
                this.onSelectDate = options.onSelectDate || (() => { });
                this.render();
            }

            prevMonth() {
                this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                this.render();
            }

            nextMonth() {
                this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                this.render();
            }

            setSelectedDate(dateStr) {
                this.selectedDate = new Date(dateStr);
                this.currentDate = new Date(dateStr);
                this.render();
            }

            render() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();
                const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                const daysOfWeek = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                let html = `
                    <div class="p-3 bg-gray-50 border border-gray-200/60 rounded-xl shadow-inner">
                        <div class="flex items-center justify-between mb-3">
                            <button type="button" class="p-1 hover:bg-gray-200 rounded-lg text-gray-600 transition-colors" onclick="miniCalendarInstance.prevMonth()">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span class="font-bold text-gray-800 text-sm">${monthNames[month]} ${year}</span>
                            <button type="button" class="p-1 hover:bg-gray-200 rounded-lg text-gray-600 transition-colors" onclick="miniCalendarInstance.nextMonth()">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-gray-400 uppercase mb-1">
                            ${daysOfWeek.map(day => `<div>${day}</div>`).join('')}
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center">
                `;

                // Offset spaces
                for (let i = 0; i < firstDay; i++) {
                    html += `<div class="h-8 w-8"></div>`;
                }

                // Days rendering
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const hasDbHolidays = holidaysGroupedByDate[dateStr] && holidaysGroupedByDate[dateStr].length > 0;

                    const isSelected = this.selectedDate.getFullYear() === year &&
                        this.selectedDate.getMonth() === month &&
                        this.selectedDate.getDate() === day;
                    const dateObj = new Date(year, month, day);
                    const isSunday = dateObj.getDay() === 0;

                    let dayClasses = "h-8 w-8 flex flex-col items-center justify-center rounded-lg text-xs font-semibold cursor-pointer relative transition-all duration-150 ";
                    if (isSelected) {
                        dayClasses += "bg-amber-600 text-white shadow scale-105";
                    } else if (isSunday) {
                        dayClasses += "text-red-500 hover:bg-red-50";
                    } else {
                        dayClasses += "text-gray-700 hover:bg-gray-200/80";
                    }

                    let dotHtml = '';
                    if (hasDbHolidays) {
                        dotHtml = `<span class="absolute bottom-1 w-1 h-1 rounded-full ${isSelected ? 'bg-white' : 'bg-amber-500'}"></span>`;
                    }

                    html += `
                        <div class="${dayClasses}" onclick="handleMiniCalendarSelect('${dateStr}')">
                            <span>${day}</span>
                            ${dotHtml}
                        </div>
                    `;
                }

                html += `
                        </div>
                    </div>
                `;

                this.element.innerHTML = html;
            }
        }

        // Switch view function
        function switchView(view) {
            const calendarBtn = document.getElementById('btnCalendarView');
            const listBtn = document.getElementById('btnListView');
            const calendarContainer = document.getElementById('calendarViewContainer');
            const listContainer = document.getElementById('listViewContainer');

            if (view === 'calendar') {
                calendarBtn.className = "flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-150 bg-amber-50 text-amber-700 border border-amber-200/50 shadow-sm";
                listBtn.className = "flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-150 text-gray-500 hover:text-gray-700 hover:bg-gray-50 border border-transparent";
                calendarContainer.classList.remove('hidden');
                listContainer.classList.add('hidden');
                if (mainCalendar) {
                    mainCalendar.updateSize();
                }
            } else {
                listBtn.className = "flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-150 bg-amber-50 text-amber-700 border border-amber-200/50 shadow-sm";
                calendarBtn.className = "flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-150 text-gray-500 hover:text-gray-700 hover:bg-gray-50 border border-transparent";
                listContainer.classList.remove('hidden');
                calendarContainer.classList.add('hidden');
            }
        }

        // Initialize Main Calendar & Mini Calendar Picker
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('holidayCalendar');

            mainCalendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: (function () {
                    const today = new Date();
                    const currentYear = today.getFullYear().toString();
                    if ('{{ $year }}' === currentYear) {
                        const y = today.getFullYear();
                        const m = String(today.getMonth() + 1).padStart(2, '0');
                        const d = String(today.getDate()).padStart(2, '0');
                        return `${y}-${m}-${d}`;
                    }
                    return '{{ $year }}-01-01';
                })(),
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                events: getCalendarEvents(),
                dateClick: function (info) {
                    openManageModal(info.dateStr);
                },
                eventClick: function (info) {
                    openManageModal(info.event.startStr);
                }
            });
            mainCalendar.render();

            // Initialize mini calendar component
            miniCalendarInstance = new MiniCalendar('miniCalendarContainer');
        });

        // Helper: Build events array for FullCalendar
        function getCalendarEvents() {
            const events = [];
            const savedDates = new Set();

            window.holidaysData.forEach(h => {
                const dateStr = h.date.split('T')[0];
                savedDates.add(dateStr);
                events.push({
                    id: 'db-' + h.id,
                    title: h.summary || h.description,
                    start: dateStr,
                    backgroundColor: '#d97706', // Amber-600
                    borderColor: '#d97706',
                    textColor: '#ffffff',
                    allDay: true,
                    extendedProps: { isDb: true }
                });
            });

            return events;
        }

        // Open Add Modal from Header (defaults to today or year start)
        function openAddModal() {
            const today = new Date();
            const yearStr = '{{ $year }}';
            let initialDateStr = `${yearStr}-01-01`;

            // If today is in the current filtered year, default to today
            if (today.getFullYear().toString() === yearStr) {
                initialDateStr = today.toISOString().split('T')[0];
            }

            openManageModal(initialDateStr);
        }

        // Main Modal Controller: opens manage dashboard for a date
        function openManageModal(dateStr) {
            document.getElementById('date').value = dateStr;

            // Reset inputs
            const summaryInput = document.getElementById('summary');
            const descInput = document.getElementById('description');
            if (summaryInput) summaryInput.value = '';
            if (descInput) descInput.value = '';

            // Parse date local to avoid timezone offset shifts
            const parts = dateStr.split('-');
            const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);

            // Update header texts
            const formattedDate = dateObj.toLocaleDateString('en-US', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
            document.getElementById('selectedDateTitle').innerText = `Holidays on ${formattedDate}`;

            // Sunday Badge check
            const isSunday = dateObj.getDay() === 0;
            const sundayBadge = document.getElementById('selectedDateSundayBadge');
            if (isSunday) {
                sundayBadge.classList.remove('hidden');
                sundayBadge.classList.add('inline-flex');
            } else {
                sundayBadge.classList.add('hidden');
                sundayBadge.classList.remove('inline-flex');
            }

            // Set selected date in mini-calendar
            miniCalendarInstance.setSelectedDate(dateStr);

            // Load holidays list
            loadDateHolidays(dateStr);

            // Open Modal visually
            const modal = document.getElementById('holidayModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Close Modal: reload only if modifications were done
        function closeModal() {
            const modal = document.getElementById('holidayModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';

            if (window.hasChanges) {
                window.location.reload();
            }
        }

        // Triggered when clicking dates in the modal mini-calendar
        function handleMiniCalendarSelect(dateStr) {
            openManageModal(dateStr);
        }

        // Load holidays on date via Ajax
        function loadDateHolidays(dateStr) {
            const container = document.getElementById('dateHolidaysList');
            container.innerHTML = `
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-amber-600"></div>
                </div>
            `;

            $.ajax({
                url: `/api/holidays/by-date`,
                method: 'GET',
                data: { date: dateStr },
                success: function (res) {
                    if (res.success && res.holidays.length > 0) {
                        let html = '';
                        res.holidays.forEach(h => {
                            html += `
                                <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-100 hover:bg-gray-100/50 rounded-xl transition-all duration-150" id="holiday-row-${h.id}">
                                    <div class="flex-1 min-w-0 pr-4">
                                        <span class="text-sm font-semibold text-gray-900 block truncate" id="holiday-desc-${h.id}">${h.summary || h.description}</span>
                                        ${h.summary && h.description ? `<span class="text-xs text-gray-500 block mt-0.5" id="holiday-sub-${h.id}">${h.description}</span>` : ''}

                                        <!-- Inline Edit Input -->
                                        <div class="hidden mt-2 flex flex-col gap-2" id="holiday-edit-container-${h.id}">
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-400 mb-0.5">Summary / Name</label>
                                                <input type="text" class="block w-full rounded-lg border-gray-300 text-xs focus:ring-amber-500 focus:border-amber-500 p-1.5" id="holiday-edit-summary-${h.id}" value="${h.summary || h.description}">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-400 mb-0.5">Description / Types</label>
                                                <input type="text" class="block w-full rounded-lg border-gray-300 text-xs focus:ring-amber-500 focus:border-amber-500 p-1.5" id="holiday-edit-desc-${h.id}" value="${h.summary ? h.description : ''}">
                                            </div>
                                            <div class="flex gap-2 justify-end mt-1">
                                                <button onclick="saveInlineEdit(${h.id})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow transition-colors">Save</button>
                                                <button onclick="cancelInlineEdit(${h.id})" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-xs font-medium transition-colors">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-1">
                                        <button onclick="toggleInlineEdit(${h.id})" class="p-1 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit inline">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </button>
                                        <button onclick="deleteHoliday(${h.id})" class="p-1 text-gray-400 hover:text-red-650 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `
                            <div class="text-center py-8 bg-gray-50/50 rounded-xl border border-dashed border-gray-200">
                                <i class="bi bi-calendar-minus text-2xl text-gray-300"></i>
                                <span class="block text-xs font-medium text-gray-500 mt-2">No custom holidays set for this date.</span>
                            </div>
                        `;
                    }
                },
                error: function () {
                    container.innerHTML = `
                        <div class="text-center py-8 text-red-550">
                            <i class="bi bi-exclamation-triangle text-lg mr-1"></i> Failed to fetch holidays list.
                        </div>
                    `;
                }
            });
        }

        // Inline Edit Controls
        function toggleInlineEdit(id) {
            const textSpan = document.getElementById(`holiday-desc-${id}`);
            const editContainer = document.getElementById(`holiday-edit-container-${id}`);
            const subSpan = document.getElementById(`holiday-sub-${id}`);

            textSpan.classList.toggle('hidden');
            if (subSpan) subSpan.classList.toggle('hidden');
            editContainer.classList.toggle('hidden');

            if (!editContainer.classList.contains('hidden')) {
                document.getElementById(`holiday-edit-summary-${id}`).focus();
            }
        }

        function cancelInlineEdit(id) {
            toggleInlineEdit(id);
        }

        function saveInlineEdit(id) {
            const newSummary = document.getElementById(`holiday-edit-summary-${id}`).value;
            const newDesc = document.getElementById(`holiday-edit-desc-${id}`).value;
            const dateStr = document.getElementById('date').value;

            if (!newSummary.trim()) {
                Swal.fire('Warning', 'Summary is required.', 'warning');
                return;
            }

            $.ajax({
                url: `/api/holidays/update/${id}`,
                method: 'POST',
                data: {
                    date: dateStr,
                    summary: newSummary,
                    description: newDesc,
                    _token: document.querySelector('input[name="_token"]').value
                },
                success: function (res) {
                    if (res.success) {
                        // Update global state
                        const idx = window.holidaysData.findIndex(h => h.id === id);
                        if (idx !== -1) {
                            window.holidaysData[idx] = res.holiday;
                        }

                        rebuildHolidaysGroup();
                        loadDateHolidays(dateStr);
                        syncMainCalendar();

                        toastr.success('Holiday updated successfully!');
                        window.hasChanges = true;
                    } else {
                        Swal.fire('Error', res.message || 'Failed to update holiday.', 'error');
                    }
                },
                error: function (xhr) {
                    let msg = 'Failed to update holiday.';
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    Swal.fire('Validation Error', msg, 'warning');
                }
            });
        }

        // Save all holidays for the entire year imported from API
        function saveAllApiHolidaysForYear(year) {
            Swal.fire({
                title: 'Import All Holidays?',
                text: `Are you sure you want to import all official Sri Lankan holidays for the year ${year} into your calendar?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Import All',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Importing Holidays...',
                        text: 'Please wait while we fetch and save all holidays.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '/api/holidays/save-all-year',
                        method: 'POST',
                        data: {
                            year: year,
                            _token: document.querySelector('input[name="_token"]').value
                        },
                        success: function (res) {
                            Swal.close();
                            if (res.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonColor: '#d97706'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', res.message || 'Failed to import holidays.', 'error');
                            }
                        },
                        error: function () {
                            Swal.close();
                            Swal.fire('Error', 'Failed to import holidays.', 'error');
                        }
                    });
                }
            });
        }

        // Save/Add Holiday for selected date
        function saveHoliday(e) {
            e.preventDefault();

            const dateStr = document.getElementById('date').value;
            const summaryInput = document.getElementById('summary');
            const descInput = document.getElementById('description');
            const submitBtn = document.getElementById('submitBtn');

            if (!summaryInput.value.trim()) {
                return;
            }

            submitBtn.disabled = true;

            const data = {
                date: dateStr,
                summary: summaryInput.value,
                description: descInput.value,
                _token: document.querySelector('input[name="_token"]').value
            };

            $.ajax({
                url: '/api/holidays/store',
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function (res) {
                    submitBtn.disabled = false;
                    if (res.success) {
                        summaryInput.value = '';
                        descInput.value = '';

                        // Add to global state
                        window.holidaysData.push(res.holiday);

                        rebuildHolidaysGroup();
                        loadDateHolidays(dateStr);
                        miniCalendarInstance.render();
                        syncMainCalendar();

                        toastr.success('Holiday added successfully!');
                        window.hasChanges = true;
                    } else {
                        Swal.fire('Error', res.message || 'Something went wrong', 'error');
                    }
                },
                error: function (xhr) {
                    submitBtn.disabled = false;
                    let msg = 'Failed to save holiday.';
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        msg = Object.values(errors).flat().join('\n');
                    }
                    Swal.fire('Validation Error', msg, 'warning');
                }
            });
        }

        // Delete Holiday
        function deleteHoliday(id) {
            const dateStr = document.getElementById('date').value;

            Swal.fire({
                title: 'Delete Holiday?',
                text: 'Are you sure you want to remove this holiday? This will re-enable scheduling on this date.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/holidays/delete/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: document.querySelector('input[name="_token"]').value
                        },
                        success: function (res) {
                            if (res.success) {
                                // Update global state
                                window.holidaysData = window.holidaysData.filter(h => h.id !== id);

                                rebuildHolidaysGroup();
                                loadDateHolidays(dateStr);
                                miniCalendarInstance.render();
                                syncMainCalendar();

                                toastr.success('Holiday deleted successfully!');
                                window.hasChanges = true;
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Failed to delete holiday.', 'error');
                        }
                    });
                }
            });
        }

        // Synchronize Main FullCalendar events
        function syncMainCalendar() {
            if (mainCalendar) {
                mainCalendar.removeAllEvents();
                mainCalendar.addEventSource(getCalendarEvents());
            }
        }

        // Confirmation when deleting directly from the list view table
        function confirmDeleteIndividual(id, formattedDate) {
            Swal.fire({
                title: 'Delete Holiday?',
                text: `Are you sure you want to remove the holiday on ${formattedDate}? This will re-enable scheduling on this date.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/holidays/delete/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: document.querySelector('input[name="_token"]').value
                        },
                        success: function (res) {
                            if (res.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: res.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Failed to delete holiday.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection