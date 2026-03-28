<?php

    namespace App\Services;

    use App\Models\HashPlate;
    use App\Models\UploadHash;
    use App\Helpers\HashHelper;
    use Carbon\Carbon;
    use App\Controllers\Requests\UploadFileRequest;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class HashPlateService{


        public function hashesPorMes()
        {
            $anoAtual = now()->year;

            $hashes = HashPlate::select(
                    DB::raw('EXTRACT(MONTH FROM created_at) as mes'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [$anoAtual])
                ->groupBy('mes')
                ->pluck('total', 'mes');

            $meses = [
                1 => 'JAN',
                2 => 'FEV',
                3 => 'MAR',
                4 => 'ABR',
                5 => 'MAI',
                6 => 'JUN',
                7 => 'JUL',
                8 => 'AGO',
                9 => 'SET',
                10 => 'OUT',
                11 => 'NOV',
                12 => 'DEZ'
            ];

            $labels = [];
            $values = [];

            foreach ($meses as $numero => $nome) {
                $labels[] = $nome;
                $values[] = $hashes[$numero] ?? 0;
            }

            return response()->json([
                'labels' => $labels,
                'values' => $values,
                'year' => $anoAtual
            ]);
        }


        public function hashesPorMesComUpload()
        {
            $anoAtual = now()->year;

            // HASHES CRIADAS
            $hashes = HashPlate::select(
                    DB::raw('EXTRACT(MONTH FROM created_at) as mes'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('created_at', $anoAtual)
                ->groupBy('mes')
                ->pluck('total', 'mes');

            // HASHES QUE POSSUEM UPLOAD
            $hashesComUpload = HashPlate::whereHas('upload')
                ->select(
                    DB::raw('EXTRACT(MONTH FROM created_at) as mes'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('created_at', $anoAtual)
                ->groupBy('mes')
                ->pluck('total', 'mes');

            $meses = [
                1 => 'JAN',
                2 => 'FEV',
                3 => 'MAR',
                4 => 'ABR',
                5 => 'MAI',
                6 => 'JUN',
                7 => 'JUL',
                8 => 'AGO',
                9 => 'SET',
                10 => 'OUT',
                11 => 'NOV',
                12 => 'DEZ'
            ];

            $labels = [];
            $hashValues = [];
            $uploadValues = [];

            foreach ($meses as $numero => $nome) {
                $labels[] = $nome;

                $hashValues[] = $hashes[$numero] ?? 0;
                $uploadValues[] = $hashesComUpload[$numero] ?? 0;
            }

            return response()->json([
                'labels' => $labels,
                'series' => [
                    [
                        'name' => 'Hashes Criadas',
                        'data' => $hashValues
                    ],
                    [
                        'name' => 'Hashes com Upload',
                        'data' => $uploadValues
                    ]
                ],
                'year' => $anoAtual
            ]);
        }

        public function uploadMovie($data){

            try{

                

                $hashGet = HashPlate::where('plate', $data['plate'])
                            ->whereDate('created_at', Carbon::today())
                            ->first();

                if(!$hashGet){

                    return response()->json([
                        "message" => "O código solicitado não está mais válido!",
                        "status" => 422,
                        "data" => null
                    ], 422);

                }

                Log::info('Iniciando upload de vídeo', [
                    'parametro' => $data
                ]);

                $date = Carbon::now()->format('d-m-Y H:i');

                $fileName = '['.$date.'] - '.$data['hash'].'.'.$data['moovie']->getClientOriginalExtension();

                $uploadPath = Storage::disk('s3')->putFileAs(
                    $data['plate'],
                    $data['moovie'],
                    $fileName
                );

                if($uploadPath){

                    UploadHash::create([
                        "path" => $uploadPath,
                        "hash_plate_id" => $hashGet->id
                    ]);

                }

                Log::info('Upload de vídeo realizado com sucesso', [
                    'parametro' => $data,
                    'upload_path' => $uploadPath
                ]);

                return $uploadPath;

            }catch(\Exception $e){

                Log::error('Erro ao enviar upload', [
                    'parametro' => $data,
                    'mensagem' => $e->getMessage(),
                    'linha' => $e->getLine(),
                    'arquivo' => $e->getFile(),
                ]);

                return response()->json([
                    'erro' => 'Não foi possível enviar o upload.'
                ], 500);

            }

        }

        public function create($data){

            $hashGet = HashPlate::where('plate', $data['plate'])
                        ->whereDate('created_at', Carbon::today())
                        ->first();

            if($hashGet) return $hashGet;

            $hashCreate = HashPlate::create([
                ...$data,
                "hash" => HashHelper::generate()
            ]);

            return $hashCreate;

        }

        public function getAll(){

            $hashList = HashPlate::with('upload')->get();

            return $hashList;

        }

    }