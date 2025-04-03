<?php

namespace App\Http\Controllers;
use App\Models\Puesto;
use App\Models\Empleado;
use App\Models\detalle_empleado_puesto;
use Illuminate\Http\RedirectResponse;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogosController extends Controller
{
    public function home():view
    {
        return view('home', ["breadcrumbs" => []]);
    }
    public function puestosGet():view
    {
        $puestos = Puesto::all();
        return view('catalogos/puestosGet',['puestos'=> $puestos, "breadcrumbs" => ["Inicio"=>url('/'), "Puestos"=>url("/catalogos/puestos")]]);
    }
    public function puestosAgregarGet():view
    {
        return view('catalogos/puestosAgregarGet',["breadcrumbs" => ["Inicio"=>url('/'), "Puestos"=>url("/catalogos/puestos"), "Agregar"=>url("/catalogos/puestos/agregar")]]);
        
    }
    public function puestosAgregarPost(Request $request): RedirectResponse
    {
        $nombre = $request->input("nombre");
        $sueldo = $request->input("sueldo");
        $estado = $request->input("estado");

        $puesto = new Puesto([
            "nombre" => strtoupper($nombre),
            "sueldo" => $sueldo,
            "estado" => $estado
        ]);
        $puesto->save();

        return redirect("/catalogos/puestos/"); // Redirecciona a la vista de puestos
    }

    public function empleadosGet():view
    {
        $empleados = Empleado::all();
        return view('catalogos/empleadosGet',['empleados'=> $empleados, "breadcrumbs" => ["Inicio"=>url('/'), "Empleados"=>url("/catalogos/empleados")]]);
    }
    public function empleadosAgregarGet():view
    {
        $puestos = Puesto::all();
        return view('catalogos/empleadosAgregarGet',[
            "puestos"=>$puestos, 
            "breadcrumbs" => [
                "Inicio"=>url('/'), 
                "Empleados"=>url("/empleados"), 
                "Agregar"=>url("/empleados/agregar")
                ]
            ]);
    }
    public function empleadosAgregarPost(Request $request): RedirectResponse
    {
        $nombre = $request->input("nombre");
        $apellidoP = $request->input("apellidoP");
        $apellidoM = $request->input("apellidoM");
        $fecha_ingreso = $request->input("fecha_ingreso");
        $activo = $request->input("activo");

        $empleado = new Empleado([
            "nombre" => strtoupper($nombre),
            "apellidoP" => strtoupper($apellidoP),
            "apellidoM" => strtoupper($apellidoM),
            "fecha_ingreso" => $fecha_ingreso,
            "activo" => $activo,
        ]);
        $empleado->save();
        $puesto = new Detalle_empleado_puesto([
            "id_empleado" => $empleado->id_empleado,
            "id_puesto" => $request->input("puesto"),
            "fecha_inicio" => $fecha_ingreso
        ]);
        $puesto->save();

        return redirect("/catalogos/empleados"); // Redirecciona a la vista de empleados
    }
    public function empleadosPuestosGet(Request $request, $id_empleado)
    {
        $puestos = detalle_empleado_puesto::join("puestos", "puestos.id_puesto", "=", "detalle_empleado_puesto.id_puesto")
        ->select("detalle_empleado_puesto.*", "puestos.nombre as puesto", "puestos.sueldo")
        ->where("detalle_empleado_puesto.id_empleado", "=", $id_empleado)
        ->get();
        $empleado = Empleado::find($id_empleado);
        return view('catalogos/empleadosPuestosGet', [
            "puestos" => $puestos,
            "empleado" => $empleado,
            "breadcrumbs" => [
                "Inicio" => url('/'),
                "Empleados" => url("/empleados"),
                "Puestos" => url("/empleados/{id}/puestos")
            ]
        ]);
    }
    public function empleadosPuestosCambiarGet(Request $request, $id_empleado): View 
    {
        $empleado = Empleado::find($id_empleado);
        $puestos = Puesto::all();
        return view('/catalogos/empleadosPuestosCambiarGet', [
            "puestos" => $puestos,
            "empleado" => $empleado,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Empleados" => url("/catalogos/empleados"),
                "Puestos" => url("empleados/{id}/puestos"),
                "Cambiar" => url("/empleados/{id}/puestos/cambiar")
            ]
        ]);
    }

    public function empleadosPuestosCambiarPost(Request $request, $id_empleado): RedirectResponse
    {
        $fecha_inicio = $request -> input ("fecha_inicio");
        $fecha_fin = (new DateTime($fecha_inicio))->modify('-1 day');
        $anterior = detalle_empleado_puesto::where("id_empleado", "=", $id_empleado)
        -> whereNull ("fecha_fin") -> update (["fecha_fin" => $fecha_fin -> format ("Y-m-d")]);
        $puesto = new detalle_empleado_puesto ([
            "id_empleado" => $id_empleado,
            "id_puesto" => $request -> input ("puesto"),
            "fecha_inicio" => $fecha_inicio
        ]);
        $puesto -> save();
        return redirect("/empleados/{$id_empleado}/puestos");
    }
    
}