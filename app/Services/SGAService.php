<?php

    namespace App\Services;

    use Illuminate\Support\Facades\Http;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Log;
    use App\Models\Bill;

    class SGAService{

        public function searchPlateMember($cpfCnpjClient)
        {
            $tokens = [
                "GO" => env('TOKEN_SGA_GO'),
                "CE" => env('TOKEN_SGA')
            ];

            foreach ($tokens as $token) {

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ])->get("https://api.hinova.com.br/api/sga/v2/associado/buscar/{$cpfCnpjClient}");

                // ✅ sucesso
                if ($response->status() === 200 && $response->json()) {

                    $dataResponse = $response->json();

                    return array_map(function ($temp) {
                        return [
                            "codigo_veiculo" => $temp['codigo_veiculo'],
                            "plate" => $temp['placa'],
                            "model" => $temp['descricao_modelo'],
                            "status" => $temp['situacao']
                        ];
                    }, $dataResponse['veiculos'] ?? []);
                }

                // ❌ se NÃO for 406, já retorna erro direto
                if ($response->status() !== 406) {
                    return $response->json();
                }

                // 🔁 se for 406, tenta próximo token
            }

            // 🚫 se todos falharem com 406
            return [];
        }

        public function listBoletOfUser($cpfCnpjClient)
        {
            $hoje = \Carbon\Carbon::today();

            $tokens = [
                "GO" => env('TOKEN_SGA_GO'),
                "CE" => env('TOKEN_SGA')
            ];

            foreach ($tokens as $state => $token) {

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ])->post('https://api.hinova.com.br/api/sga/v2/listar/boleto/periodo/', [
                    'cpf_associado' => $cpfCnpjClient,
                    "data_vencimento_original_inicial" => $hoje->copy()->subDays(90)->format('d/m/Y'),
                    "data_vencimento_original_final"   => $hoje->copy()->addDays(90)->format('d/m/Y'),
                ]);

                // ✅ sucesso
                if ($response->status() === 200 && $response->json()) {

                    return [
                        "tokenState" => $state,
                        "data" => $response->json()
                    ];
                    
                }

                // ❌ se não for 406, retorna erro direto
                if ($response->status() !== 406) {
                    return $response->json();
                }

                // 🔁 se for 406, tenta próximo token
            }

            // 🚫 nenhum token retornou sucesso
            return [];
        }

        public function listBoletoOfPlate($plateVehicle)
        {
            $hoje = \Carbon\Carbon::today();

            $tokens = [
                "GO" => env('TOKEN_SGA_GO'),
                "CE" => env('TOKEN_SGA')
            ];

            foreach ($tokens as $state => $token) {

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ])->post('https://api.hinova.com.br/api/sga/v2/listar/boleto-associado-veiculo', [
                    'placa' => $plateVehicle,
                    "data_vencimento_original_inicial" => $hoje->copy()->subDays(90)->format('d/m/Y'),
                    "data_vencimento_original_final"   => $hoje->copy()->addDays(90)->format('d/m/Y'),
                ]);

                // ✅ sucesso
                if ($response->status() === 200 && $response->json()) {
                    return [
                        "tokenState" => $state,
                        "data" => $response->json()
                    ];
                }

                if ($response->status() === 406) {

                    $body = $response->json();

                    if (isset($body['mensagem']) && str_contains($body['mensagem'], 'Nenhum boleto')) {
                        return [];
                    }

                    continue;
                }

                return $response->json();
            }

            return [];
        }

        public function updateMaturity($codigoBolet, $state){

            Log::info('Iniciando processo de atualização de boleto', [
                'parametro' => $codigoBolet,
                'state' => $state
            ]);

            $plateVehicle = data_get($codigoBolet, 'veiculos.0.placa', null) ?? data_get($codigoBolet, 'veiculo.0.placa', null);

            try{

                $tokenState = $state === "CE" ? env('TOKEN_SGA') : env('TOKEN_SGA_GO');

                Log::info("Buscando pelo Token: ".$tokenState);

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $tokenState,
                    'Accept' => 'application/json',
                ])->post('https://api.hinova.com.br/api/sga/v2/alterar/vencimento-boleto', [
                    "nosso_numero" => $codigoBolet['nosso_numero'],
                    "nova_data_vencimento" => Carbon::now()->format('d/m/Y')
                ]);

                if($response->status() === 200){

                    //BOLETO ATUALIZADO PORTANTO GERAR BILLET PARA O BOLETO ATUALIZADO

                    $boletoAtualizado = $response->json();

                    Bill::updateOrCreate(
                        [
                            "nosso_numero" => data_get($codigoBolet, 'nosso_numero', 0),
                        ],
                        [
                            "codigo_boleto" => data_get($codigoBolet, 'codigo_boleto', 0),
                            "nova_data_vencimento" => $boletoAtualizado['nova_data_vencimento'],
                            "cpf_cnpj" => data_get($codigoBolet, 'cpf', 'Não Identificado'),
                            "associado" => data_get($codigoBolet, 'nome_associado', 'Não Identificado'),
                            "linha_digitavel" => data_get($codigoBolet, 'linha_digitavel', 'Não Identificado'),
                            "link_boleto" => data_get($codigoBolet, 'link_boleto', 'Não Identificado'),
                            "valor_boleto" => floatval(data_get($codigoBolet, 'valor_boleto', 0)),
                            "plate" => $plateVehicle
                        ]
                    );

                    return $response->json();

                }

            }catch(\Exception $e){

                Log::error('Erro ao atualizar boleto', [
                    'parametro' => $codigoBolet,
                    'mensagem' => $e->getMessage(),
                    'linha' => $e->getLine(),
                    'arquivo' => $e->getFile(),
                ]);

                return response()->json([
                    'erro' => 'Não foi possível atualizar o boleto.'
                ], 500);

            }

        }

        public function getBolet($nosso_numero){

            try{
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                    'Accept' => 'application/json',
                ])->get('https://api.hinova.com.br/api/sga/v2/buscar/boleto/'.$nosso_numero, []);

                if($response->status() === 200){

                    return $response->json();

                }

            }catch(\Exception $e){

                Log::error('Erro ao buscar boleto', [
                    'parametro' => $nosso_numero,
                    'mensagem' => $e->getMessage(),
                    'linha' => $e->getLine(),
                    'arquivo' => $e->getFile(),
                ]);

                return response()->json([
                    'erro' => 'Não foi possível buscar o boleto.'
                ], 500);

            }

        }

    }