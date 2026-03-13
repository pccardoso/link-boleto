<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\SGAService;

class SGAController extends Controller
{

    public function __construct (
        protected SGAService $serviceSGA
    ){}

    public function consultPlatesUser($cpfOrCnpj){

        $plate = $this->serviceSGA->searchPlateMember($cpfOrCnpj);

        if(!$plate){
            return response()->json([
                "message" => "Nenhuma placa encontrada",
                "status" => 404,
                "data" => []
            ], 404);
        }

        return response()->json([
            "message" => "Placas encontradas",
            "status" => 200,
            "data" => $plate
        ], 200);

    }

    public function consultBoletUser($cpfOrCnpj){

        $bolet = $this->serviceSGA->listBoletOfUser($cpfOrCnpj);

        return response()->json([
            "message" => "Boletos encontradas",
            "status" => 200,
            "data" => $bolet
        ], 200);

    }

    public function consultBoletPlate($plate){

        $bolet = $this->serviceSGA->listBoletoOfPlate($plate);

        if(!$bolet){
            return response()->json([
                "message" => "Nenhum boleto encontrado para a placa informada!",
                "status" => 404,
                "data" => []
            ], 404);
        }

        return response()->json([
            "message" => "Boletos encontradas",
            "status" => 200,
            "data" => $bolet
        ], 200);

    }

    public function updateBolet($codigoBoleto){

        $responseUpdate = $this->serviceSGA->updateMaturity($codigoBoleto);

        return response()->json([
            "message" => "Boletos atualizado",
            "status" => 200,
            "data" => $responseUpdate
        ], 200);

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->serviceSGA->searchPlateMember('07031486300'));
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
    public function store(Request $request)
    {
        //
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
