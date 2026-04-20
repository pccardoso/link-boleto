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
        'valor_boleto',
        'status',
        'hash_plate_id',
        'descricao_situacao_boleto',
        'codigo_situacao_boleto',
        'need_validate',
        'data_pagamento',
        'valor_pagamento',
        'verified_paid_at',
        'plate'
    ];

}
