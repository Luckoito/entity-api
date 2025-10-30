<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Class SwaggerUiServiceProvider
 * @package App\Providers
 */
class SwaggerUiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() : void
    {
        Gate::define('viewSwaggerUI', function ($user = null) {
            return in_array(optional($user)->email, [
                //
            ]);
        });
    }
}
