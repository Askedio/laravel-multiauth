<?php

namespace Askedio\MultiAuth\Commands;

use App\UserOauth;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredOauthTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiauth:deleteExpiredTokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired tokens.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(UserOauth $oauth)
    {
        $oauth->where('expires_at', '<', Carbon::now())->delete();
    }
}
