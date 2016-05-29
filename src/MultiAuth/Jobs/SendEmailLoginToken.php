<?php

namespace Askedio\MultiAuth\Jobs;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class SendEmailLoginToken implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;

    protected $user;

    protected $oauth;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $oauth)
    {
        $this->user = $user;
        
        $this->oauth = $oauth;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('auth.emails.link', ['user' => $this->user, 'oauth' => $this->oauth], function ($m) {
            $m->to($this->user->email, $this->user->name)->subject(trans('multiauth.email_subject'));
        });
    }
}
