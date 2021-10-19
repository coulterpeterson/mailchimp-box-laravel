<?php 

namespace Coulterpeterson\MailchimpBox;

use Illuminate\Support\ServiceProvider;

class MailchimpBoxServiceProvider extends ServiceProvider
{

    // bootstrap web services
    // listen for events
    // publish configuration files or database migrations
    public function boot()
    {
        
    }

    // extend functionality from other classes
    // register service providers
    // create singleton classes
    public function register()
    {
        $this->app->singleton( MailchimpBox::class, function() {
            return new MailchimpBox();
        });
    }

}