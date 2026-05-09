<?php

namespace App\Providers;

use GuzzleHttp\Psr7\Request;
use Illuminate\Support\ServiceProvider;
use App\Models\Task;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
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
       
        // RateLimiter::for('login',function(Request $request){
             
        //     return Limit::perMinutes(10,5)->by($request->ip());
        // });
        \Gate::policy(Task::class, UserPolicy::class);
    }
}
