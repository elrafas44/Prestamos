<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;
    protected $table = "abono";
    protected $primaryKey = "id_abono";
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fecha;
    protected $monto_capital;
    protected $monto_interes;
    protected $saldo_actual;
    protected $id_prestamo;
    protected $fillable = [
        'fecha',
        'monto_capital',
        'monto_interes',
        'saldo_actual',
        'id_prestamo'
    ];
    public $timestamps = false;
}
