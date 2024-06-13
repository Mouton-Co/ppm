<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ClearUserConfigs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-user-configs {user}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = $this->argument('user');

        if (! empty($this->argument('user')) && ! empty($user = User::find($user))) {
            $user->configurations = null;
            $user->save();
            $this->info('User configurations cleared.');
            return;
        }

        foreach (User::all() as $user) {
            $user->configurations = null;
            $user->save();
        }
        $this->info('All user configurations cleared.');
    }
}
