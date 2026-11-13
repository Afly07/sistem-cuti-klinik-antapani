<?php

namespace App\Providers;

use App\Models\PengajuanCuti;
use App\Policies\PengajuanCutiPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(PengajuanCuti::class, PengajuanCutiPolicy::class);
    }
}
