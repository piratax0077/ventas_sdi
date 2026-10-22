<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $venta->id }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', monospace;
            background: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .acciones {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .acciones button, .acciones a {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-pdf { background: #dc3545; color: white; }
        .btn-print { background: #0d6efd; color: white; }
        .btn-volver { background: #6c757d; color: white; }

        #ticket {
            background: white;
            width: 300px;
            padding: 20px;
            border: 1px dashed #999;
        }

        .ticket-empresa {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .ticket-subtitulo {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-bottom: 10px;
        }

        .ticket-separador {
            border: none;
            border-top: 1px dashed #999;
            margin: 10px 0;
        }

        .ticket-fila {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .ticket-label {
            font-size: 11px;
            color: #666;
        }

        .ticket-productos table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }

        .ticket-productos th {
            border-bottom: 1px solid #ccc;
            padding: 3px 0;
            text-align: left;
            font-size: 11px;
        }

        .ticket-productos td {
            padding: 4px 0;
            vertical-align: top;
        }

        .ticket-productos td:last-child {
            text-align: right;
        }

        .ticket-total {
            text-align: right;
            font-size: 15px;
            font-weight: bold;
            margin-top: 5px;
        }

        .ticket-pie {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 10px;
        }

        @media print {
            body { background: white; padding: 0; }
            .acciones { display: none; }
            #ticket { border: none; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="acciones">
        <button class="btn-pdf" onclick="descargarPdf()">
            ⬇ Descargar PDF
        </button>
        <button class="btn-print" onclick="window.print()">
            🖨 Imprimir
        </button>
        <a href="{{ route('ventas.index') }}" class="btn-volver">
            ← Volver
        </a>
    </div>

    <div id="ticket">
        <div class="ticket-empresa">{{ env('APP_NAME') }}</div>
        <div class="ticket-subtitulo">Ticket de Venta</div>

        <hr class="ticket-separador">

        <div class="ticket-fila">
            <span class="ticket-label">N° Ticket:</span>
            <span><strong>#{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</strong></span>
        </div>
        <div class="ticket-fila">
            <span class="ticket-label">Fecha:</span>
            <span>{{ $venta->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="ticket-fila">
            <span class="ticket-label">Cliente:</span>
            <span>{{ $venta->cliente->nombre }} {{ $venta->cliente->primer_apellido }}</span>
        </div>

        <hr class="ticket-separador">

        <div class="ticket-productos">
            <table>
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th style="text-align:center">Cant.</th>
                        <th style="text-align:right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venta->productos as $producto)
                        @php $subtotal = $producto->cantidad * $producto->precio; @endphp
                        <tr>
                            <td>{{ $producto->descripcion }}</td>
                            <td style="text-align:center">{{ $producto->cantidad }}</td>
                            <td style="text-align:right">${{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="ticket-separador">

        <div class="ticket-total">
            TOTAL: ${{ number_format($total, 2) }}
        </div>

        <hr class="ticket-separador">

        <div class="ticket-pie">
            ¡Gracias por su compra!<br>
            {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <script>
        function descargarPdf() {
            const elemento = document.getElementById('ticket');
            const opciones = {
                margin: 5,
                filename: 'ticket-{{ $venta->id }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a6', orientation: 'portrait' }
            };
            html2pdf().set(opciones).from(elemento).save();
        }
    </script>
</body>
</html>
