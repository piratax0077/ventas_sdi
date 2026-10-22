<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BienvenidaProfesional extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $email;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(string $nombre, string $email, string $password)
    {
        $this->nombre   = $nombre;
        $this->email    = $email;
        $this->password = $password;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Bienvenido al sistema - Tus credenciales de acceso')
                    ->view('emails.bienvenida_profesional');
    }
}
