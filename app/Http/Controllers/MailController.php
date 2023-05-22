<?php

namespace App\Http\Controllers;

use App\Mail\SmtpMail;
use App\Models\UsersSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return \response()
     */
    public function index()
    {
        $mailData = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp.'
        ];

        $data = UsersSocial::whereUserId(Auth::id())->first();
        
        // mail($data->value,
        //     'Тема письма',
        //     'Текст письма',
        //     'aiwprtonsteam@gmail.com');

        Mail::to($data->value)->send(new SmtpMail($mailData));
            
        dd("Email is sent successfully.");
    }
}
