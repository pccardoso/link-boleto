<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    
    protected $table = "bill_update";

    protected $fillable = [
        'id',
        'codigo_boleto',
        'nosso_numero',
        'cpf_cnpj',
        'associado',
        'linha_digitavel',
        'link_boleto',
        'nova_data_vencimento',
        'hash_plate_id'
    ];

}
