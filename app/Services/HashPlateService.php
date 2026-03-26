<?php

    namespace App\Services;

    use App\Models\HashPlate;
    use App\Models\UploadHash;
    use App\Helpers\HashHelper;
    use Carbon\Carbon;
    use App\Controllers\Requests\UploadFileRequest;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\DB;

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

        public function uploadMovie($data){

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

            return $uploadPath;

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