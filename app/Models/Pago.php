<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos'; // Tu tabla existente
    protected $primaryKey = 'idPago'; // Tu llave primaria

    protected $fillable = [
        'id_orden',
        'montoEfectivo',
        'montoTarjeta',
        'metodoPago',
        'fecha'
    ];

    // Relación inversa: Un pago pertenece a una orden
    public function orden() {
        return $this->belongsTo(Orden::class, 'id_orden', 'id_orden');
    }
}