# Revisión funcional — 21 de octubre de 2026

## Resultado

26 pruebas correctas, 442 aserciones. La suite incluye SQLite en memoria para
tokens y una base MySQL separada para las operaciones comerciales.
No se modificaron registros de la base original compartida.

## Correcciones verificadas

- Migraciones completas y aplicadas en ventas_sdi_v13.
- Esquemas de referencia sin personas, usuarios ni datos comerciales copiados.
- Catálogos de regiones, ciudades, previsiones, especialidades, tipos y roles.
- Productos físicos consultan productos, en lugar de productos_software.
- Validación de stock y errores JSON; el carrito ya no informa éxitos falsos.
- Venta completa dentro de una transacción, con bloqueo de stock.
- Reversión de la operación cuando cambia el stock o faltan artículos.
- Registro de membresías en venta_planes y relación de Plan con id_plan.
- Clientes creados y vinculados a sus usuarios, también para perfiles médicos.
- Registro de asistentes con la clase importada correctamente.
- Conservación de la contraseña al editar un usuario sin reemplazarla.
- El hash de contraseña ya no se inserta en el formulario de edición.
- Cambio de contraseña del perfil exige la contraseña actual.
- Rol Vendedor exigido en módulos web y datos comerciales de la API.
- Registro público web deshabilitado para impedir el acceso sin rol.
- Ventas, tickets y eliminación limitados a su responsable.
- Cierre de sesión invalida la sesión y limpia el carrito.
- Validación de productos y duplicados, cantidades e índices del carrito.
- Reportes sin destinatarios opcionales ya no producen error 500.
- Los estados enviados como false/0 se respetan en catálogos.
- Archivos de respuestas de soporte ligados a respuesta_id.
- Validación de cargas de soporte produce 422 para solicitudes inválidas.
- Compatibilidad del rol Admin original en soporte.
- API de creación de venta asigna cliente y responsable sin mass-assignment.
- Estado API devuelve JSON false para un visitante.
- Nombre duplicado de logout corregido para permitir route:cache.
- Se eliminaron rutas de ventas que apuntaban a métodos sin implementación.
- Errores de validación visibles en la interfaz.

## Verificaciones adicionales

- Login, CSS locales y estado API: HTTP 200 con servidor PHP real.
- Cachés de rutas, configuración y vistas: generadas correctamente.
- Sintaxis PHP revisada.
- Todas las migraciones aplicadas.
- Composer audit: sin avisos conocidos para dependencias de producción.
- Requisitos de plataforma compatibles con el runtime PHP 8.5.12.
- Enlace de almacenamiento público creado.
- Primer vendedor se crea con ventas:usuario; contraseña solicitada en privado.

## Límites de la comprobación

No se realizó una auditoría visual en navegador ni se ejecutó la descarga
JavaScript de PDF. No se probó una impresora física ni SMTP real.
Se conserva el frontend heredado con sus CDN; no se ejecutó la compilación
de Laravel Mix. El módulo Reportes administra configuraciones, sin implementar
un servicio nuevo de envío automático. Las pruebas de stock cubren cambios de
disponibilidad y rollback; no son una prueba de carga concurrente.

Las bases de trabajo y pruebas permanecen separadas. La nueva base requiere
crear el primer vendedor antes de iniciar sesión. No se dejaron cuentas ni
ventas de prueba en la base de trabajo.
