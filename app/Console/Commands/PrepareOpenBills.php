<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Bill;

class PrepareOpenBills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prepare-open-bills';

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
        $this->info('Iniciando preparação dos boletos...');
        Log::info('Iniciando preparação dos boletos...');

        $total = Bill::where('descricao_situacao_boleto', 'ABERTO')
            ->update([
                'need_validate' => true,
                'updated_at' => now()
            ]);

        $this->info("{$total} boletos marcados para validação.");

        Log::info("{$total} boletos marcados para validação.");

        return Command::SUCCESS;

    }
}
