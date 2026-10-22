<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a SDI</title>
</head>

<body style="margin:0;padding:0;background:#eef3f9;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#eef3f9;">
        <tr>
            <td align="center" style="padding:40px 15px;">

                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                    style="max-width:640px;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 20px 60px rgba(15,23,42,.08);">

                    <!-- BARRA SUPERIOR -->
                    <tr>
                        <td style="height:6px;background:linear-gradient(90deg,#1a49a3 0%, #31bebe 100%);"></td>
                    </tr>

                    <!-- HEADER -->
                    <tr>
                        <td style="padding:30px 40px 20px 40px;">

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>

                                    <td width="90" valign="middle">
                                        <img
                                            src="https://www.med-sdi.cl/images/sdi-color-h.svg"
                                            alt="SDI"
                                            style="width:100px;display:block;">
                                    </td>

                                    <td valign="middle">

                                        <p style="
                                            margin:0;
                                            font-family:Arial,Helvetica,sans-serif;
                                            font-size:14px;
                                            letter-spacing:2px;
                                            text-transform:uppercase;
                                            color:#31bebe;
                                            text-align: right;
                                            font-weight:bold;">
                                            Cuenta Profesional Creada
                                        </p>

                                    </td>

                                </tr>
                            </table>

                        </td>
                    </tr>


                    <!-- TITULO -->
                    <tr>
                        <td style="padding:0 45px;">

                            <h1 style="
                                margin:0;
                                font-family:Arial,Helvetica,sans-serif;
                                font-size:20px;
                                color:#0f172a;
                                font-weight:700;">
                                ¡Bienvenido a SDI!
                            </h1>

                        </td>
                    </tr>

                    <!-- CONTENIDO -->
                    <tr>
                        <td style="padding:18px 45px 10px 45px;">

                            <p style="
                                margin:0 0 18px 0;
                                font-family:Arial,Helvetica,sans-serif;
                                font-size:16px;
                                line-height:25px;
                                color:#475569;">
                                Hola <strong>{{ $nombre }}</strong>,
                            </p>

                            <p style="
                                margin:0;
                                font-family:Arial,Helvetica,sans-serif;
                                font-size:16px;
                                line-height:25px;
                                color:#475569;">
                                Tu cuenta profesional ha sido creada exitosamente.
                                A continuación encontrarás tus credenciales de acceso:
                            </p>

                        </td>
                    </tr>



                    <!-- CREDENCIALES -->
                    <tr>
                        <td style="padding:10px 35px;">

                            <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                style="
                                border:1px solid #dbeafe;
                                background:#f8fbff;

                                border-radius:18px;">

                                <tr>
                                    <td colspan="2"
                                        style="
                                        padding:10px 24px;
                                        border-bottom:1px solid #dbeafe;
                                        background:#eff6ff;
                                          border-top-left-radius: 20px;
                                border-top-right-radius: 20px;">

                                        <strong style="
                                            font-family:Arial,Helvetica,sans-serif;
                                            color:#1a49a3;
                                            font-size:16px;">
                                            Credenciales de Acceso
                                        </strong>

                                    </td>
                                </tr>

                                <tr>
                                    <td width="35%"
                                        style="
                                        padding:10px 24px;
                                        font-family:Arial,Helvetica,sans-serif;
                                        font-size:14px;
                                        color:#1a49a3;
                                        font-weight:bold;
                                        border-bottom:1px solid #e2e8f0;">
                                        Usuario
                                    </td>

                                    <td style="
                                        padding:10px 24px;
                                        font-family:Arial,Helvetica,sans-serif;
                                        font-size:14px;
                                        color:#475569;
                                        border-bottom:1px solid #e2e8f0;">
                                        {{ $email }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="
                                        padding:10px 24px;
                                        font-family:Arial,Helvetica,sans-serif;
                                        font-size:14px;
                                        color:#1a49a3;
                                        font-weight:bold;
                                        border-bottom:1px solid #e2e8f0;">
                                        Contraseña Temporal
                                    </td>

                                    <td style="
                                        padding:10px 24px;
                                        font-family:Arial,Helvetica,sans-serif;
                                        font-size:14px;
                                        color:#475569;
                                        border-bottom:1px solid #e2e8f0;">
                                        {{ $password }}
                                    </td>
                                </tr>

                                <tr>
                                    <td width="35%"
                                        style="
                                        padding:10px 24px;
                                        font-family:Arial,Helvetica,sans-serif;
                                        font-size:14px;
                                        color:#1a49a3;
                                        font-weight:bold;
                                     ;">
                                        Acceso
                                    </td>

                                    <td style="
                                        padding:10px 24px;
                                        font-family:Arial,Helvetica,sans-serif;
                                        font-size:14px;
                                        color:#475569;
                                      ">
                                         <a href="https://med-sdi.cl"
                                            target="_blank"
                                            style="
                                            color:#1a49a3;
                                            font-weight:bold;
                                            text-decoration:none;">
                                           wwww.med-sdi.cl
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- MENSAJE SEGURIDAD -->
                    <tr>
                        <td style="padding:0 40px 30px 40px;">

                            <table width="100%" cellspacing="0" cellpadding="0"
                                style="
                                background:#f0fdf4;
                                border:1px solid #bbf7d0;
                                border-radius:14px;">

                                <tr>
                                    <td style="
                                        padding:16px 20px;
                                        font-family:Arial,Helvetica,sans-serif;
                                        font-size:14px;
                                        line-height:24px;
                                        color:#166534;">

                                        🔒 Por seguridad, te recomendamos cambiar tu contraseña
                                        al ingresar por primera vez al sistema.

                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    <!-- DESPEDIDA -->
                    <tr>
                        <td style="padding:0 30px 35px 45px;">

                            <p style="
                                margin:0;
                                font-family:Arial,Helvetica,sans-serif;
                                font-size:16px;
                                line-height:28px;
                                color:#475569;">
                                Si tienes alguna duda, no dudes en contactarnos.
                            </p>

                            <p style="
                                margin:20px 0 0 0;
                                font-family:Arial,Helvetica,sans-serif;
                                font-size:16px;
                                line-height:28px;
                                color:#475569;">
                                Saludos,<br>
                                <strong>Equipo SDI</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="
                            background:#f1f5f9;
                            border-top:1px solid #dbe4ee;
                            padding:20px;
                            text-align:center;">

                            <p style="
                                margin:0;
                                font-family:Arial,Helvetica,sans-serif;
                                font-size:12px;
                                color:#64748b;">
                                Este correo fue generado automáticamente. Por favor no responder.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>