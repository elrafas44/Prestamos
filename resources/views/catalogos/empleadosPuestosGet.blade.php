@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent
<div class = "row">
    <div class = "form-group my-3">
        <h1>Puestos del empleado</h1>
    </div>
    <div>Empleado: {{$empleado->nombre}}</div>
    <div class="col"></div>
    <div class="col-auto">
        <a class="btn btn-primary" href="{{url('/catalogos/empleados/'.$empleado->id_empleado.'/puestos/cambiar')}}">Cambiar</a>
    </div>
</div>
<table class="table" id="maintable">
<thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">PUESTO</th>
        <th scope="col">FECHA DE INICIO</th>
        <th scope="col">FECHA DE TERMINO</th>
    </tr>
</thead>
<tbody>
@foreach($puestos as $puesto)
    <tr>
        <td class ="text-center">{{$puesto->id_detalle_empl_puesto}}</td>
        <td class ="text-center">{{$puesto->puesto}}</td>
        <td class ="text-center">{{$puesto->fecha_inicio}}</td>
        <td class ="text-center">{{$puesto->fecha_fin}}</td>
    </tr>
@endforeach
</tbody></table>
@endsection