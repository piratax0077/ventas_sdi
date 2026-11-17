<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SeguimientoContacto extends Model
{
    public const ESTADOS = [
        'pendiente' => 'Pendiente',
        'contactado' => 'Contactado',
        'respondio' => 'Respondió',
        'sin_respuesta' => 'Sin respuesta',
        'no_interesado' => 'No interesado',
    ];

    protected $table = 'seguimientos_contacto';
    protected $fillable = ['cliente_id', 'responsable_id', 'lote', 'canal', 'referencia', 'estado', 'nota', 'respondio_en'];
    protected $casts = ['respondio_en' => 'datetime'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function scopeVisiblePara(Builder $query, User $user): Builder
    {
        return $user->can('administrar-catalogo') ? $query : $query->where('responsable_id', $user->id);
    }
}
