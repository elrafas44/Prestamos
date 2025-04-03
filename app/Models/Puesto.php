<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;
    protected $table = "puestos";
    protected $primaryKey = "id_puesto";
    public $incrementing = true;
    protected $keyType = "int";
    protected $nombre;
    protected $sueldo;
    protected $estado;
    protected $fillable = ["nombre", "sueldo", "estado"];
    public $timestamps = false;
}
