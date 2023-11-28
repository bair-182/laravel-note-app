<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendEmail()
    {

        $data = [
            'title' => 'Тестовое письмо',
            'content' => 'Привет, это тестовое письмо из Laravel!'
        ];

        Mail::send('emails.sendmail', $data, function($message) {
            $message->to('fragemoon@gmail.com', 'Имя получателя')
                ->subject('Тестовое письмо');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        return "Письмо успешно отправлено";
    }
}
