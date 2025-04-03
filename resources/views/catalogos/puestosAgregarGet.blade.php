@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent
<div class="row">
    <div class="form-group my-3">
        <h1>Agregar puesto</h1>
    </div>
    <div class="col"></div>
</div>

<form method="post" action="{{url('/catalogos/puestos/agregar')}}">
    @csrf <!-- sirve para validar que la petición de los datos enviados provengan del formulario actual, evitando un ataque de tipo CSRF -->
    <div class="row my-2">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" maxlength="50" name="nombre" id="nombre" placeholder="Ingrese nombre del puesto" class="form-control" required autofocus>
        </div>
    </div>
    <div class="form-group">
        <label for="sueldo">Sueldo</label>
        <input type="number" name="sueldo" id="sueldo" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="estado">Estado</label>
        <select name="estado" id="estado" class="form-control" required>
            <option value="1">1</option>
            <option value="0">0</option>
        </select>
    </div>
    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            <br>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</form>
@endsection
