<?php

namespace App;

class ContactoCliente
{
    public static function correo(?string $valor): ?string
    {
        $correo = strtolower(trim($valor ?? ''));
        if (strlen($correo) > 100 || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $dominio = substr(strrchr($correo, '@'), 1);
        if (!str_contains($dominio, '.') || preg_match('/(?:^|\.)(localhost|local|test|invalid|example|localhost\.localdomain|example\.(com|org|net))$/', $dominio)) {
            return null;
        }

        return $correo;
    }

    public static function telefono(?string $valor): ?string
    {
        $numero = preg_replace('/[\s().-]+/', '', trim($valor ?? ''));
        if (!preg_match('/^(?:\+?56|0056)?([2-79]\d{8})$/', $numero, $partes)) {
            return null;
        }
        $nacional = $partes[1];
        if (preg_match('/^(?:\d)?(\d)\1{7}$/', $nacional) || in_array($nacional, ['234567890', '912345678', '987654321'], true)) {
            return null;
        }

        return '+56'.$nacional;
    }

    public static function whatsapp(Cliente $cliente): ?string
    {
        foreach ([$cliente->celular, $cliente->telefono, $cliente->inst_telefono] as $valor) {
            $numero = self::telefono($valor);
            if ($numero && str_starts_with($numero, '+569')) {
                return substr($numero, 1);
            }
        }
        return null;
    }
}
