<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $table = "empleado";
    protected $primaryKey = "id_empleado";
    public $incrementing = true;
    protected $keyType = "int";
    protected $nombre;
    protected $apellidoP;
    protected $apellidoM;
    protected $fecha_ingreso;
    protected $activo;  
    protected $fillable = ["nombre", "apellidoP", "apellidoM", "fecha_ingreso", "activo"];
    public $timestamps = false;
}
