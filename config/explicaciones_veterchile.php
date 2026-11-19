<?php

$tutor = [
    'titulo' => 'Para el tutor · Todo sobre su mascota, siempre a mano',
    'introduccion' => 'Presenta la Ficha Veterinaria Única como un lugar para organizar el cuidado de cada mascota y preparar sus próximas atenciones.',
    'funciones' => [
        ['Historial completo de su mascota', 'Reúne identificación, fecha de nacimiento, peso, raza, número de chip, enfermedades crónicas, vacunas, desparasitaciones, últimos controles, tratamientos activos, alimentación e historial de atenciones.', 'Tener los antecedentes a mano facilita preparar una atención o responder ante una emergencia, con menos dependencia de la memoria y de los papeles.'],
        ['Datos genealógicos', 'Registra los cruces y las camadas de la mascota.', 'Permite conocer su origen y su descendencia desde su ficha.'],
        ['Mis veterinarios preferidos', 'Mantiene el historial de los veterinarios que han atendido a la mascota y permite agendar directamente desde el escritorio.', 'Facilita volver a contactar a un profesional conocido y consultar sus horas disponibles.'],
        ['Agenda e información de cada mascota', 'El tutor puede agendar horas y consultar la información de cada una de sus mascotas desde un mismo lugar.', 'Ayuda a organizar el cuidado cuando una familia tiene varias mascotas.'],
        ['Compras y entregas periódicas', 'Permite agendar y gestionar entregas mensuales o periódicas de alimento, fármacos y otros productos.', 'Ayuda a anticipar las reposiciones y organizar lo que la mascota necesita.'],
        ['Documentos disponibles 24/7', 'Acceso desde distintos dispositivos a resultados de exámenes, consentimientos, certificados, recetas e informes disponibles en su cuenta.', 'Facilita encontrar los documentos para la próxima atención y reduce la dependencia de copias en papel.'],
        ['Seguimiento de enfermedades crónicas', 'Reúne controles, información para el tutor, recetas y especialistas.', 'Ayuda a mantener el seguimiento organizado y a acompañar al tutor durante el tratamiento.'],
        ['Mascotas perdidas', 'Permite publicar mascotas perdidas o encontradas que pertenecen a la red y establecer contacto con su tutor.', 'Facilita la comunicación para favorecer un reencuentro.'],
        ['Aplicación móvil', 'Permite autorizar y firmar documentos desde el celular.', 'Facilita realizar las autorizaciones desde donde se encuentra el tutor.'],
        ['Transferencia de mascota', 'Al dar una mascota en adopción, el tutor puede transferir sus datos a otro usuario, incluidos vacunas, tratamientos, controles y documentos.', 'El nuevo tutor recibe los antecedentes para continuar el cuidado con su historial disponible.'],
        ['Co-tutores y cuidadores', 'El tutor puede invitar a familiares, cuidadores o paseadores a ver y entregar información. Solo el tutor responsable puede modificar la información o eliminar la mascota.', 'Permite colaborar en el cuidado manteniendo las decisiones sobre la ficha en manos del tutor responsable.'],
    ],
];
$profesional = [
    'titulo' => 'Para el médico veterinario · Una consulta más ordenada y eficiente',
    'introduccion' => 'Relaciona cada herramienta con una tarea cotidiana del profesional, desde coordinar la cita hasta continuar el seguimiento.',
    'funciones' => [
        ['Agenda inteligente', 'Integrada al escritorio, con acceso desde computador, tablet o celular. Incluye programación automatizada, recordatorios por correo o WhatsApp y disponibilidad en tiempo real.', 'Los recordatorios ayudan a reducir ausencias y a aprovechar mejor el tiempo de consulta.'],
        ['Ficha clínica por especialidad', 'Fichas diseñadas para distintas especialidades, con alertas, notas y consentimientos integrados.', 'Facilita registrar, consultar y gestionar la información con el contexto de la especialidad.'],
        ['Historial clínico completo', 'Reúne antecedentes, controles, tratamientos y evolución a lo largo del tiempo.', 'Permite revisar la información disponible junto al tutor durante controles habituales o urgencias.'],
        ['Documentos a su medida', 'Personaliza consentimientos, indicaciones, recetas de fármacos y órdenes de exámenes con el contenido de la consulta.', 'Ayuda a preparar documentos que reflejen el trabajo y las necesidades del profesional.'],
        ['Seguimiento de enfermedades crónicas', 'Un espacio para controles, recetas, información al tutor y derivación a especialistas.', 'Facilita organizar la continuidad del tratamiento y la coordinación con el tutor.'],
        ['Aplicación móvil', 'Centraliza las citas, autorizaciones y horas en el celular.', 'El profesional puede revisar su jornada y atender coordinaciones desde su teléfono.'],
        ['Módulos adicionales', 'Opciones para gestionar mascotas hospitalizadas, centros de peluquería, hoteles para mascotas y cementerios.', 'Cada negocio puede elegir el módulo que necesita dentro de la plataforma. Revisa su inclusión al presentar el plan.'],
    ],
];
$institucion = [
    'titulo' => 'Para centros, asistentes e instituciones · Administración sin complicaciones',
    'introduccion' => 'Muestra cómo la coordinación de la agenda y la administración apoyan el trabajo diario de cada equipo.',
    'funciones' => [
        ['Agenda compartida', 'Coordina profesionales, asistentes, tutores e instituciones desde distintos dispositivos, organizada por especialidades y con disponibilidad en tiempo real.', 'Facilita consultar las horas disponibles y coordinar a quienes participan en la atención.'],
        ['Gestión administrativa integrada', 'Herramientas para pagos, estadísticas, control de inventario y reportes.', 'Reunir estas tareas facilita revisar la operación del centro y gestionar sus recursos desde la plataforma.'],
    ],
];

