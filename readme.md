# mailchimp-box-laravel

A frictionless MailChimp signup box you can easily embed in a web page.

## Installation

* `composer require coulterpeterson\mailchimp-box-laravel`
* Add an `.env` file for `MC_KEY` (your API key)
* Optionally publish the config file with `php artisan vendor:publish --provider="Coulterpeterson\MailchimpBox\MailchimpBoxServiceProvider" --tag="config"`