<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href={{ secure_asset('bootstrap-5.3.3-dist/css/bootstrap.min.css') }} />
    <script src={{ secure_asset('bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js') }}></script>
    <link href={{ secure_asset('DataTables/datatables.min.css')}} rel="stylesheet" />
    <script src={{ secure_asset('DataTables/datatables.min.js')}}></script>
    <link href={{ secure_asset("assets/style.css")}} rel="stylesheet" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos Coliman</title>
</head>
<body>
    <div class="row">
        <div class="col-2">
            @component("components.sidebar")
            @endcomponent
        </div>
        <div class="col-10">
            <div class="container">
                @section("content")
                @show
            </div>
        </div>
    </div>
    
</body>
</html>