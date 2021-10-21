<?php 

namespace Coulterpeterson\MailchimpBox;

use Mailchimp;
use React\Promise\Promise;
use React\Promise\Deferred;
//use Illuminate\Support\Facades\Log;

class MailchimpBox {

    public static function subscribe(string $email, string $firstName, string $lastName, 
        string $audienceName, string $tagToApply)
    {

        // Skipping email validation as the MailChimp API package handles that for us

        $audienceIdDeferred = new Deferred();
        
        self::get_audience_id( $audienceName )
            ->then(function ( $audienceId ) {
                self::subscribe_to_audience( $audienceId, $email, $firstName, $lastName )
                ->then(function ( $subscribeResult ) {
                    self::apply_tag_to_member( $audienceId, $email, $tagToApply )
                    ->done(
                        function ($result){
                            dd($result);
                        },
                        function($error) {
                            dd($error);
                        }
                    );
                })
                ->otherwise(function ( $error ) {
                    // Error with subscribing to audience
                    dd($error);
                })
            ->otherwise(function ( $error ) {
                // Error with getting audience ID
                dd($error);
            });
        });



            // Then subscribe member to given list

            // Then add given tag to that member

    }

    private static function get_audience_id( string $audienceName )
    {
        $deferred = new Deferred();

        try 
        {
            $audiences = Mailchimp::api('GET', '/lists/?fields=[lists,lists.id,lists.name]&count=30', []);
            // Could likely instead use Mailchimp::getLists(['fields' => '[lists,lists.id,lists.name]', 'count' => '30']);
        }
        catch (Exception $e)
        {
            $deferred->reject($e);
            return $deferred->promise();
        }

        foreach( $audiences['lists'] as $audienceItem )
        {
            // If the audienceName is found in this item's name
            $stringA = strtolower(strval($audienceItem['name']));
            $stringB = strtolower(strval($audienceName));

            if( $stringA === $stringB )
            {
                $deferred->resolve($audienceItem['id']);
            }
        }

        $deferred->reject('Audience not found.');

        return $deferred->promise();
    }

    private static function subscribe_to_audience( $audienceId, $email, $firstName, $lastName )
    {
        $deferred = new Deferred();

        $merge = [
            'FNAME' => $firstName,
            'LNAME' => $lastName
        ];

        try 
        {
            // TODO: Swith to ::api function if this doesn't implement `'status_if_new' => 'subscribed'`
            $audiences = Mailchimp::subscribe($audienceId, $email, $merge, false);
        }
        catch (Exception $e)
        {
            $deferred->reject($e);
            return $deferred->promise();
        }

        $deferred->resolve(true);
        return $deferred->promise();
    }

    private static function apply_tag_to_member( $audienceId, $email, $tagToApply )
    {
        $deferred = new Deferred();

        $payload = [
            'tags' => [
                [
                    "name" => $tagToApply,
                    "status" => "active"
                ]
            ]
        ];

        try 
        {
            $audiences = Mailchimp::api('POST', `/lists/$audienceId/members/`.(md5($email)).`/tags`, $payload);
        }
        catch (Exception $e)
        {
            $deferred->reject($e);
            return $deferred->promise();
        }

        $deferred->resolve(true);
        return $deferred->promise();
    }


}