return [
    'veterchile-veterfood' => [
        'titulo' => 'Veterfood · Conecta el cuidado con las compras periódicas',
        'introduccion' => 'Explica el recorrido desde la necesidad de la mascota hasta la visita a la tienda y la organización de un pedido mensual.',
        'funciones' => [
            ['Visita a la tienda', 'Revisar el catálogo, la presentación del producto, su precio y disponibilidad.', 'Permite elegir con información y consultar productos adicionales cuando se necesitan.'],
            ['Pedidos mensuales', 'Organizar el producto, la cantidad y la frecuencia de reposición, con fecha, dirección y forma de pago.', 'Ayuda a anticipar las compras y organizar las próximas entregas.'],
            ['Mis suscripciones y pedidos', 'Consultar los pedidos programados y las compras desde el panel del cliente.', 'Facilita tener a mano el detalle del pedido y coordinar la reposición habitual.'],
        ],
    ],
    'veterchile-introduccion' => [
        'titulo' => 'Ventajas para quienes cuidan a la mascota',
        'introduccion' => 'Veterchile conecta el cuidado cotidiano del tutor, la consulta del profesional y la coordinación del equipo de atención.',
        'funciones' => [
            ['Para tutores', 'Historial, documentos, agenda, seguimiento y colaboración con cuidadores.', 'Ayuda a tener los antecedentes a mano y organizar el cuidado de cada mascota.'],
            ['Para profesionales', 'Agenda, fichas por especialidad, documentos personalizados y aplicación móvil.', 'Facilita preparar la consulta y continuar el seguimiento junto al tutor.'],
            ['Para centros y asistentes', 'Agenda compartida y herramientas de pagos, estadísticas, inventario y reportes.', 'Ayuda a coordinar al equipo y administrar la operación.'],
        ],
    ],
    'veterchile-tutores' => $tutor,
    'veterchile-profesionales' => $profesional,
    'veterchile-asistente-online' => $institucion,
    'veterchile-centros-veterinarios' => $institucion,
    'veterchile-clinicas' => $institucion,
    'veterchile-hospitales' => [
        'titulo' => 'Para hospitales veterinarios · Continuidad del cuidado',
        'introduccion' => 'Presenta las herramientas compartidas de la institución y el módulo para mascotas hospitalizadas.',
        'funciones' => array_merge($institucion['funciones'], [
            ['Mascotas hospitalizadas', 'Módulo adicional para gestionar mascotas hospitalizadas dentro de la plataforma.', 'Facilita organizar el seguimiento hospitalario junto a los antecedentes y documentos de la mascota.'],
        ]),
    ],
    'veterchile-laboratorios' => [
        'titulo' => 'Para laboratorios · Coordinación y documentos disponibles',
        'introduccion' => 'Relaciona la organización de exámenes y documentos con la coordinación del equipo y del tutor.',
        'funciones' => array_merge($institucion['funciones'], [
            ['Resultados disponibles', 'Los resultados de exámenes e informes disponibles forman parte de los documentos consultables de la mascota.', 'Facilita encontrar los antecedentes para el próximo control veterinario.'],
        ]),
    ],
];
