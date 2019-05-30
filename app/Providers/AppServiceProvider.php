<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\EntityHelper;
use App\Entity;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(App\Entity::class, function() {
            return new Entity;
        });
        $this->app->singleton(EntityHelper::class, function($app) {
            /**
            * @var global EntityHelper
            * @see App\Providers\Facades\Entity
            */
            return new EntityHelper($app[App\Entity::class]);
        });
    }

    public function provides() {
        return [App\Entity::class, EntityHelper::class];
    }
}
