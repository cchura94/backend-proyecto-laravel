<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sucursal;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista_sucursales = Sucursal::all();

        foreach ($lista_sucursales as $sucursal) {
            $sucursal->productos;
        }

        return response()->json($lista_sucursales, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validar
        $request->validate([
            "nombre" => "required|max:200",
            "direccion" => "required|max:200",
            "telefono" => "required|max:15",
            "user_id" => "required"
        ]);

        // guardar
        $sucursal = new Sucursal;
        $sucursal->nombre = $request->nombre;
        $sucursal->direccion = $request->direccion;
        $sucursal->telefono = $request->telefono;
        $sucursal->user_id = $request->user_id;
        $sucursal->save();

        return response()->json(["mensaje" => "Sucursal Registrado"], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sucursal = Sucursal::find($id);
        return response()->json($sucursal, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        // validar
        $request->validate([
            "nombre" => "required|max:200",
            "direccion" => "required|max:200",
            "telefono" => "required|max:15",
            "user_id" => "required"
        ]);

        // guardar
        $sucursal = Sucursal::find($id);
        $sucursal->nombre = $request->nombre;
        $sucursal->direccion = $request->direccion;
        $sucursal->telefono = $request->telefono;
        $sucursal->user_id = $request->user_id;
        $sucursal->save();

        return response()->json(["mensaje" => "Sucursal Modificado"], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sucursal = Sucursal::find($id);
        $sucursal->delete();
        return response()->json(["mensaje" => "Sucursal Eliminado"], 200);

    }
}
