<?php
namespace Yoan1005\Admigen;

use Illuminate\Support\ServiceProvider;

class AdmigenServiceProvider extends ServiceProvider
{
  /**
  * Perform post-registration booting of services.
  *
  * @return void
  */
  public function boot()
  {

    $this->loadViewsFrom(__DIR__.'/resources/views', 'admigen');

    $this->app['router']->aliasMiddleware('Admin', Middleware\Admin::class);

    $this->app['router']->namespace('Yoan1005\\Admigen\\Controllers')
    ->middleware(['web'])
    ->group(function () {
      $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    });

    if ($this->app->runningInConsole()) {
      $this->bootForConsole();
    }
  }

  /**
  * Register any package services.
  *
  * @return void
  */
  public function register()
  {
    $this->mergeConfigFrom(__DIR__.'/config/admigen.php', 'admigen');
  }

  /**
  * Get the services provided by the provider.
  *
  * @return array
  */
  public function provides()
  {
    return ['admigen'];
  }

  /**
  * Console-specific booting.
  *
  * @return void
  */
  protected function bootForConsole()
  {
    $this->publishes([
      __DIR__.'/config/admigen.php' => config_path('admigen.php'),
    ], 'admigen.config');

    $this->publishes([
      __DIR__.'/resources/views' => base_path('resources/views/vendor/admigen'),
    ], 'admigen.views');

    $this->publishes([
      __DIR__ . '/public/assets/' => public_path('vendor/admigen'),
    ], 'public');
  }
}
