<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\SGAService;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\Bill;

class ValidateBillStatusJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Bill $bill
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SGAService $sgaService): void
    {
        Log::info("Validando boleto {$this->bill->nosso_numero}");

        try {
            $response = $sgaService->getBolet($this->bill->nosso_numero);

            $status = data_get($response, 'descricao_situacao_boleto');
            $codigo = data_get($response, 'codigo_situacao_boleto');

            if ($status === 'BAIXADO' || $codigo == '1') {

                $this->bill->update([
                    'descricao_situacao_boleto' => 'BAIXADO',
                    'codigo_situacao_boleto' => '1',
                    'need_validate' => false,
                    'data_pagamento' => data_get($response, 'data_pagamento'),
                    'valor_pagamento' => (float) data_get($response, 'valor_pagamento', 0),
                ]);

                Log::info("Boleto {$this->bill->id} baixado.");

            } else {

                $this->bill->update([
                    'need_validate' => false,
                ]);

                Log::info("Boleto {$this->bill->id} continua aberto.");
            }

        } catch (\Throwable $e) {

            Log::error("Erro ao validar boleto {$this->bill->id}: {$e->getMessage()}");
        }
    }
}
