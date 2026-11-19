<?php

return [
    'veterchile-veterfood' => [
        'caso' => 'Sofía compra alimento para Bruno cada mes. Quiere organizar su próxima entrega y consultar otros productos en la tienda.',
        'pasos' => [
            ['Visitar la tienda', 'Producto de ejemplo', 'Alimento para Bruno · Presentación y precio por revisar · Disponibilidad por confirmar.', 'Consultar la tienda facilita comparar el producto y revisar la información antes de comprar.'],
            ['Programar pedido mensual', 'Entrega de ejemplo', 'Cantidad: 1 unidad · Frecuencia: mensual · Fecha, dirección y forma de pago por confirmar.', 'Programar la reposición ayuda a anticipar las compras habituales y organizar la entrega.'],
            ['Revisar pedidos y volver a la tienda', 'Panel del cliente', 'Mis suscripciones · Pedidos disponibles · Consulta de productos adicionales.', 'Tener la información de los pedidos a mano facilita revisar las compras habituales y preparar otras cuando las necesite.'],
        ],
    ],
    'medichile-introduccion' => [
        'caso' => 'Camila prepara una atención y quiere entender quién puede consultar sus antecedentes.',
        'pasos' => [
            ['Conectar a los participantes', 'Un mismo cuidado', 'Paciente, profesional y centro participan en la atención según sus accesos.', 'Coordinar a quienes dan y reciben atención facilita organizar el cuidado de la salud.'],
            ['Revisar el control individual', 'Los datos de cada persona', 'Cada persona conserva la propiedad y el control de sus datos.', 'Mantener el control individual permite a cada titular decidir sobre su información.'],
            ['Compartir con autorización', 'Interacción autorizada', 'La información se comparte con autorización de sus titulares.', 'Compartir los antecedentes autorizados facilita una atención coordinada y respetuosa de las decisiones de cada persona.'],
        ],
    ],
    'veterchile-introduccion' => [
        'caso' => 'Sofía quiere organizar el cuidado de Bruno y compartir los antecedentes disponibles en su próxima atención.',
        'pasos' => [
            ['Reunir antecedentes', 'El cuidado en un solo lugar', 'Historial, documentos y controles disponibles de Bruno.', 'Reunir los antecedentes facilita encontrarlos cuando la mascota necesita atención.'],
            ['Conectar al equipo', 'Tutores y atención veterinaria', 'Tutor, veterinario y centro participan desde la plataforma según sus accesos.', 'Trabajar con la información disponible facilita coordinar el cuidado de la mascota.'],
            ['Preparar el próximo control', 'Tenencia responsable', 'Revisar los antecedentes y organizar una próxima atención.', 'Tener la información ordenada ayuda al tutor a participar en el seguimiento de su mascota.'],
        ],
    ],
    'fmu' => [
        'caso' => 'Camila prepara su próximo control. Sus recetas y exámenes están repartidos.',
        'pasos' => [
            ['Abrir mi FMU', 'Documentos de Camila', 'Receta del último control · Informe de examen · Resumen de controles.', 'Tener los documentos disponibles en un mismo lugar facilita preparar una consulta y volver a revisar sus antecedentes.'],
            ['Consultar un examen', 'Examen de ejemplo', 'Informe disponible para consultar antes del próximo control.', 'El paciente puede encontrar el informe que necesita sin volver a buscarlo entre conversaciones y papeles.'],
            ['Ver opciones con app', 'Uso desde el celular', 'Comparar el plan con app, reserva de horas y módulos de Embarazo o Niño Sano.', 'Elegir las prestaciones que realmente usará ayuda a comprender el valor de la suscripción y de mantenerla entre controles.'],
        ],
    ],
    'profesionales' => [
        'caso' => 'La doctora Paula necesita preparar una consulta y emitir sus documentos.',
        'pasos' => [
            ['Abrir agenda y ficha', 'Atención de ejemplo', 'Hora confirmada · Antecedentes disponibles · Registro de atención.', 'Reunir agenda y ficha facilita pasar de una cita a la atención con los antecedentes a mano.'],
            ['Revisar receta y acceso', 'Documentos del profesional', 'Receta firmada con firma configurada · Apertura de talonarios según autorización.', 'El profesional puede revisar la receta emitida y controlar quién tiene acceso a sus talonarios. Demuestra los controles habilitados.'],
            ['Comparar módulos', 'Opciones para la consulta', 'Atención Online · Hospitalización · Licencias y bonos según habilitación y convenio.', 'Adaptar el plan al trabajo del profesional permite explicar qué tareas puede resolver desde su escritorio y qué integraciones debe confirmar.'],
        ],
    ],
    'centros-medicos' => [
        'caso' => 'Un centro coordina recepción, profesionales y caja para una jornada de consultas.',
        'pasos' => [
            ['Consultar agendas', 'Agenda del equipo', 'Profesional y servicio · Horas disponibles · Citas confirmadas.', 'Una vista compartida facilita coordinar al equipo y responder a las solicitudes de horas.'],
            ['Recibir una cita', 'Recepción de ejemplo', 'Cita localizada · Profesional asignado · Atención preparada.', 'Conectar recepción y agenda ayuda a que cada persona encuentre la información necesaria para su tarea.'],
            ['Revisar caja y plan', 'Gestión del centro', 'Usuarios del equipo · Asistencia de caja · Convenios habilitados.', 'Elegir los módulos y accesos según cada área facilita ordenar las tareas administrativas y revisar los cobros disponibles.'],
        ],
    ],
    'asistente-online' => [
        'caso' => 'Una persona solicita cambiar su hora y necesita una respuesta del asistente.',
        'pasos' => [
            ['Abrir solicitud', 'Solicitud de ejemplo', 'Persona identificada · Motivo: coordinar una hora.', 'Tener el contexto a mano facilita dar una respuesta coherente y continuar la atención.'],
            ['Buscar disponibilidad', 'Agenda vinculada', 'Profesional seleccionado · Horas disponibles para coordinar.', 'Consultar la agenda correspondiente facilita ofrecer alternativas concretas en la misma conversación.'],
            ['Confirmar coordinación', 'Hora de ejemplo coordinada', 'Alternativa elegida · Datos revisados · Próximo paso informado.', 'Una coordinación clara ayuda a que la persona y el equipo sepan cuándo y cómo continuará la atención.'],
        ],
    ],
    'clinicas' => [
        'caso' => 'Una clínica necesita coordinar una consulta y su seguimiento entre servicios.',
        'pasos' => [
            ['Abrir cita por servicio', 'Atención ambulatoria', 'Servicio elegido · Profesional · Cita de ejemplo.', 'Organizar las citas por servicio facilita orientar al paciente y coordinar el trabajo del equipo.'],
            ['Consultar ficha', 'Antecedentes disponibles', 'Ficha clínica · Documentos · Control anterior.', 'Encontrar los antecedentes desde el flujo de atención facilita preparar el seguimiento.'],
            ['Elegir módulos', 'Alcance de la clínica', 'Comparar Atención Online, Hospitalización y accesos del equipo.', 'Configurar el alcance según las áreas que lo usarán permite demostrar un beneficio concreto para cada servicio.'],
        ],
    ],
    'hospitales' => [
        'caso' => 'Un equipo necesita revisar la evolución de una persona hospitalizada.',
        'pasos' => [
            ['Abrir hospitalización', 'Caso hospitalario ficticio', 'Paciente de ejemplo · Servicio responsable · Antecedentes disponibles.', 'Consultar el caso desde un mismo flujo facilita ubicar la información relevante para el equipo.'],
            ['Revisar evolución y tareas', 'Seguimiento del equipo', 'Evolución registrada · Tarea asignada · Responsable identificado.', 'Ver las tareas y responsables ayuda a coordinar la continuidad entre integrantes del servicio.'],
            ['Revisar urgencias', 'Módulos del hospital', 'Admisión de urgencias · Turnos · Accesos por servicio.', 'Relacionar los módulos con el recorrido del hospital permite mostrar cómo se organiza la atención entre áreas.'],
        ],
    ],
    'laboratorios' => [
        'caso' => 'El laboratorio debe gestionar un examen y dejar disponible su resultado.',
        'pasos' => [
            ['Abrir solicitud de examen', 'Examen ficticio', 'Solicitud identificada · Persona asociada · Estado de trabajo.', 'Identificar la solicitud y su contexto facilita organizar el trabajo del laboratorio.'],
            ['Cargar resultado de ejemplo', 'Resultado preparado', 'Documento ficticio asociado al examen para la demostración.', 'Asociar cada resultado a su examen facilita encontrarlo cuando se necesite consultar.'],
            ['Revisar accesos', 'Responsables del laboratorio', 'Carga externa · Revisión · Administración técnica según plan.', 'Definir quién carga y quién consulta ayuda a repartir tareas y mostrar el alcance útil del plan.'],
        ],
    ],
    'veterchile-tutores' => [
        'caso' => 'Sofía prepara el próximo control de Bruno y necesita encontrar sus documentos.',
        'pasos' => [
            ['Abrir ficha de Bruno', 'Ficha Veterinaria Única', 'Mascota: Bruno · Antecedentes, recetas y controles disponibles.', 'El tutor puede reunir los antecedentes de su mascota y preparar su próxima atención con más facilidad.'],
            ['Consultar examen', 'Examen de Bruno', 'Informe ficticio disponible para consultar.', 'Encontrar el resultado desde la ficha facilita tenerlo a mano en el próximo control.'],
            ['Buscar una hora', 'Reserva de ejemplo', 'Profesional y servicio · Disponibilidad consultada · Opción de reserva.', 'Consultar disponibilidad facilita coordinar la atención de la mascota desde un mismo recorrido.'],
        ],
    ],
    'veterchile-profesionales' => [
        'caso' => 'Una veterinaria prepara una consulta y revisa los antecedentes de Bruno.',
        'pasos' => [
            ['Abrir agenda y ficha', 'Consulta veterinaria', 'Cita de Bruno · Historial disponible · Ficha de la especialidad.', 'Conectar agenda e historial facilita preparar la consulta con los antecedentes de la mascota.'],
            ['Revisar receta electrónica', 'Documentos veterinarios', 'Receta y orden de ejemplo con firma configurada.', 'Tener los documentos de la atención asociados facilita consultarlos y explicar las indicaciones registradas.'],
            ['Delegar agenda', 'Coordinación con asistente', 'Agenda vinculada · Accesos revisados según el plan.', 'Delegar tareas de coordinación facilita distribuir el trabajo del equipo según los permisos asignados.'],
        ],
    ],
    'veterchile-asistente-online' => [
        'caso' => 'El tutor pide una hora para su mascota y necesita coordinar con recepción.',
        'pasos' => [
            ['Abrir solicitud del tutor', 'Solicitud de Sofía', 'Mascota identificada · Servicio solicitado · Motivo de coordinación.', 'Identificar al tutor y la mascota facilita mantener el contexto de la solicitud.'],
            ['Consultar agenda', 'Agendas vinculadas', 'Profesional seleccionado · Horas disponibles.', 'Ver las alternativas disponibles facilita responder con una propuesta concreta.'],
            ['Confirmar hora de ejemplo', 'Coordinación preparada', 'Hora y servicio revisados · Instrucciones del siguiente paso.', 'Una coordinación clara ayuda al tutor y al equipo a organizar la atención.'],
        ],
    ],
    'veterchile-centros-veterinarios' => [
        'caso' => 'Un centro necesita coordinar las citas de varios profesionales y servicios.',
        'pasos' => [
            ['Ver agendas del centro', 'Agenda centralizada', 'Profesionales · Servicios · Citas de ejemplo.', 'Consultar las agendas desde un mismo lugar facilita coordinar recepción y profesionales.'],
            ['Recibir una reserva', 'Recepción de Bruno', 'Tutor y mascota identificados · Cita localizada.', 'Relacionar la cita con el tutor y la mascota facilita preparar la atención del equipo.'],
            ['Revisar usuarios y plan', 'Accesos del centro', 'Roles del equipo · Agendas · Ciclo de cobro.', 'Ajustar el plan a las tareas y al número de usuarios permite explicar qué valor recibe cada área.'],
        ],
    ],
    'veterchile-clinicas' => [
        'caso' => 'Una clínica coordina una consulta y el seguimiento de una mascota por especialidad.',
        'pasos' => [
            ['Elegir servicio', 'Consulta por especialidad', 'Servicio y profesional elegidos · Cita de ejemplo.', 'Organizar la atención por servicio facilita dirigir la solicitud al equipo adecuado.'],
            ['Abrir historial', 'Documentos de la mascota', 'Antecedentes · Controles · Documentos disponibles.', 'El equipo puede encontrar la información disponible para preparar el seguimiento.'],
            ['Revisar plan de la clínica', 'Alcance por área', 'Accesos del equipo · Recetas y órdenes · Módulos confirmados.', 'Comparar el plan con las necesidades de cada área ayuda a presentar una propuesta útil para la clínica.'],
        ],
    ],
    'veterchile-hospitales' => [
        'caso' => 'Un hospital veterinario necesita revisar antecedentes de una mascota en atención compleja.',
        'pasos' => [
            ['Abrir antecedentes', 'Caso de Bruno', 'Historial y documentos disponibles para la demostración.', 'Ubicar los antecedentes facilita preparar la revisión del caso por el equipo.'],
            ['Consultar controles', 'Continuidad de atención', 'Controles, tratamientos y documentos registrados.', 'Tener la información disponible en un mismo recorrido facilita consultar el seguimiento.'],
            ['Confirmar alcance hospitalario', 'Requerimientos del hospital', 'Áreas participantes · Accesos · Funciones de internación por confirmar.', 'Revisar el alcance con el hospital permite ofrecer los módulos que respondan a sus necesidades reales.'],
        ],
    ],
    'veterchile-laboratorios' => [
        'caso' => 'Un laboratorio recibe una orden y prepara el resultado de un examen veterinario.',
        'pasos' => [
            ['Abrir orden', 'Orden veterinaria de ejemplo', 'Mascota y examen identificados.', 'Relacionar el examen con la mascota facilita organizar cada solicitud.'],
            ['Asociar resultado', 'Informe de ejemplo', 'Resultado ficticio vinculado al examen.', 'Encontrar el resultado junto al examen facilita consultar el documento correcto.'],
            ['Revisar permisos', 'Equipo del laboratorio', 'Responsables de carga · Consulta · Prestaciones del plan.', 'Aclarar las tareas y accesos facilita distribuir el trabajo y explicar qué cubre la suscripción.'],
        ],
    ],
];
