<?php 

use Illuminate\Support\Facades\Route;
use Coulterpeterson\MailchimpBox\Http\Controllers\MailchimpBoxController;

Route::get('/mcbshowbox', [ MailchimpBoxController::class, 'showBox' ]);
Route::post('/mcbsubscribe', [ MailchimpBoxController::class, 'subscribe' ]);
