<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ==========================================
     * RELACIÓN DE ROLES
     * ==========================================
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,      // Modelo relacionado
            'usuario_rol',    // Tabla pivote
            'usuario_id',     // Llave foránea de ESTE modelo (User)
            'id_rol',         // Llave foránea del OTRO modelo (Role)
            'id',             // Llave primaria de ESTE modelo (User)
            'id_rol'          // Llave primaria del OTRO modelo (Role)
        );
    }

    /**
     * Verifica si el usuario tiene un rol específico.
     * VERSIÓN SQL: Pregunta directamente a la base de datos si existe el enlace.
     * Esto evita problemas de mayúsculas, espacios o arrays en PHP.
     */
    public function hasRole($roleName)
    {
        // Esto ejecuta una consulta rápida: 
        // "SELECT * FROM roles WHERE nombrerol = 'Admin' AND usuario_id = 1"
        return $this->roles()->where('nombrerol', $roleName)->exists();
    }
}