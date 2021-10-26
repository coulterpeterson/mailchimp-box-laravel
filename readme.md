# mailchimp-box-laravel

A frictionless MailChimp signup box you can easily embed in a web page.

## Installation

* `composer require coulterpeterson\mailchimp-box-laravel`
* Add an `.env` file for `MC_KEY` (your API key)
* Optionally publish the config file with `php artisan vendor:publish --provider="Coulterpeterson\MailchimpBox\MailchimpBoxServiceProvider" --tag="config"`

## Example Usage

Create a blade (or similar templating engine) component file that sends a request like the following

```php
<?php 

    $client = new GuzzleHttp\Client();

    $res = $client->request('GET', 'http://yoursitename.test/email/mcbshowbox', [
        'query' => [
            'audienceName' => 'yo',
            'tagToApply' => 'yo'
        ]
    ]);

    echo $res->getBody();

?>
```