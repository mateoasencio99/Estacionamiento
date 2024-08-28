<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SensorEstado;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        // Obtén los parámetros de la solicitud
        $id = $request->query('id'); // Obtiene el parámetro 'id' de la URL
        $estado = $request->query('estado'); // Obtiene el parámetro 'estado' de la URL

        // Validar los datos opcionalmente
        if (is_null($id) || is_null($estado)) {
            return response()->json(['error' => 'Faltan parámetros'], 400);
        }

        $sensorEstado = SensorEstado::create([
            'sensor' => $request->input('id'),
            'estado' => $request->input('estado'),
        ]);


        // Retorna los datos en formato JSON
        return response()->json([
            'id' => $id,
            'estado' => $estado
        ]);
    }

    // public function store(Request $request)
    // {
    //     // Validar la solicitud
    //     $request->validate([
    //         'id' => 'required|integer',
    //         'estado' => 'required|boolean',
    //     ]);

    //     // Crear una nueva entrada en la tabla
    //     $sensorEstado = SensorEstado::create([
    //         'sensor' => $request->input('id'),
    //         'estado' => $request->input('estado'),
    //     ]);

    //     // Devolver una respuesta
    //     return response()->json($sensorEstado, 201);
    // }
}
