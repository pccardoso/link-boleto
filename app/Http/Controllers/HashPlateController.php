<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreHashPlateRequest;
use App\Http\Requests\UploadFileRequest;
use App\Services\HashPlateService;
use App\Services\SGAService;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HashPlateController extends Controller
{

    public function __construct(
        protected HashPlateService $hashService,
        protected SGAService $sgaService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        $hashList = $this->hashService->getAll();

        if($hashList){

            return response()->json([
                "message" => "Lista de hash plates obtida com sucesso!",
                "data" => $hashList,
                "status" => 200
            ], 200);

        }

        return response()->json([
            "message" => "Nenhuma hash plate encontrada!",
            "data" => [],
            "status" => 404
        ], 404);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHashPlateRequest $request)
    {
        
        $hashCreate = $this->hashService->create($request->validated());
        
        if($hashCreate){

            return response()->json([
                "message" => "Hash criada com sucesso!",
                "data" => $hashCreate,
                "status" => 200
            ], 200);

        }

    }

    public function uploadMoovie(UploadFileRequest $request){

        $returnFileUpload = $this->hashService->uploadMovie($request->validated());

        if($returnFileUpload){

            $boletUpdate = $this->sgaService->updateMaturity($request->input('nosso_numero'));

            return response()->json([
                "message" => "Vídeo da vistoria foi enviado com sucesso",
                "data" => [
                    "path_upload" => $returnFileUpload,
                    "boleto" => $boletUpdate
                ],
                "status" => 200
            ], 200);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
