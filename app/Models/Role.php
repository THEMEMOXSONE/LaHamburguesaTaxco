<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // 1. Definimos el nombre de la tabla (opcional si sigue convención, pero mejor asegurar)
    protected $table = 'roles'; 

    // 2. IMPORTANTE: Definimos que tu llave primaria NO es 'id', sino 'id_rol'
    protected $primaryKey = 'id_rol';

    // 3. Indicamos que la llave es autoincremental (normalmente true por defecto, pero no estorba)
    public $incrementing = true;

    // 4. Especificamos el tipo de la llave (entero)
    protected $keyType = 'int';

    // Relación inversa para poder consultar desde el Rol (opcional)
    public function users()
    {
        return $this->belongsToMany(User::class, 'usuario_rol', 'id_rol', 'usuario_id');
    }
}