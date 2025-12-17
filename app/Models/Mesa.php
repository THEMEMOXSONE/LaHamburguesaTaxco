<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    // La llave primaria en la tabla es `id_mesa`
    protected $primaryKey = 'id_mesa';

    // Campos rellenables
    protected $fillable = ['nombre', 'estado', 'zona'];

    /**
     * Una Mesa puede tener muchas Ordenes
     */
    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'id_mesa');
    }
}
