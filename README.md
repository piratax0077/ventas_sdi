# Ventas SDI — Laravel 13 / PHP 8.5

Proyecto basado en ventas-sdi(7.4), con Laravel Framework 13.38.0 y PHP 8.5.12.
Dependencias fijadas en composer.lock: Passport 13.10.0, Laravel UI 4.6.3,
Spatie Permission 8.3.2 y ESC/POS 4.0.

## Estado

La base independiente ventas_sdi_v13 ya está creada y migrada. Contiene los
catálogos de referencia necesarios; no se copiaron usuarios, pacientes ni
registros comerciales del sistema anterior. El esquema comercial de productos
es propio de ventas, porque la tabla productos de la base compartida tenía
otra estructura.

Se conservó la estructura de kernels y proveedores de la aplicación y se
adaptaron middleware, modelos, fechas, permisos y tokens a Laravel 13 / PHP 8.5.
La instalación completa requiere MySQL o MariaDB.

## Primer acceso

Desde esta carpeta, en PowerShell, crear el primer vendedor:

~~~powershell
.\artisan85.ps1 ventas:usuario tu-correo@ejemplo.cl --name="Tu nombre"
~~~

La contraseña se solicita de forma privada y debe tener al menos 8 caracteres.
No se instaló una contraseña predeterminada ni se copiaron cuentas antiguas.
El registro público web está deshabilitado. Desde Usuarios, la cuenta
administradora puede crear y gestionar vendedores y subadministradores.

Iniciar el servidor:

~~~powershell
.\artisan85.ps1 serve --host=127.0.0.1 --port=8013
~~~

Abrir http://127.0.0.1:8013/login.

## PHP y Laragon

artisan85.ps1 utiliza PHP oficial de .runtime/php con intl, sodium, pdo_mysql,
pdo_sqlite, mbstring, openssl, curl, fileinfo y zip. El PHP global de Herd del
equipo carece de intl y sodium. El runtime local no se versiona.

Para servir con Laragon, seleccionar un PHP 8.5 con esas extensiones y apuntar
el document root a public. .env contiene la conexión a ventas_sdi_v13.
Las claves APP_KEY y OAuth de desarrollo ya fueron generadas. storage:link
ya fue ejecutado. MAIL_MAILER=log evita envíos reales durante el desarrollo.

## Despliegue en producción

1. Configurar PHP 8.5.x tanto en la consola como en el servidor web. Usar `public/`
   como raíz del sitio y dirigir las rutas de la aplicación a `public/index.php`.
   En Apache, habilitar `mod_rewrite` y permitir las reglas de `public/.htaccess`.
   En Nginx, usar `try_files $uri $uri/ /index.php?$query_string;` con PHP-FPM 8.5.
2. Crear una base MySQL y un usuario para la aplicación. Copiar `.env.example`
   a `.env` solo para una instalación nueva. Configurar `APP_ENV=production`,
   `APP_DEBUG=false`, `APP_URL` con el dominio HTTPS, `SESSION_SECURE_COOKIE=true`
   y las variables `DB_*`. Configurar SMTP si el sitio debe enviar correos.
   Dar permiso de escritura al proceso web en `storage/` y `bootstrap/cache/`.
3. Desde la raíz del proyecto, instalar y comprobar las dependencias de producción.

   ~~~sh
   php -v
   composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
   composer check-platform-reqs --no-dev
   ~~~

4. En una instalación nueva, inicializar la base, Passport y las cuentas.

   ~~~sh
   php artisan key:generate --force
   php artisan migrate --force
   php artisan db:seed --class=DatabaseSeeder --force
   php artisan passport:keys
   php artisan passport:client --personal --name="Ventas SDI" --provider=users
   php artisan storage:link
   php artisan ventas:usuario administrador@ejemplo.cl --name="Administrador" --admin
   php artisan ventas:usuario vendedor@ejemplo.cl --name="Vendedor"
   php artisan config:cache
   php artisan view:cache
   ~~~

   Las contraseñas se solicitan de forma privada. Los archivos CSS e imágenes
   usados por la aplicación ya están en `public/`.

5. Abrir `/login` en el dominio de producción y comprobar el acceso al panel.

En una actualización, respaldar la base y conservar el `.env`, `APP_KEY`, las
claves OAuth y los archivos persistentes de `storage/` del servidor. Instalar
las dependencias, ejecutar `php artisan migrate --force` y regenerar las cachés
de configuración y vistas. Si el servidor sigue mostrando PHP 8.4 en el
navegador, corregir la versión asociada al dominio en el servidor web.

Las estructuras de referencia están incluidas en database/source-schema.json,
sin registros personales. database/catalogs.json contiene únicamente catálogos.
Las migraciones se completaron para usuarios, permisos, productos, configuración,
soporte, responsables de ventas y relaciones. Los tokens de Passport 8 no se
reutilizan: se emiten tokens nuevos con Passport 13.

## Pruebas

~~~powershell
.\test85.ps1
~~~

El script prepara exclusivamente ventas_sdi_v13_test y ejecuta la suite.
Los datos ficticios se revierten mediante transacciones; la base de ventas
no se utiliza para las pruebas comerciales.

Resultado de la revisión del 21-10-2026: 26 pruebas y 442 aserciones correctas.
Se probaron 24 pantallas, ventas físicas y membresías, stock y rollback,
altas de pacientes/profesionales/asistentes/instituciones/laboratorios,
CRUD de catálogos, usuarios, perfiles, roles, API y archivos de soporte.

Las pruebas básicas también pueden ejecutarse con:

~~~powershell
.\artisan85.ps1 test
~~~

Sin VENTAS_MYSQL_TESTS=1 se omiten las pruebas comerciales de MySQL.

## Alcance pendiente

La impresión física requiere configurar NOMBRE_IMPRESORA en .env y probar
con la impresora conectada. El ticket HTML para descargar PDF respondió
correctamente; la descarga mediante JavaScript no fue probada en navegador.
Los correos SMTP reales tampoco se enviaron.

Se mantienen CSS, vistas y CDN originales. package.json y webpack.mix.js
son herramientas heredadas: no se requiere compilarlos para la interfaz
actual y no se validó su compilación con Node. La revisión HTTP verificó
login, CSS locales y estado API; no incluye una auditoría visual en navegador.

Los roles de acceso son Administrador, Subadministrador y Vendedor.
El administrador gestiona usuarios y configuración. Administradores y
subadministradores gestionan software, planes, mantenimiento y reportes.
Los tres roles pueden registrar suscripciones.
Las respuestas administrativas de soporte requieren el rol Admin.
El catálogo se carga primero en Software, con su línea y público. Luego se
crean los planes mensuales o anuales y se vinculan al software incluido.
Cada plan define el precio por ciclo y, si corresponde, un precio promocional.
El vendedor elige un plan y registra la suscripción del cliente como pendiente.
La API de registro en `POST /api/auth/venta` requiere `id_cliente` e `id_plan`
y admite `cantidad`. El registro se limita al período inicial. Los cobros,
las activaciones y las renovaciones automáticas quedan para una etapa posterior.
Los reportes guardan su configuración para consulta.

Más detalle en REVISION.md.
