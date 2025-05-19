<?php

namespace Paparee\BaleCms\Commands;

use Illuminate\Console\Command;

class BaleSeedCommand extends Command
{
    protected $signature = 'bale:seed {--class= : The seeder class to run}';

    protected $description = 'Run Bale CMS package seeders';

    public function handle()
    {
        $class = $this->option('class') ?? 'Paparee\\BaleCms\\Database\\Seeders\\BaleCmsSeeder';

        if (!class_exists($class)) {
            $this->error("Seeder class [$class] not found.");
            return 1;
        }

        $this->info("Seeding: {$class}");

        $this->call('db:seed', ['--class' => $class]);

        $this->info('Seeding complete!');

        return 0;
    }
}
