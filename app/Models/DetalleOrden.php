<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrden extends Model
{
    use HasFactory;

    protected $table = 'detalle_orden';

    protected $primaryKey = 'id_detalle';

    public $timestamps = true;

    protected $fillable = [
        'id_orden',
        'id_prod',
        'cantidad',
        'precio',
        'notas'
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'id_orden');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_prod');
    }
}
