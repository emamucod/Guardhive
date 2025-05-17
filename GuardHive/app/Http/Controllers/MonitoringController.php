<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function showDetectedImages(Request $request)
    {
        try {
            // Get all image files in the public/detected_images folder
            $imageFiles = File::files(public_path('detected_images'));

            // Generate URLs and dates for each image
            $imageUrls = [];
            foreach ($imageFiles as $file) {
                $filename = basename($file); // e.g., detected_20230517_153120.jpg
                $parts = explode('_', $filename); // ["detected", "20230517", "153120.jpg"]

                // Compose timestamp string for Carbon parsing
                if (count($parts) >= 3) {
                    $timestamp = $parts[1] . '_' . pathinfo($parts[2], PATHINFO_FILENAME); // "20230517_153120"
                } else {
                    // Fallback: if filename format is unexpected
                    continue;
                }

                // Parse timestamp into Carbon date
                $date = Carbon::createFromFormat('Ymd_His', $timestamp);

                $imageUrls[] = [
                    'url' => asset('detected_images/' . $filename),
                    'date' => $date,
                ];
            }

            // Filter by report type if specified
            if ($request->has('report')) {
                $reportType = $request->get('report');
                if ($reportType === 'daily') {
                    $imageUrls = array_filter($imageUrls, fn($image) => $image['date']->isToday());
                } elseif ($reportType === 'weekly') {
                    $imageUrls = array_filter($imageUrls, fn($image) => $image['date']->isSameWeek());
                } elseif ($reportType === 'monthly') {
                    $imageUrls = array_filter($imageUrls, fn($image) => $image['date']->isSameMonth());
                }
            }

            // Manual pagination of the filtered images
            $page = Paginator::resolveCurrentPage() ?: 1;
            $perPage = 10;
            $collection = collect($imageUrls);
            $currentPageItems = $collection->slice(($page - 1) * $perPage, $perPage)->values();

            $paginatedImages = new LengthAwarePaginator(
                $currentPageItems,
                $collection->count(),
                $perPage,
                $page,
                ['path' => Paginator::resolveCurrentPath()]
            );

            // Return view with paginated images
            return view('monitoring', ['imageUrls' => $paginatedImages]);
        } catch (\Exception $e) {
            Log::error('Error in MonitoringController: ' . $e->getMessage());
            return view('monitoring')->with('error', 'Something went wrong!');
        }
    }
}
