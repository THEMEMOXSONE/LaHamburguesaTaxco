<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'ordenes';
    
    // La llave primaria en la tabla es `id_orden`
    protected $primaryKey = 'id_orden';

    // Campos rellenables
    protected $fillable = [
        'id_mesa',
        'usuario_id',
        'estado',
        'total',
        'folioDia',
        'FECHA',
        'nota'
    ];

    /**
     * Una Orden pertenece a una Mesa
     */
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'id_mesa');
    }

    /**
     * Detalles de la orden (líneas)
     */
    public function detalles()
    {
        return $this->hasMany(DetalleOrden::class, 'id_orden');
    }

    public function pago() {
    // Una orden tiene un pago asociado
    return $this->hasOne(Pago::class, 'id_orden', 'id_orden');
}

public function usuario()
    {
        // Le decimos: "Esta orden pertenece a un User, usando la columna 'usuario_id'"
        return $this->belongsTo(User::class, 'usuario_id');
    }

}