<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;
use App\Jobs\ValidateBillStatusJob;
use Illuminate\Support\Facades\Log;

class SyncBillsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-bills-status';

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
        $this->info('Iniciando sincronização dos boletos...');
        Log::info('Iniciando sincronização dos boletos...');

        $total = 0;
        $delaySeconds = 0;

        Bill::where('need_validate', true)
            ->chunkById(100, function ($bills) use (&$total, &$delaySeconds) {

                foreach ($bills as $bill) {

                    ValidateBillStatusJob::dispatch($bill)
                        ->delay(now()->addSeconds($delaySeconds));

                    $total++;
                    $delaySeconds += 10;
                }
            });

        $this->info("{$total} jobs enviados para fila.");
        Log::info("{$total} jobs enviados para fila.");

        return Command::SUCCESS;
    }
}
