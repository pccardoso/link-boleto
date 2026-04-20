<?php

    namespace App\Services;

    use Illuminate\Support\Facades\Http;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Log;
    use App\Models\Bill;

    class SGAService {

        public function searchPlateMember($cpfCnpjClient){
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                'Accept' => 'application/json',
            ])->get('https://api.hinova.com.br/api/sga/v2/associado/buscar/'.$cpfCnpjClient);

            if($response->status() === 200 && $response != null){

                $dataResponse = $response->json();

                $listPlate = array_map(function($temp){
                    return [
                        "codigo_veiculo" => $temp['codigo_veiculo'],
                        "plate" => $temp['placa'],
                        "model" => $temp['descricao_modelo'],
                        "status" => $temp['situacao']
                    ];
                }, $dataResponse['veiculos']);

                return $listPlate;

            }

            if($response->status() === 406){

                return [];

            }

            return $response->json();
            
        }

        public function listBoletOfUser($cpfCnpjClient) {

            $hoje = Carbon::today();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                'Accept' => 'application/json',
            ])->post('https://api.hinova.com.br/api/sga/v2/listar/boleto/periodo/', [
                'cpf_associado' => $cpfCnpjClient,
                "data_vencimento_original_inicial" => $hoje->copy()->subDays(90)->format('d/m/Y'),
                "data_vencimento_original_final"   => $hoje->copy()->addDays(90)->format('d/m/Y'),
            ]);

            if($response->status() === 200 && $response != null){

                return $response->json();

            }

        }

        public function listBoletoOfPlate($plateVehicle) {

            $hoje = Carbon::today();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                'Accept' => 'application/json',
            ])->post('https://api.hinova.com.br/api/sga/v2/listar/boleto-associado-veiculo', [
                'placa' => $plateVehicle,
                "data_vencimento_original_inicial" => $hoje->copy()->subDays(90)->format('d/m/Y'),
                "data_vencimento_original_final"   => $hoje->copy()->addDays(90)->format('d/m/Y'),
            ]);

            if($response->status() === 200 && $response != null){

                return $response->json();

            }

            if($response->status() === 406){

                return [];

            }

            return $response->json();

        }

        public function updateMaturity($codigoBolet){

            Log::info('Iniciando processo de atualização de boleto', [
                'parametro' => $codigoBolet
            ]);

            $plateVehicle = data_get($codigoBolet, 'veiculos.0.placa', null) ?? data_get($codigoBolet, 'veiculo.0.placa', null);

            try{

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
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

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                'Accept' => 'application/json',
            ])->get('https://api.hinova.com.br/api/sga/v2/buscar/boleto/'.$nosso_numero, []);

            if($response->status() === 200){

                return $response->json();

            }

        }

    }