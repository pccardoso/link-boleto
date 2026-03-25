<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HashPlate;
use App\Models\UploadHash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SyncOldUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-old-uploads';

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
        Log::info('Iniciando sincronização de uploads antigos...');

        $listHas = HashPlate::orderBy('id')->offset(0)->limit(10)->get();

        foreach ($listHas as $hash){

            $folder = $hash->plate;

            Log::info("Verificando pasta: {$folder}");

            if (!Storage::disk('s3')->exists($folder)) {
                $this->warn("Pasta não existe: {$folder}");
                continue;
            }

            $files = Storage::disk('s3')->files($folder);
            $count = count($files);

            if ($count === 1){

                UploadHash::firstOrCreate([
                    'path' => $files[0],
                    'hash_plate_id' => $hash->id
                ]);

                Log::info("Upload registrado: {$files[0]}");

            } else {

                $this->warn("Pasta {$folder} contém {$count} arquivos");

            }

        }

        $this->info("Processados {$listHas->count()} registros.");
    }
}
