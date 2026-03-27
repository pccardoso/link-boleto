<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BillService;

class BillController extends Controller
{

    public function __construct(
        private BillService $billService 
    )
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        $billList = $this->billService->getAll();

        if($billList){

            return response()->json([
                "message" => "Lista de boletos obtida com sucesso!",
                "data" => $billList,
                "status" => 200
            ], 200);

        }

        return response()->json([
            "message" => "Nenhum boleto encontrado!",
            "data" => [],
            "status" => 404
        ], 404);

    }

    public function billsPorMes()
    {
        return $this->billService->billsPorMes();
    }

    public function valorPorMes()
    {
        return $this->billService->valorPorMes();
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
