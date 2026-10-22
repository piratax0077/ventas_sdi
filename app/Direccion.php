<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ciudad;

class Direccion extends Model
{
    //
    protected $table = 'direcciones';

    public function Ciudad()
    {
        return $this->hasOne(Ciudad::class,'id', 'id_ciudad');
    }
}
