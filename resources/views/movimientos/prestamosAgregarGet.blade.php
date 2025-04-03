@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent
<h1>Agregar préstamos</h1>
<form method="post" action="{{url('/movimientos/prestamos/agregar')}}">
    @csrf <!-- Sirve para validar que la petición de los datos enviados provengan del formulario actual -->

    <div class="form-group mb-3">
        <label for="nombre">Empleado:</label>
        <select class="form-select" name="id_empleado" required autofocus>
            <!-- Muestra el nombre del empleado pero se envía el id -->
            @foreach($empleados as $empleado)
                <option value="{{$empleado->id_empleado}}">
                    {{$empleado->nombre}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="form-group mb-3 col-2">
            <label for="fecha_solicitud">Fecha de solicitud</label>
            <input type="date" name="fecha_solicitud" id="fecha_solicitud" class="form-control" required>
        </div>

        <div class="form-group mb-3 col-2">
            <label for="monto">Monto:</label>
            <input type="number" name="monto" id="monto" class="form-control" required>
        </div>
    </div>

    <div class="form-group mb-3 col-2">
        <label for="fecha_Apobacion">Fecha de aprobación</label>
        <input type="date" name="fecha_Apobacion" id="fecha_Apobacion" class="form-control" required>
    </div>

    <div class="form-group mb-3 col-2">
        <label for="estado">Estado</label>
        <select class="form-select" name="estado" id="estado" required>
            <option>0</option>
            <option>1</option>
        </select>
    </div>

    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</form>

<script>
    (function(){
        let inpmonto = document.getElementById("monto");

        inpmonto.addEventListener("input", function(e){
            inpsaldoactual.value = inpmonto.value;
        });

        function calculopagofijocapital(){
            if (!inpmonto.value || !inpplazo.value) {
                return;
            }
            inpagofijocap.value = inpmonto.value / inpplazo.value;
        }

        inpmonto.addEventListener("input", calculopagofijocapital);
        inpplazo.addEventListener("input", calculopagofijocapital);

        function calculofechafinplazo(){
            if (!inpplazo.value || !inpfechainidesc.value) {
                return;
            }
            let fechainicio = new Date(inpfechainidesc.value);
            let meses = parseInt(inpplazo.value);
            let fechafin = new Date(fechainicio);
            fechafin.setMonth(fechainicio.getMonth() + meses - 1);
            inpfechafindesc.value = fechafin.toISOString().slice(0, 10);
        }

        inpplazo.addEventListener("input", calculofechafinplazo);
        inpfechainidesc.addEventListener("input", calculofechafinplazo);
    })();
</script>
@endsection