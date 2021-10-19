<?php 

namespace Coulterpeterson\MailchimpBox;

use NZTim\Mailchimp;
use Illuminate\Support\Facades\Log;

class MailchimpBox{

    public static function subscribe(string $email, string $firstName, string $lastName, 
        string $audienceName, string $tagToApply)
    {
        // send the event to mailchimp api
        //return $input1 . ' - ' . $input2;

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::error('Invalid email provided.');
            return;
        }

        $email = strtolower($email);

        // Todo: Get audience ID from audience name

            // Then subscribe member to given list

            // Then add given tag to that member

    }

    private static function get_audience_id( string $audienceName )
    {

    }

    private static function get_location_prefix_for_api( string $apiKey ) 
    {
        $subStrings = explode( '-', $apiKey );

        return $subStrings[1];
    }


}