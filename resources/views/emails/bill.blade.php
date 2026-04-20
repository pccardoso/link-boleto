<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Boletos - {{ count($listBoletos) }} boleto(s)</title>
</head>

<body style="margin:0; padding:30px; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#333;">

    <div style="max-width:1100px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 14px rgba(0,0,0,0.08);">

        <!-- HEADER -->
        <div style="background:#1f2937; padding:24px 30px;">

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="left" valign="middle">
                        <img 
                            src="https://s3-database.mundoevogard.com/logos/evogard.png" 
                            alt="Evogard"
                            style="height:60px; display:block;"
                        >
                    </td>

                    <td align="right" valign="middle">
                        <div>
                            <h1 style="margin:0; font-size:24px; color:#ffffff;">
                                Relatório de Baixa de Boletos
                            </h1>

                            <p style="margin:8px 0 0; color:#d1d5db; font-size:14px;">
                                Gerado automaticamente em {{ now()->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

        </div>

        <!-- CONTENT -->
        <div style="padding:30px;">

            <!-- RESUMO -->
            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:18px; margin-bottom:25px;">

                <p style="margin:0 0 10px; font-size:15px;">
                    <strong>Relatório do dia:</strong>
                    {{ \Carbon\Carbon::today()->format('d/m/Y') }}
                </p>

                <p style="margin:0 0 10px; font-size:15px;">
                    <strong>Total de boletos:</strong>
                    {{ count($listBoletos) }}
                </p>

                <p style="margin:0; font-size:14px; color:#6b7280; line-height:1.5;">
                    <strong>Atenção:</strong>
                    Este relatório refere-se à verificação realizada entre
                    <strong>01:00 e 06:00</strong> do dia
                    {{ \Carbon\Carbon::today()->format('d/m/Y') }}.
                </p>

            </div>

            <!-- TABELA -->
            <table style="width:100%; border-collapse:collapse; font-size:14px;">

                <thead>
                    <tr style="background:#f3f4f6; text-align:left;">
                        <th style="padding:12px; border:1px solid #e5e7eb;">ID</th>
                        <th style="padding:12px; border:1px solid #e5e7eb;">Nosso Número</th>
                        <th style="padding:12px; border:1px solid #e5e7eb;">Associado</th>
                        <th style="padding:12px; border:1px solid #e5e7eb;">Placa</th>
                        <th style="padding:12px; border:1px solid #e5e7eb;">CPF/CNPJ</th>
                        <th style="padding:12px; border:1px solid #e5e7eb;">Valor Pago</th>
                        <th style="padding:12px; border:1px solid #e5e7eb;">Data Pagamento</th>
                        <th style="padding:12px; border:1px solid #e5e7eb;">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($listBoletos as $boleto)
                        <tr style="background:#ffffff;">
                            <td style="padding:10px; border:1px solid #e5e7eb;">{{ $boleto->id }}</td>

                            <td style="padding:10px; border:1px solid #e5e7eb;">
                                {{ $boleto->nosso_numero }}
                            </td>

                            <td style="padding:10px; border:1px solid #e5e7eb;">
                                {{ $boleto->associado }}
                            </td>

                            <td style="padding:10px; border:1px solid #e5e7eb;">
                                {{ $boleto->plate }}
                            </td>

                            <td style="padding:10px; border:1px solid #e5e7eb;">
                                {{ $boleto->cpf_cnpj }}
                            </td>

                            <td style="padding:10px; border:1px solid #e5e7eb;">
                                R$ {{ number_format($boleto->valor_pagamento ?? 0, 2, ',', '.') }}
                            </td>

                            <td style="padding:10px; border:1px solid #e5e7eb;">
                                {{ $boleto->data_pagamento }}
                            </td>

                            <td style="padding:10px; border:1px solid #e5e7eb;">
                                {{ $boleto->descricao_situacao_boleto }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

        <!-- FOOTER -->
        <div style="padding:20px 30px; background:#f9fafb; border-top:1px solid #e5e7eb; font-size:12px; color:#6b7280;">
            Este e-mail foi enviado automaticamente pelo sistema Evogard.
        </div>

    </div>

</body>

</html>