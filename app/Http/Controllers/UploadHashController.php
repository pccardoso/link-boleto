<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadHash;

class UploadHashController extends Controller
{

    public function listUploadHash($id_hash)
    {
        $uploadHash = UploadHash::where('hash_plate_id', $id_hash)->get();

        if (!$uploadHash) {
            return response()->json(['message' => 'Nenhum anexo encontrado!'], 404);
        }

        return response()->json([
            'message' => 'Anexos encontrados com sucesso!',
            'data' => $uploadHash
        ], 200);

    }

}
