<?php

namespace App\Http\Controllers;
use App\Models\Puesto;
use App\Models\Empleado;
use App\Models\detalle_empleado_puesto;
use App\Models\Prestamo;
use App\Models\Abono;
use App\Models\detalle_prestamo;
use Illuminate\Http\RedirectResponse;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovimientosController extends Controller
{
    /*
    * Presenta una lista de todos los préstamos registrados en el sistema
    */
    public function prestamosGet(): View
    {
        $prestamos = Prestamo::join("empleado", "prestamo.id_empleado", "=", "empleado.id_empleado")->get();
        return view("movimientos/prestamosGet", [
            "prestamos" => $prestamos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url("/movimientos/prestamos")
            ]
        ]);
    }
    public function prestamosAgregarGet(): View
    {
        $hacennanno = (new DateTime("-1 year"))->format("Y-m-d");
        $empleados = Empleado::where("fecha_ingreso", "<", $hacennanno)->get()->all();
        $empleados = array_column($empleados, null, "id_empleado");
        // Se envían a la vista los registros de los empleados en la tabla
        return view('movimientos/prestamosAgregarGet', [
            "empleados" => $empleados,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url("/movimientos/prestamos"),
                "Agregar" => url("/movimientos/prestamos/agregar")
            ]
        ]);
    }
    public function prestamosAgregarPost(Request $request)
    {
        $id_empleado = $request->input("id_empleado");
        $monto = $request->input("monto");
        $fecha_solicitud = $request->input("fecha_solicitud");
        $fecha_Apobacion = $request->input("fecha_Apobacion"); // Asegúrate de que este valor no sea null
        $estado = $request->input("estado");

        // Validar que la fecha de aprobación no sea null
        if (is_null($fecha_Apobacion)) {
            return back()->withErrors(["fecha_Apobacion" => "La fecha de aprobación es obligatoria."]);
        }

        $prestamo = new Prestamo([
            "id_empleado" => $id_empleado,
            "fecha_solicitud" => $fecha_solicitud,
            "monto" => $monto,
            "fecha_Apobacion" => $fecha_Apobacion, // Asegúrate de que este valor no sea null
            "estado" => $estado,
        ]);

        $prestamo->save();
        return redirect("/movimientos/prestamos"); // Redirige al listado de préstamos
    }
    public function abonosGet($id_prestamo): View
    {
        // Obtener todos los abonos asociados al préstamo específico
        $abonos = Abono::where("id_prestamo", $id_prestamo)->get()->all();

        // Obtener la información del préstamo junto con los datos del empleado asociado
        $prestamo = Prestamo::join("empleado", "empleado.id_empleado", "=", "prestamo.id_empleado")
            ->where("prestamo.id_prestamo", $id_prestamo)->first();

        // Retornar la vista con los datos de los abonos, el préstamo y la ruta de navegación
        return view('movimientos/abonosGet', [
            'abonos' => $abonos,
            'prestamo' => $prestamo,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url("/movimientos/prestamos"),
                "Abonos" => url("/movimientos/prestamos/abonos"),
            ]
        ]);
    }
    public function abonosAgregarGet($id_prestamo): View
    {
        $prestamo = Prestamo::join("empleado", "empleado.id_empleado", "=", "prestamo.id_empleado")
            ->where("id_prestamo", $id_prestamo)->first();
    
        $abonos = Abono::where("abono.id_prestamo", $id_prestamo)->get();
        $num_abono = count($abonos) + 1;
    
        // Obtener el último abono registrado
        $ultimo_abono = Abono::where("abono.id_prestamo", $id_prestamo)
            ->orderBy("fecha", "desc")
            ->first();
    
        // Si hay un abono previo, tomamos su saldo actual, si no, usamos el saldo del préstamo
        $saldo_actual = $ultimo_abono ? $ultimo_abono->saldo_actual : $prestamo->saldo_actual;
        
        // Cálculo basado en el saldo actual correcto
        $monto_interes = $saldo_actual * ($prestamo->tasa_mensual / 100);
        $monto_cobrado = $prestamo->pago_fijo_cap + $monto_interes;
        $saldo_pendiente = $saldo_actual - $prestamo->pago_fijo_cap;
    
        if ($saldo_pendiente < 0) {
            $pago_fijo_cap = $prestamo->pago_fijo_cap + $saldo_pendiente;
            $saldo_pendiente = 0;
        } else {
            $pago_fijo_cap = $prestamo->pago_fijo_cap;
        }
    
        return view('movimientos/abonosAgregarGet', [
            'prestamo' => $prestamo,
            'num_abono' => $num_abono,
            'pago_fijo_cap' => $pago_fijo_cap,
            'monto_interes' => $monto_interes,
            'monto_cobrado' => $monto_cobrado,
            'saldo_pendiente' => $saldo_pendiente,
            'breadcrumbs' => [
                "Inicio" => url("/"),
                "Prestamos" => url("/movimientos/prestamos"),
                "Abonos" => url("/prestamos/{$prestamo->id_prestamo}/abonos"),
                "Agregar" => "",
            ]
        ]);
    }
    
    public function abonosAgregarPost(Request $request)
    {
        $id_prestamo = $request->input("id_prestamo");
        $num_abono = $request->input("num_abono");
        $fecha = $request->input("fecha");
        $monto_capital = $request->input("monto_capital");
        $monto_interes = $request->input("monto_interes");
        $monto_cobrado = $request->input("monto_cobrado");
        $saldo_pendiente = $request->input("saldo_pendiente");
        
        // Crear el nuevo abono
        $abono = new Abono([
            "id_prestamo" => $id_prestamo,
            "num_abono" => $num_abono,
            "fecha" => $fecha,
            "monto_capital" => $monto_capital,
            "monto_interes" => $monto_interes,
            "monto_cobrado" => $monto_cobrado,
            "saldo_actual" => $saldo_pendiente,
        ]);

        $abono->save();

        // Actualizar el saldo del préstamo
        $prestamo = Prestamo::find($id_prestamo);
        $prestamo->saldo_actual = $saldo_pendiente;
        if ($saldo_pendiente < 0.01) {
            $prestamo->estado = 1; // Marcar como pagado si el saldo llega a 0
        }

        $prestamo->save();

        return redirect("/prestamos/{$id_prestamo}/abonos");
    }

    public function empleadosPrestamosGet(Request $request, $id_empleado): View
    {
        $empleado = Empleado::find($id_empleado);

        $prestamos = Prestamo::where("prestamo.id_empleado", $id_empleado)->get();

        return view('movimientos.empleadosPrestamosGet', [
            "empleado" => $empleado,
            'prestamos' => $prestamos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url("/movimientos/prestamos")
            ]
        ]);
    }


}