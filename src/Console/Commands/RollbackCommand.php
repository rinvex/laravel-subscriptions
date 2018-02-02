<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Console\Commands;

use Illuminate\Console\Command;

class RollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rinvex:rollback:subscriptions {--force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback Rinvex Subscriptions Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->warn($this->description);
        $this->call('migrate:reset', ['--path' => 'vendor/rinvex/subscriptions/database/migrations', '--force' => $this->option('force')]);
    }
}
