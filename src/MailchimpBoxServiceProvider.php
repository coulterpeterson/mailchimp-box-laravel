<?php 

namespace Coulterpeterson\MailchimpBox;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MailchimpBoxServiceProvider extends ServiceProvider
{

    // bootstrap web services
    // listen for events
    // publish configuration files or database migrations
    public function boot()
    {
        // If we're in the console, give the option for the user to publish the config file
            // so they can make their own modifications as desired
            // Thanks to https://laravelpackage.com/07-configuration-files.html
        if ($this->app->runningInConsole()) {

            $this->publishes([
              __DIR__.'/../config/config.php' => config_path('mailchimpbox.php'),
            ], 'config');
            // Usage: php artisan vendor:publish --provider="Coulterpeterson\MailchimpBox\MailchimpBoxServiceProvider" --tag="config"
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'mailchimpbox');

        $this->registerRoutes();
    }

    // extend functionality from other classes
    // register service providers
    // create singleton classes
    public function register()
    {
        // Register our config file with the key `mailchimpbox`
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'mailchimpbox');

        $this->app->singleton( MailchimpBox::class, function() {
            return new MailchimpBox();
        });
    }

    // Thanks https://laravelpackage.com/09-routing.html#routes for
        // the prefix and middleware enabling assistance
    protected function registerRoutes()
    {
        Route::group( $this->routeConfiguration(), function() {
            $this->loadRoutesFrom( __DIR__.'/../routes/web.php' );
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('mailchimpbox.prefix'),
            'middleware' => config('mailchimpbox.middleware'),
        ];
    }

}