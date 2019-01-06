<?php

declare(strict_types=1);

namespace Namdeveloper\Subscriptions\Console\Commands;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'namdeveloper:migrate:subscriptions {--force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Namdeveloper Subscriptions Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->warn($this->description);
        $this->call('migrate', ['--step' => true, '--path' => 'vendor/namdeveloper/laravel-subscriptions/database/migrations', '--force' => $this->option('force')]);
    }
}
