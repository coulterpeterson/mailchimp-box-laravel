<?php

namespace Coulterpeterson\MailchimpBox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MailchimpBoxController extends Controller
{
    public function showBox(Request $request)
    //public function showBox($audienceName, $tagToApply = '')
    {
        // Expecting $params->audienceName and $params->tagToApply
        return view( 'mailchimpbox::mailchimpbox.mailchimpbox', $request );
        // $data = [
        //     'audienceName' => $audienceName,
        //     'tagToApply' => $tagToApply
        // ];
        
        // return view( 'mailchimpbox::mailchimpbox.mailchimpbox', $data );
    }
    
    public function subscribe(Request $request)
    {
        Log::info("Mailchimp-Box-Laravel: Subscribe request raw:");
        Log::info($request);
    }

    // Some example functions
    // public function store()
    // {
    //     // Let's assume we need to be authenticated
    //     // to create a new post
    //     if (! auth()->check()) {
    //         abort (403, 'Only authenticated users can create new posts.');
    //     }

    //     request()->validate([
    //         'title' => 'required',
    //         'body'  => 'required',
    //     ]);

    //     // Assume the authenticated user is the post's author
    //     $author = auth()->user();

    //     $post = $author->posts()->create([
    //         'title'     => request('title'),
    //         'body'      => request('body'),
    //     ]);

    //     return redirect(route('posts.show', $post));
    // }
}
