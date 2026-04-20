<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Boletos</title>
</head>

<body style="font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px;">

    <div style="background: #fff; padding: 20px; border-radius: 8px;">

        <h2 style="color: #333; margin-bottom: 20px;">
            Boletos pagos no período
        </h2>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background: #f0f0f0;">
                    <th style="border: 1px solid #ddd; padding: 10px;">ID</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Nosso Número</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Associado</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Placa</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">CPF/CNPJ</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Valor Pago</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Data Pagamento</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($listBoletos as $boleto)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $boleto->id }}
                        </td>

                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $boleto->nosso_numero }}
                        </td>

                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $boleto->associado }}
                        </td>

                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $boleto->plate }}
                        </td>

                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $boleto->cpf_cnpj }}
                        </td>

                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ number_format($boleto->valor_pagamento ?? 0, 2, ',', '.') }}
                        </td>

                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $boleto->data_pagamento }}
                        </td>

                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $boleto->descricao_situacao_boleto }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>
</html>