<?php

namespace Paparee\BaleCms\Commands;

use Illuminate\Console\Command;

class BaleCmsCommand extends Command
{
    public $signature = 'bale-cms';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
