<?php 

use Illuminate\Support\Facades\Route;
use Coulterpeterson\MailchimpBox\Http\Controllers\MailchimpBoxController;

Route::post('/mcbsubscribe', [ MailchimpController::class, 'subscribe' ])->name('mailchimpbox.subscribe');