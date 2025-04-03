@extends("components.layout")
@section("content")
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

@php
    // Obtener fechas y préstamos únicos
    $fechas = $abonos->collapse()->keys()->unique()->sort();
    $prestamos = $abonos->keys();
@endphp

<div class="row my-4">
    <div class="col">
        <h1>Resumen de Abonos Cobrados</h1>
    </div>
</div>

<form class="card p-4 my-4" action="{{ url('/reportes/matriz-abonos') }}" method="get">
    <div class="row">
        <div class="col form-group">
            <label for="txtfecha_inicio">Fecha inicio</label>
            <input class="form-control" type="date" name="fecha_inicio" id="txtfecha_inicio" value="{{ $fecha_inicio }}">
        </div>
        <div class="col form-group">
            <label for="txtfecha_fin">Fecha fin</label>
            <input class="form-control" type="date" name="fecha_fin" id="txtfecha_fin" value="{{ $fecha_fin }}">
        </div>
        <div class="col-auto">
            <br>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </div>
</form>

<table class="table" id="maintable">
    <thead>
        <tr>
            <th>ID</th>
            <th>NOMBRE</th>
            @foreach($fechas as $fecha)
                <th>{{ $fecha }}</th>
            @endforeach
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
    @foreach($prestamos as $id_prestamo)
        @php
            $primerAbono = $abonos[$id_prestamo]->first()->first();
            $nombre = $primerAbono['nombre'] ?? 'N/A';
        @endphp
        <tr>
            <td>{{ $id_prestamo }}</td>
            <td>{{ $nombre }}</td>
            @foreach($fechas as $fecha)
                @php
                    $monto = isset($abonos[$id_prestamo][$fecha]) 
                        ? $abonos[$id_prestamo][$fecha]->sum('monto_cobrado') 
                        : 0;
                @endphp
                <td class="text-end">{{ number_format($monto, 2) }}</td>
            @endforeach
            <td class="text-end">
                {{ number_format($abonos[$id_prestamo]->collapse()->sum('monto_cobrado'), 2) }}
            </td>
        </tr>
    @endforeach
</tbody>

<tfoot>
    <tr>
        <td class="text-end" colspan="2">TOTAL</td>
        @foreach($fechas as $fecha)
            @php
                $totalFecha = 0;
                foreach ($abonos as $idPrestamo => $abonosPrestamo) {
                    if (isset($abonosPrestamo[$fecha])) {
                        $totalFecha += $abonosPrestamo[$fecha]->sum('monto_cobrado');
                    }
                }
            @endphp
            <td class="text-end">{{ number_format($totalFecha, 2) }}</td>
        @endforeach
        <td class="text-end">
            {{ number_format($abonos->collapse()->sum('monto_cobrado'), 2) }}
        </td>
    </tr>
</tfoot>
@endsection