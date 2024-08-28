@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<style>
        .rojo {
            background-color: red;
        }
        .verde {
            background-color: green;
        }
    </style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center"><h2>ESTACIONAMIENTO UTN</h2></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row mt-2">
                        <div class="col-sm-4 p-2" style="height: 130px">
                            <div class="text-center d-flex align-items-center justify-content-center text-white" id="sensor-div" style="width:100%; height: 100%">
                                <h1>1</h1>
                            </div>
                        </div>
                        <div class="col-sm-4 p-2" style="height: 130px;">
                            <div class="verde text-center d-flex align-items-center justify-content-center text-white" style="width:100%; height: 100%">
                                <h1>2</h1>
                            </div>
                        </div>
                        <div class="col-sm-4 p-2" style="height: 130px">
                        <div class="verde text-center d-flex align-items-center justify-content-center text-white" style="width:100%; height: 100%">
                                <h1>3</h1>
                            </div>
                        </div>
                        </div>
                        <div class="row mt-4">
                        <div class="col-sm-4 p-2" style="height: 130px">
                        <div class="verde text-center d-flex align-items-center justify-content-center text-white" style="width:100%; height: 100%">
                                <h1>4</h1>
                            </div>
                        </div>
                        <div class="col-sm-4 p-2" style="height: 130px;">
                        <div class="verde text-center d-flex align-items-center justify-content-center text-white" style="width:100%; height: 100%">
                                <h1>5</h1>
                            </div>
                        </div>
                        <div class="col-sm-4 p-2" style="height: 130px">
                        <div class="verde text-center d-flex align-items-center justify-content-center text-white" style="width:100%; height: 100%">
                                <h1>6</h1>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
<script>
        $(document).ready(function() {
            actualizarEstado();
            // Maneja el clic en el botón de Activar
            $('#activar').click(function() {
                enviarEstado(1, 1); // id = 1, estado = 1
            });

            // Maneja el clic en el botón de Desactivar
            $('#desactivar').click(function() {
                enviarEstado(1, 0); // id = 1, estado = 0
            });

            function enviarEstado(id, estado) {
                $.ajax({
                    url: '/actualizar_estado?id=' + id + '&estado=' + estado,
                    type: 'POST',
                    data: {
                        id: id,
                        estado: estado,
                        _token: '{{ csrf_token() }}' // Asegúrate de incluir el token CSRF
                    },
                    success: function(response) {
                        console.log(response)
                    },
                    error: function(xhr) {
                        // Maneja cualquier error
                        console.log(xhr)
                    }
                });
            }
        });
    </script>
<script defer>
        async function actualizarEstado() {
            try {
                const response = await fetch('/sensor-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    const estado = data.estado;
                    const div = document.getElementById('sensor-div');

                    if (estado) {
                        div.classList.remove('verde');
                        div.classList.add('rojo');
                    } else {
                        div.classList.remove('rojo');
                        div.classList.add('verde');
                    }
                } else {
                    console.error('Error al obtener el estado:', response.statusText);
                }
            } catch (error) {
                console.error('Error de red:', error);
            }
        }

        setInterval(actualizarEstado, 1000);
    </script>
