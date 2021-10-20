<?php 

namespace Coulterpeterson\MailchimpBox;

use Mailchimp;
//use Illuminate\Support\Facades\Log;

class MailchimpBox {

    public static function subscribe(string $email, string $firstName, string $lastName, 
        string $audienceName, string $tagToApply)
    {

        // Skipping email validation as the MailChimp API package handles that for us

        dd( self::get_audience_id( $audienceName ) );

        // Todo: Get audience ID from audience name

            // Then subscribe member to given list

            // Then add given tag to that member

    }

    private static function get_audience_id( string $audienceName )
    {
        $audiences = Mailchimp::api('GET', '/lists/?fields=[lists,lists.id,lists.name]&count=30', []);

        return $audiences;
    }


}