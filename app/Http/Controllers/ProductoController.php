<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // select * from productos
        if($request->limit){
            $limit = $request->limit;            
            /* $lista_productos = Producto::join("categorias", "productos.categoria_id", "categorias.id")
                                        ->select("categorias.nombre as nombre_categoria", "productos.*")
                                        ->paginate($limit);
                                        */
            $lista_productos = Producto::where("nombre", "like", "%".$request->q."%")->paginate($limit);
            foreach ($lista_productos as $producto) {
                $producto->categoria;
            }
            return response()->json($lista_productos, 200);
        }

        $lista_productos = Producto::get();
        return response()->json($lista_productos, 200);
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
            "nombre" => "required|min:3|max:200|string",
            "categoria_id" => "required",
            'imagen' => 'nullable|max:2000|mimes:jpeg,png', 
        ]);

        // subir la imagen
        $nombre_imagen= "";
        if($file = $request->file("imagen")){
                      
            $nombre_imagen = time() . "-" . $file->getClientOriginalName();
            $file->move("imagenes", $nombre_imagen);
        }

        // guardar datos
        $prod = new Producto;
        $prod->nombre = $request->nombre;
        $prod->precio = $request->precio;
        $prod->imagen = "/imagenes/$nombre_imagen";
        $prod->descripcion = $request->descripcion;
        $prod->categoria_id = $request->categoria_id;
        $prod->save();

        $prod->categoria;
        // responder
        return response()->json([
            "mensaje" => "Producto Registrado",
            "producto" => $prod
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::find($id);
        return response()->json($producto, 200);
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
            "nombre" => "required|min:3|max:200|string",
            "categoria_id" => "required",
            'imagen' => 'nullable|max:2000|mimes:jpeg,png', 
        ]);

        

        // guardar datos
        $prod = Producto::find($id);
        $prod->nombre = $request->nombre;
        $prod->precio = $request->precio;

        // subir la imagen
        $nombre_imagen= "";
        if($file = $request->file("imagen")){
                      
            $nombre_imagen = time() . "-" . $file->getClientOriginalName();
            $file->move("imagenes", $nombre_imagen);
            $prod->imagen = "/imagenes/$nombre_imagen";
        }
        
        $prod->descripcion = $request->descripcion;
        $prod->categoria_id = $request->categoria_id;
        $prod->save();

        // responder
        return response()->json([
            "mensaje" => "Producto Modificado",
            "producto" => $prod
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prod = Producto::find($id);
        $prod->delete();
        // responder
        return response()->json([
            "mensaje" => "Producto Modificado",
            "producto" => $prod
        ], 200);
    }
}
