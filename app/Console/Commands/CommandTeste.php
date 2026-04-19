<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\TesteJob;

class CommandTeste extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:command-teste';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        TesteJob::dispatch();
    }
}
