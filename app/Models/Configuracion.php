<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;


    protected $table = 'configuracions'; //Nombre de la tabla en la base de datos

    protected $fillable = [
        'site_name',
        'site_description',
        'site_phone',
        'site_email',
        'site_logo',
    ];
}
