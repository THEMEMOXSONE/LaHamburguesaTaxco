<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corte extends Model
{
    protected $table = 'cortes';
    protected $primaryKey = 'idCorte';

    protected $fillable = [
        'usuario_id',
        'fecha',
        'monto_inicial',
        'monto_final',
        'monto_calculado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
