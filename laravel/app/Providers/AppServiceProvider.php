<?php

namespace App\Providers;

use App\Models\Device;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $devices = Device::orderBy('id', 'asc')->get();
        view()->share('devices', $devices);
    }
}
