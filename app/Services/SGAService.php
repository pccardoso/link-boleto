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

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                'Accept' => 'application/json',
            ])->post('https://api.hinova.com.br/api/sga/v2/alterar/vencimento-boleto', [
                "nosso_numero" => $codigoBolet['nosso_numero'],
                "nova_data_vencimento" => Carbon::now()->addDays(1)->format('d/m/Y') //Carbon::now()->format('d/m/Y')
            ]);

            if($response->status() === 200){

                //BOLETO ATUALIZADO PORTANTO GERAR BILLET PARA O BOLETO ATUALIZADO

                Bill::create([
                    "codigo_boleto" => data_get($codigoBolet, 'codigo_boleto', 0),
                    "nosso_numero" => data_get($codigoBolet, 'nosso_numero', 0),
                    "nova_data_vencimento" => Carbon::now()->addDays(1)->format('Y-m-d'),
                    "cpf_cnpj" => data_get($codigoBolet, 'cpf', 'Não Identificado'),
                    "associado" => data_get($codigoBolet, 'nome_associado', 'Não Identificado'),
                    "linha_digitavel" => data_get($codigoBolet, 'linha_digitavel', 'Não Identificado'),
                    "link_boleto" => data_get($codigoBolet, 'link_boleto', 'Não Identificado'),
                    "valor_boleto" => floatval(data_get($codigoBolet, 'valor_boleto', 0))
                ]);

                //BUSCAR O CÓDIGO DO BOLETO PELO NOSSO_NUMERO

                $responseGetCode = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                    'Accept' => 'application/json',
                ])->post('https://api.hinova.com.br/api/sga/v2/processa-pdf/boleto', [
                    "nosso_numero" => [$codigoBolet['nosso_numero']]
                ]);

                if($responseGetCode->status() === 200 && $responseGetCode != null){

                    $dataBolet = $responseGetCode->json();

                    $responseMsgBolet = Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                        'Accept' => 'application/json',
                    ])->post('https://api.hinova.com.br/api/sga/v2/boleto/manutencao', [
                        "codigo_boleto" => $dataBolet[0]['codigo'],
                        "mensagem_desconto" => "Y"
                    ]);

                    if($responseMsgBolet->status() === 207){

                        return $response->json();

                    }

                }


                /*$response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                    'Accept' => 'application/json',
                ])->post('https://api.hinova.com.br/api/sga/v2/boleto/manutencao', [
                    "codigo_boleto" => $codigoBolet['codigo_boleto'],
                    "mensagem_desconto" => "Y"
                ]);*/

            }

            return $response->json();

        }

    }