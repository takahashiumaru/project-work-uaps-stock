<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\WorkReport;
use App\Models\Request as HoRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layout.admin', function ($view) {
            // Pending Work Reports only
            $pendingWorkReportsCount = WorkReport::whereIn('status', ['Pending'])->count();

            // Pending Requests only
            $pendingRequestsCount = HoRequest::whereIn('status', ['Pending'])->count();
            $pendingRequestsLatest = HoRequest::whereIn('status', ['Pending'])
                ->with(['product:id,name'])
                ->orderByDesc('request_date')
                ->limit(5)
                ->get(['id', 'product_id', 'qty_requested', 'request_date', 'status']);

            // CHANGED: badge total = Request HO pending only
            $pendingNotifTotal = (int) $pendingRequestsCount;

            $view->with([
                'pendingWorkReportsCount' => $pendingWorkReportsCount,
                'pendingRequestsCount' => $pendingRequestsCount,
                'pendingRequestsLatest' => $pendingRequestsLatest,
                'pendingNotifTotal' => $pendingNotifTotal,
            ]);
        });
    }
}
