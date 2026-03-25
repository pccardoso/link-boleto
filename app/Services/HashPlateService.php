<?php

    namespace App\Services;

    use App\Models\HashPlate;
    use App\Models\UploadHash;
    use App\Helpers\HashHelper;
    use Carbon\Carbon;
    use App\Controllers\Requests\UploadFileRequest;
    use Illuminate\Support\Facades\Storage;

    class HashPlateService{

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

    }