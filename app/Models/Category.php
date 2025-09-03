<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active'
    ];

    // Campos que deben ser tratados como fechas
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    // Casting de tipos de datos
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relación: Una categoría tiene muchos productos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

// el modelo tiene una funcionalidad que es "use HasFactory";
// otra caracteristica que tiene es que se le puede decir que datos de las tablas pueden ser modificados
