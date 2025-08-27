<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;    //

    protected $fillable = [
        'name',
        'description',
    ];
}

// el modelo tiene una funcionalidad que es "use HasFactory";
// otra caracteristica que tiene es que se le puede decir que datos de las tablas pueden ser modificados
