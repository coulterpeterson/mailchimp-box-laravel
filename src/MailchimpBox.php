<?php 

namespace Coulterpeterson\MailchimpBox;

class MailchimpBox{

    public static function event(string $input1, int $input2, ?string $input3 = null)
    {
        // send the event to mailchimp api
        return $input1 . ' - ' . $input2;
    }

}