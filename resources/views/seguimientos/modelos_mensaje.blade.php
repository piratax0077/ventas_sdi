@php
    $primerNombre = \Illuminate\Support\Str::of($cliente->nombre)->ltrim(" *\t\n\r")->before(' ')->title()->toString();
    $saludo = preg_match('/^[\p{L}\p{M}-]+$/u', $primerNombre) ? "Hola, $primerNombre." : 'Hola.';
    $invitacion = <<<TEXTO
    $saludo Soy [tu nombre], del equipo de Medichile.

    Queremos invitarte a conocer la Ficha Médica Única, un espacio digital donde puedes consultar tus documentos de salud, recetas y controles desde un solo lugar. También contamos con opciones de seguimiento para el embarazo y el control de niño sano.

    Si te interesa, puedo mostrarte cómo funciona en una demostración breve y responder tus preguntas. ¿Te gustaría que coordinemos una?

    Saludos,
    [Tu nombre] · Equipo Medichile
    TEXTO;
@endphp

<section class="card card-body mb-3">
    <h2 class="h5">Modelos de mensaje</h2>
    <div class="mb-3">
        <label for="modelo-contacto">Modelo</label>
        <select id="modelo-contacto" class="form-select" data-no-select2>
            <option value="">Seleccionar modelo</option>
            <option value="invitacion">Invitación a conocer Medichile</option>
        </select>
    </div>
    @if($correo)
        <div class="mb-3">
            <label for="asunto-contacto">Asunto del correo</label>
            <input id="asunto-contacto" type="text" class="form-control" maxlength="120">
        </div>
    @endif
    <div>
        <label for="mensaje-contacto">Mensaje</label>
        <textarea id="mensaje-contacto" class="form-control" rows="8"></textarea>
    </div>
</section>

@push('scripts')
<script>
    (() => {
        const modelo = document.getElementById('modelo-contacto');
        const asunto = document.getElementById('asunto-contacto');
        const mensaje = document.getElementById('mensaje-contacto');
        const correo = document.getElementById('contacto-correo');
        const whatsapp = document.getElementById('contacto-whatsapp');
        const correoBase = correo?.getAttribute('href');
        const whatsappBase = whatsapp?.getAttribute('href');

        function actualizarEnlaces() {
            const texto = encodeURIComponent(mensaje.value);
            if (correo) {
                const titulo = encodeURIComponent(asunto.value.trim());
                correo.href = titulo || texto ? correoBase + '?subject=' + titulo + '&body=' + texto : correoBase;
            }
            if (whatsapp) {
                whatsapp.href = texto ? whatsappBase + '?text=' + texto : whatsappBase;
            }
        }

        modelo.addEventListener('change', () => {
            if (modelo.value === 'invitacion') {
                if (asunto) asunto.value = 'Te invitamos a conocer Medichile';
                mensaje.value = @js($invitacion);
            }
            actualizarEnlaces();
        });

        asunto?.addEventListener('input', actualizarEnlaces);
        mensaje.addEventListener('input', actualizarEnlaces);
    })();
</script>
@endpush
