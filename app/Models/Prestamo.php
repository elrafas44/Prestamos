<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;
    protected $table = "prestamo";
    protected $primaryKey = "id_prestamo";
    public $incrementing = true;
    protected $keyType = "int";
    protected $estado;
    protected $fecha_Apobacion;
    protected $fecha_solicitud;
    protected $monto;
    protected $id_empleado;
    protected $fillable = ["estado", "fecha_Apobacion", "fecha_solicitud", "monto", "id_empleado"];
    public $timestamps = false;
}
