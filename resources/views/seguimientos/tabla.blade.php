<div class="table-responsive">
    <table class="table">
        <thead><tr><th>Cliente</th><th>Canal y referencia</th><th>Responsable</th><th>Seguimiento</th></tr></thead>
        <tbody>
        @forelse($seguimientos as $registro)
            <tr>
                <td><a href="{{ route('clientes.contacto', $registro->cliente_id) }}">{{ $registro->cliente->nombre }} {{ $registro->cliente->primer_apellido }}</a></td>
                <td>
                    {{ $registro->canal === 'whatsapp' ? 'WhatsApp' : 'Correo electrónico' }}
                    @if($registro->referencia)<small class="d-block">{{ $registro->referencia }}</small>@endif
                    <small class="d-block text-muted">{{ $registro->created_at->format('d-m-Y H:i') }}</small>
                </td>
                <td>{{ $registro->responsable?->name ?? 'Sin responsable' }}</td>
                <td>@include('seguimientos.estado', ['registro' => $registro])</td>
            </tr>
        @empty
            <tr><td colspan="4">Sin seguimientos para mostrar.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $seguimientos->links('pagination::bootstrap-5') }}
