<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    public function categoria()
    {   // N:1
        return $this->belongsTo(Categoria::class);
    }

    public function sucursales()
    {
        return $this->belongsToMany(Sucursal::class);
    }
}
