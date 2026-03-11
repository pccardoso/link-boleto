<?php

    namespace App\Services;

    use Illuminate\Support\Facades\Http;
    use Carbon\Carbon;

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
            
        }

        public function listBoletOfUser($cpfCnpjClient) {

            $hoje = Carbon::today();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                'Accept' => 'application/json',
            ])->post('https://api.hinova.com.br/api/sga/v2/listar/boleto/periodo/', [
                'cpf_associado' => $cpfCnpjClient,
                "data_vencimento_original_inicial" => $hoje->copy()->subDays(30)->format('d/m/Y'),
                "data_vencimento_original_final"   => $hoje->copy()->addDays(30)->format('d/m/Y'),
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
                "data_vencimento_original_inicial" => $hoje->copy()->subDays(30)->format('d/m/Y'),
                "data_vencimento_original_final"   => $hoje->copy()->addDays(30)->format('d/m/Y'),
            ]);

            if($response->status() === 200 && $response != null){

                return $response->json();

            }

        }

        public function updateMaturity($codigoBolet){

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TOKEN_SGA'),
                'Accept' => 'application/json',
            ])->post('https://api.hinova.com.br/api/sga/v2/alterar/vencimento-boleto', [
                "nosso_numero" => $codigoBolet,
                "nova_data_vencimento" => Carbon::now()->format('d/m/Y')
            ]);

            if($response->status() === 200){

                return $response->json();

            }

        }

    }