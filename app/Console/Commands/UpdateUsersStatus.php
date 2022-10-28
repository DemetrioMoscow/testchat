<?php

namespace App\Console\Commands;

use App\Jobs\UpdateUsersStatuses;
use Illuminate\Console\Command;

class UpdateUsersStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users pusher status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        UpdateUsersStatuses::dispatchSync();

        return Command::SUCCESS;
    }
}
