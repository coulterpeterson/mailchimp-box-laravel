<?php 

namespace Coulterpeterson\MailchimpBox;

use Mailchimp;
use React\Promise\Promise;
use React\Promise\Deferred;
use Illuminate\Support\Facades\Log;

class MailchimpBox {

    public static function subscribe(string $email, string $firstName, string $lastName, 
        string $audienceName, string $tagToApply)
    {

        // Skipping email validation as the MailChimp API package handles that for us

        $audienceIdDeferred = new Deferred();

        $data = [
            'email' => $email,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'audienceName' => $audienceName,
            'tagToApply' => $tagToApply
        ];
        
        // The API calling sequence in Promise form:
        self::get_audience_id( $data )
            ->then(function ( $data ) {

                self::subscribe_to_audience( $data )
                ->then(function ( $data ) {

                    self::apply_tag_to_member( $data )
                    ->done(
                        function ($data){
                            Log::info("Mailchimp-Box-Laravel: " . $data['email'] . " was just subscribed");
                        },
                        function($error) {
                            Log::error("Mailchimp-Box-Laravel: Enountered the following error when applying tag:");
                            Log::error($error);
                        }
                    );

                })
                ->otherwise(function ( $error ) {
                    // Error with subscribing to audience
                    Log::error("Mailchimp-Box-Laravel: Enountered the following error during subscription attempt:");
                    Log::error($error);
                });
            
            })
            ->otherwise(function ( $error ) {
                Log::error("Mailchimp-Box-Laravel: Enountered the following error when getting audience ID:");
                Log::error($error);
            });
    }

    private static function get_audience_id( $data )
    {
        $deferred = new Deferred();

        $audienceName = $data['audienceName'];

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
                $data['audienceId'] = $audienceItem['id'];
                $deferred->resolve($data);
            }
        }

        $deferred->reject('Audience not found.');

        return $deferred->promise();
    }

    private static function subscribe_to_audience( $data )
    {
        $deferred = new Deferred();

        $audienceId = $data['audienceId'];
        $email = $data['email'];
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];

        $merge = [
            'FNAME' => $firstName,
            'LNAME' => $lastName
        ];

        try 
        {
            // Using ::api instead of ::subscribe lets us tell Mailchimp to subscribe a user even if they were
                // accidentally deleted in the MC dashboard, or something similar
            $payload = [
				'email_address' => $email,
				'status_if_new' => 'subscribed',
				'email_type' => 'html',
				'status' => 'subscribed',
				'merge_fields' => [
					'FNAME' => $firstName,
					'LNAME' => $lastName,
				/*	'PHONE' => $customerPhone,
					'ADDRESS' => array(
						'addr1' => $customerAddr1,
						'addr2' => $customerAddr2,
						'city' => $customerAddrCity,
						'state' => $customerAddrState,
						'zip' => $customerAddrZip,
						'country' => $customerAddrCountry
					)*/
                ]
            ];

            $subscribe = Mailchimp::api( 'PUT', "/lists/$audienceId/members/" . md5($email), $payload );
        }
        catch (Exception $e)
        {
            $deferred->reject($e);
            return $deferred->promise();
        }

        $deferred->resolve($data);
        return $deferred->promise();
    }

    private static function apply_tag_to_member( $data )
    {
        $deferred = new Deferred();

        $audienceId = $data['audienceId'];
        $email = $data['email'];
        $tagToApply = $data['tagToApply'];

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
            $audiences = Mailchimp::api('POST', "/lists/$audienceId/members/" . md5($email) . "/tags", $payload);
        }
        catch (Exception $e)
        {
            $deferred->reject($e);
            return $deferred->promise();
        }

        $deferred->resolve($data);
        return $deferred->promise();
    }


}