<?php

namespace Modules\Church\Http\Middleware;

use Closure;
use Modules\Church\Utilities\InstallDashboard;

class SeedDashboardWidgets
{
    /**
     * Attach church widgets after the company is identified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (company_id()) {
            try {
                (new InstallDashboard())->ensure();
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $next($request);
    }
}
