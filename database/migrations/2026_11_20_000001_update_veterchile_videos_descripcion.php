<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Descripciones ampliadas de los videos de Veterchile por público: qué es, "Idea clave" y "Por dónde partir".
// Los párrafos se separan con una línea en blanco (ver la descripción en vender/videos.blade.php).
return new class extends Migration {
    public const NUEVAS = [
        'videos/presentacion-veterchile-tutores.mp4' => [
            'Veterchile funciona como el espacio donde el tutor reúne la salud y el cuidado de su mascota: historial completo, documentos disponibles 24/7, agenda de horas, veterinarios de confianza y seguimiento de enfermedades crónicas. También incluye datos genealógicos, una sección de mascotas perdidas, aplicación móvil para autorizar y firmar documentos, transferencia de mascota en caso de adopción e invitación a co-tutores o cuidadores.',
            'Idea clave: el tutor no compra funciones, compra tranquilidad. Veterchile reemplaza los papeles sueltos y la memoria por una ficha ordenada que el veterinario también puede ver. Explícalo así para que el cliente piense en el día en que necesite esa información con urgencia.',
            'Por dónde partir: pregunta cuántas mascotas tiene, si alguna está en tratamiento y quién más la cuida. Con esa respuesta eliges las 3 o 4 secciones que mejor le sirven.',
        ],
        'videos/presentacion-veterchile-profesionales.mp4' => [
            'Veterchile funciona como el escritorio del profesional: agenda inteligente con recordatorios por correo o WhatsApp, donde el tutor puede pagar en línea o decidir pagar en la consulta, fichas clínicas por especialidad con alertas, notas y consentimientos integrados, historial clínico completo y documentos personalizables (consentimientos, indicaciones, recetas de fármacos y órdenes de exámenes). Incluye seguimiento de enfermedades crónicas y aplicación móvil para ver la agenda rápidamente y autorizar o firmar documentos.',
            'Idea clave: el profesional compra tiempo y orden. Cada herramienta debe conectarse con su día, sus citas, el seguimiento de las mascotas y la información de los tutores. Explícalo así para que no parezca un sistema más que aprender.',
            'Por dónde partir: pregunta cuántos pacientes faltan a su hora y cuánto tiempo gasta en recetas y documentos. Esas dos respuestas suelen abrir la venta.',
        ],
        'videos/presentacion-veterchile-asistente-online.mp4' => [
            'Veterchile permite que la asistente gestione la agenda del profesional: agendar, cancelar y anular horas, y administrar según su modalidad de trabajo, presencial u online. Una misma asistente puede trabajar para distintos empleadores, ya sea un solo profesional o un centro veterinario.',
            'Idea clave: este perfil usa la herramienta todos los días, pero quien decide la compra suele ser el profesional o el centro. Explícalo mostrando que la asistente puede trabajar desde cualquier lugar y que el empleador conserva el control de su agenda.',
            'Por dónde partir: pregunta si el cliente es una asistente o quien la contrata, y desde dónde se gestiona hoy la agenda. Confirma antes el alcance de los permisos administrativos para no prometer de más.',
        ],
        'videos/presentacion-veterchile-centros-veterinarios.mp4' => [
            'Veterchile funciona como la base de operación del centro: agenda compartida que coordina profesionales, asistentes y tutores, organizada por especialidades y con disponibilidad en tiempo real. Suma gestión administrativa integrada, con pagos, estadísticas, control de inventario y reportes. Si el centro lo necesita, puede incorporar módulos adicionales como hospitalización, peluquería, hotel o cementerio.',
            'Idea clave: el centro compra orden y control sin complicaciones. Se parte por lo más urgente, normalmente la agenda, y se suma el resto a medida que el equipo se adapta. Explícalo así para que el cambio no se perciba como una carga.',
            'Por dónde partir: pregunta quién agenda hoy, cómo se coordinan los profesionales y cómo revisa el dueño cómo va el negocio.',
        ],
        'videos/presentacion-veterchile-clinicas.mp4' => [
            'Veterchile funciona como el sistema común de una clínica con varios profesionales y especialidades: agenda compartida con disponibilidad en tiempo real y gestión administrativa integrada con pagos, estadísticas, inventario y reportes. Si la clínica hospitaliza mascotas, puede sumar el módulo de mascotas hospitalizadas.',
            'Idea clave: a mayor equipo, mayor es el costo de coordinarse mal. La clínica compra que todos vean la misma información y que la dirección pueda revisar la operación sin armar informes a mano. Explícalo así para que el cliente piense en horas que se pisan y planillas separadas.',
            'Por dónde partir: pregunta cuántos profesionales y especialidades hay, y cuántos sistemas o planillas usan hoy para agenda, pagos e inventario.',
        ],
        'videos/presentacion-veterchile-hospitales.mp4' => [
            'Veterchile funciona como el sistema donde el hospital organiza su operación y el seguimiento de pacientes internados. Con el módulo de mascotas hospitalizadas, el equipo organiza el seguimiento hospitalario junto a los antecedentes y documentos de la mascota. Se complementa con agenda compartida y gestión administrativa integrada.',
            'Idea clave: el hospital compra continuidad, es decir, que cada integrante del equipo tenga a mano los antecedentes del paciente internado. Explícalo así para que el módulo se vea como parte del mismo sistema y no como un registro aparte. Confirma antes si el módulo de hospitalización está incluido en el plan o tiene costo adicional.',
            'Por dónde partir: pregunta cómo llevan hoy el seguimiento de los pacientes internados y dónde guardan sus antecedentes y documentos.',
        ],
        'videos/presentacion-veterchile-laboratorios.mp4' => [
            'Veterchile funciona como el espacio donde el laboratorio organiza sus horas y deja los resultados al alcance de quienes los necesitan. Incluye agenda compartida y gestión administrativa integrada. Con resultados disponibles, los exámenes e informes pasan a formar parte de los documentos consultables de la mascota, lo que facilita encontrar los antecedentes para el próximo control veterinario.',
            'Idea clave: el laboratorio compra una mejor relación con los veterinarios y tutores, que encuentran sus resultados sin llamar ni pedir copias. Explícalo así para que el cliente vea el beneficio también en su propio equipo, con menos consultas repetitivas.',
            'Por dónde partir: pregunta cuántas veces al día le piden un resultado que ya entregó y cómo los entrega hoy (papel, correo o WhatsApp).',
        ],
    ];

    private const ANTERIORES = [
        'videos/presentacion-veterchile-tutores.mp4' => 'Ficha Veterinaria Única, documentos clínicos y reserva de horas.',
        'videos/presentacion-veterchile-profesionales.mp4' => 'Agenda, fichas por especialidad y recetas u órdenes electrónicas.',
        'videos/presentacion-veterchile-asistente-online.mp4' => 'Coordinación de horas y manejo de las agendas vinculadas.',
        'videos/presentacion-veterchile-centros-veterinarios.mp4' => 'Agendas centralizadas, recepción y coordinación de profesionales.',
        'videos/presentacion-veterchile-clinicas.mp4' => 'Agenda, fichas por especialidad y continuidad de los controles.',
        'videos/presentacion-veterchile-hospitales.mp4' => 'Historial, documentos y coordinación entre equipos en atenciones complejas.',
        'videos/presentacion-veterchile-laboratorios.mp4' => 'Órdenes de exámenes, carga de resultados y consulta de documentos.',
    ];

    public function up(): void
    {
        foreach (self::NUEVAS as $video => $parrafos) {
            DB::table('videos_capacitacion')->where('video', $video)
                ->update(['descripcion' => implode("\n\n", $parrafos), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        foreach (self::ANTERIORES as $video => $descripcion) {
            DB::table('videos_capacitacion')->where('video', $video)
                ->update(['descripcion' => $descripcion, 'updated_at' => now()]);
        }
    }
};
