<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $holidays = Holiday::where('year', $year)
            ->orWhereNull('year')
            ->orderBy('date', 'asc')
            ->get();

        // Get distinct years for filter
        $years = Holiday::select('year')->whereNotNull('year')->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        if (!in_array(date('Y'), $years)) {
            $years[] = (int)date('Y');
        }
        sort($years);
        $years = array_reverse($years);

        return view('holidays.index', compact('holidays', 'years', 'year'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'summary' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $date = Carbon::parse($request->date);
        
        $holiday = Holiday::create([
            'date' => $date->format('Y-m-d'),
            'year' => $date->year,
            'summary' => $request->summary,
            'description' => $request->description ?? 'Public',
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Holiday added successfully!',
            'holiday' => $holiday,
        ]);
    }

    public function update(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'summary' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $date = Carbon::parse($request->date);

        $holiday->update([
            'date' => $date->format('Y-m-d'),
            'year' => $date->year,
            'summary' => $request->summary,
            'description' => $request->description,
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Holiday updated successfully!',
            'holiday' => $holiday,
        ]);
    }

    public function getByDate(Request $request)
    {
        $date = $request->query('date');
        if (!$date) {
            return response()->json([
                'success' => false,
                'message' => 'Date is required.'
            ], 400);
        }

        $holidays = Holiday::whereDate('date', $date)->get();

        return response()->json([
            'success' => true,
            'holidays' => $holidays
        ]);
    }

    public function getApiHolidaysForDate(Request $request)
    {
        $dateStr = $request->query('date');
        if (!$dateStr) {
            return response()->json([
                'success' => false,
                'message' => 'Date is required.'
            ], 400);
        }

        try {
            $date = Carbon::parse($dateStr);
            $year = $date->year;
            $targetDateStr = $date->format('Y-m-d');

            // Retrieve year holidays from cache, or fetch and store for 24 hours
            $mappedHolidays = \Illuminate\Support\Facades\Cache::remember("sri_lankan_holidays_{$year}", 86400, function () use ($year) {
                try {
                    $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                        ->timeout(10)
                        ->get("https://raw.githubusercontent.com/Dilshan-H/srilanka-holidays/main/json/{$year}.json");

                    if ($response->successful()) {
                        $holidays = $response->json() ?? [];
                        
                        $mapped = [];
                        foreach ($holidays as $h) {
                            $mapped[] = [
                                'summary' => $h['summary'] ?? '',
                                'description' => implode(',', $h['categories'] ?? []),
                                'date' => $h['start'] ?? ''
                            ];
                        }
                        return $mapped;
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to fetch official holidays for year: " . $e->getMessage());
                }
                return null;
            });

            if ($mappedHolidays !== null) {
                $matchingHolidays = array_values(array_filter($mappedHolidays, function ($h) use ($targetDateStr) {
                    return $h['date'] === $targetDateStr;
                }));

                return response()->json([
                    'success' => true,
                    'holidays' => $matchingHolidays
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to fetch official holidays: " . $e->getMessage());
        }

        return response()->json([
            'success' => false,
            'message' => 'Could not fetch official holidays from API.'
        ]);
    }

    public function getApiHolidaysForYear(Request $request)
    {
        $year = $request->query('year', date('Y'));

        $mappedHolidays = \Illuminate\Support\Facades\Cache::remember("sri_lankan_holidays_{$year}", 86400, function () use ($year) {
            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                    ->timeout(10)
                    ->get("https://raw.githubusercontent.com/Dilshan-H/srilanka-holidays/main/json/{$year}.json");

                if ($response->successful()) {
                    $holidays = $response->json() ?? [];
                    
                    $mapped = [];
                    foreach ($holidays as $h) {
                        $mapped[] = [
                            'summary' => $h['summary'] ?? '',
                            'description' => implode(',', $h['categories'] ?? []),
                            'date' => $h['start'] ?? ''
                        ];
                    }
                    return $mapped;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to fetch official holidays for year: " . $e->getMessage());
            }
            return null;
        });

        if ($mappedHolidays !== null) {
            return response()->json([
                'success' => true,
                'holidays' => $mappedHolidays
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Could not fetch official holidays for year from API.'
        ]);
    }

    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return response()->json([
            'success' => true,
            'message' => 'Holiday deleted successfully!',
        ]);
    }

    public function saveAllApiHolidaysForYear(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // Fetch year holidays using existing cached API logic
        $mappedHolidays = \Illuminate\Support\Facades\Cache::remember("sri_lankan_holidays_{$year}", 86400, function () use ($year) {
            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                    ->timeout(10)
                    ->get("https://raw.githubusercontent.com/Dilshan-H/srilanka-holidays/main/json/{$year}.json");

                if ($response->successful()) {
                    $holidays = $response->json() ?? [];
                    
                    $mapped = [];
                    foreach ($holidays as $h) {
                        $mapped[] = [
                            'summary' => $h['summary'] ?? '',
                            'description' => implode(',', $h['categories'] ?? []),
                            'date' => $h['start'] ?? ''
                        ];
                    }
                    return $mapped;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to fetch official holidays for year: " . $e->getMessage());
            }
            return null;
        });

        if ($mappedHolidays === null) {
            return response()->json([
                'success' => false,
                'message' => 'Could not fetch official holidays for year from API.'
            ], 400);
        }

        $importedCount = 0;
        foreach ($mappedHolidays as $h) {
            // Check if holiday already exists on this date with the same summary/name
            $exists = Holiday::whereDate('date', $h['date'])
                ->where('summary', $h['summary'])
                ->exists();

            if (!$exists) {
                Holiday::create([
                    'date' => $h['date'],
                    'year' => (int)$year,
                    'summary' => $h['summary'],
                    'description' => $h['description'] ?: 'Public',
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
                $importedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully saved {$importedCount} official holidays for the year {$year}!",
        ]);
    }
}
