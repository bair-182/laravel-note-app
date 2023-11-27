<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //$user = Auth::user();
        $data = [
            'title' => 'Новая заметка создана',
            'content' => "Пользователь создал новую заметку"
        ];

        Mail::send('emails.sendmail', $data, function($message) {
            $message
                ->to(env('MAIL_ADMIN'), 'Имя получателя')
                ->subject('Новая заметка создана');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        echo "Уведомление о создании новой заметки успешно отправлено администратору";
    }
}
