@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<div class="row my-4">
    <div class="col">
        <h1>Abonos</h1>
    </div>
    <div class="col-auto titlebar-commands">
        <a class="btn btn-primary" href="{{url("/prestamos/{$prestamo->id_prestamo}/abonos/agregar")}}">Agregar</a>
    </div>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table" id="maintable">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">FECHA</th>
                    <th scope="col">MONTO CAPITAL</th>
                    <th scope="col">MONTO INTERES</th> 
                    <th scope="col">SALDO ACTUAL</th>
                    <th scope="col">PRESTAMO</th>
                </tr>
            </thead>
            <tbody>
                @foreach($abonos as $abono)
                    <tr>
                        <td class="text-center">{{$abono->id_abono}}</td>
                        <td class="text-center">{{$abono->fecha}}</td>
                        <td class="text-center">{{$abono->monto_capital}}</td>
                        <td class="text-center">{{$abono->monto_interes}}</td>
                        <td class="text-center">{{$abono->saldo_actual}}</td>
                        <td class="text-center">{{$abono->id_prestamo}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection