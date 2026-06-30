<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'appName' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
                'permissions' => $request->user() ? $request->user()->getAllPermissions()->pluck('name') : [],
            ],
            'stats' => [
                'commitments_count' => function () {
                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('commitments')) {
                            return \Illuminate\Support\Facades\DB::table('commitments')
                                ->whereDate('commitment_date', now()->toDateString())
                                ->count();
                        }
                    } catch (\Exception $e) {}
                    return 0;
                },
                'trash_count' => function () {
                    try {
                        if (function_exists('unViewedSoftDeleteActivityLogs')) {
                            return unViewedSoftDeleteActivityLogs();
                        }
                    } catch (\Exception $e) {}
                    return 0;
                },
            ],
            'locale' => function () {
                return session('locale', app()->getLocale());
            },
            'theme' => function () {
                return session('theme', 'light');
            },
            'translations' => function () {
                $locale = session('locale', app()->getLocale());
                
                $menu = trans('menu', [], $locale);
                $pages = trans('pages', [], $locale);
                $main = trans('main', [], $locale);
                $permission = trans('permission', [], $locale);
                
                return [
                    'menu' => is_array($menu) ? $menu : [],
                    'pages' => is_array($pages) ? $pages : [],
                    'main' => is_array($main) ? $main : [],
                    'permission' => is_array($permission) ? $permission : [],
                ];
            },
        ];
    }
}
