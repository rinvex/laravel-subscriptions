<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Console\Commands;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rinvex:migrate:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Rinvex Subscriptions Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn('Migrate rinvex/subscriptions:');
        $this->call('migrate', ['--step' => true, '--path' => 'vendor/rinvex/subscriptions/database/migrations']);
    }
}
