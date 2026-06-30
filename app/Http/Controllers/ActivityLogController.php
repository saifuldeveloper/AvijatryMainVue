<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;

class ActivityLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage users|manage roles'),
        ];
    }

    public function index(Request $request): Response
    {
        $query = Activity::latest();

        if ($request->filled('daterange')) {
            $dates = explode(' - ', $request->daterange);
            if (count($dates) === 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            }
        }

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', 'LIKE', '%' . $request->log_name . '%');
        }

        $perPage = $request->input('per_page', 50);
        $logs = $query->paginate($perPage)->withQueryString();

        // Transform the paginated items
        $logs->getCollection()->transform(function ($row) {
            $event = $row->properties['event'] ?? null;
            
            // Translate the message
            $translatedMessage = $row->log_name ? __($row->log_name) : '—';
            // Fallback for translation if it contains dot
            if ($translatedMessage === $row->log_name && str_contains($row->log_name, 'pages.')) {
                $translatedMessage = __($row->log_name);
            }
            if ($translatedMessage === $row->log_name) {
                $translatedMessage = __('pages.' . $row->log_name);
            }

            // User Info
            $causer = activityLogCauserInfo($row->causer_id);
            $causerName = $causer ? $causer->name : '—';

            // Device Info
            $os = $row->properties['operatingSystem'] ?? '';
            $browser = $row->properties['browser'] ?? '';

            // Action
            $viewRoute = Route::has('view.soft-delete.activity-log')
                ? route('view.soft-delete.activity-log', $row->id)
                : '#';
            $generatedRoute = generateActivityLogRoute($row->properties);
            $target = $generatedRoute !== '#' ? '_blank' : '';

            return [
                'id' => $row->id,
                'event' => $event,
                'user_name' => $causerName,
                'ip' => $row->properties['ipAddress'] ?? '—',
                'message' => $translatedMessage,
                'os' => $os,
                'browser' => $browser,
                'time' => bangladeshiDateTimeFormatter($row->created_at),
                'view_route' => $viewRoute,
                'generated_route' => $generatedRoute,
                'target' => $target,
            ];
        });

        $users = User::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Setting/ActivityLog', [
            'logs' => $logs,
            'users' => $users,
            'filters' => $request->only(['daterange', 'causer_id', 'log_name', 'per_page']),
        ]);
    }

    public function trashList(Request $request): Response
    {
        $query = Activity::where('properties->event', 'SoftDelete')->latest();

        if ($request->filled('daterange')) {
            $dates = explode(' - ', $request->daterange);
            if (count($dates) === 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            }
        }

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', 'LIKE', '%' . $request->log_name . '%');
        }

        $perPage = $request->input('per_page', 50);
        $logs = $query->paginate($perPage)->withQueryString();

        $logs->getCollection()->transform(function ($row) {
            // Translate the message
            $translatedMessage = $row->log_name ? __($row->log_name) : '—';
            if ($translatedMessage === $row->log_name) {
                $translatedMessage = __('pages.' . $row->log_name);
            }

            // User Info
            $causer = activityLogCauserInfo($row->causer_id);
            $causerName = $causer ? $causer->name : '—';

            // Device Info
            $os = $row->properties['operatingSystem'] ?? '';
            $browser = $row->properties['browser'] ?? '';

            // Action
            $viewRoute = Route::has('view.soft-delete.activity-log')
                ? route('view.soft-delete.activity-log', $row->id)
                : '#';
            $generatedRoute = generateActivityLogRoute($row->properties);
            $target = $generatedRoute !== '#' ? '_blank' : '';
            $viewed = !empty($row->properties['viewed']) && $row->properties['viewed'] == true;

            return [
                'id' => $row->id,
                'event' => 'SoftDelete',
                'viewed' => $viewed,
                'user_name' => $causerName,
                'ip' => $row->properties['ipAddress'] ?? '—',
                'message' => $translatedMessage,
                'os' => $os,
                'browser' => $browser,
                'time' => bangladeshiDateTimeFormatter($row->created_at),
                'view_route' => $viewRoute,
                'generated_route' => $generatedRoute,
                'target' => $target,
            ];
        });

        $users = User::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Setting/TrashList', [
            'logs' => $logs,
            'users' => $users,
            'filters' => $request->only(['daterange', 'causer_id', 'log_name', 'per_page']),
        ]);
    }

    public function viewSoftDeleteActivityLog(Activity $activity): RedirectResponse
    {
        $properties = $activity->properties;
        if (isset($properties['viewed'])) {
            $properties['viewed'] = true;
            $activity->update([
                'properties' => $properties
            ]);
        }
        $url = generateActivityLogRoute($properties);
        return redirect($url);
    }
}
