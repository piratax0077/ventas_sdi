<?php

namespace App;
use App\Region;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    //
    protected $table = 'ciudades';

    public function Region()
    {
       return $this->belongsTo(Region::class,'id_region', 'id');
    }
}
