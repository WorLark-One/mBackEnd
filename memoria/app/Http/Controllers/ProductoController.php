<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Validator;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            $producto = Producto::all();
            return response()->json(['code' => '200','data' => $producto], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //$producto = request->all();
        $validator = Validator::make($request->all(), $this->rulesValidation());
        if ($validator->fails()) {
            return response()->json(['code' => '400', 'message' => 'Request not valid for create product'], 400);
        }
        try {
            $find = Producto::where('link', $request->link)->first();
            if ($find == null) {
                $producto = new Producto();
                $producto->titulo = $request->titulo;
                $producto->descripcion = $request->descripcion;
                $producto->precio = $request->precio;
                $producto->imagen = $request->imagen;
                $producto->ubicacion = $request->ubicacion;
                $producto->link = $request->link;
                $producto->marketplace = $request->marketplace;
                $producto->save();
            } else {
                $find->titulo = $request->titulo;
                $find->descripcion = $request->descripcion;
                $find->precio = $request->precio;
                $find->imagen = $request->imagen;
                $find->ubicacion = $request->ubicacion;
                $find->link = $request->link;
                $find->marketplace = $request->marketplace;
                $find->save();
                return response()->json(['code' => '200','message' => 'Product updated'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Product created'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $producto = Producto::findOrFail($id);
            return response()->json(['code' => '200','data' => $producto], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }

    /**
    * Rules validation of request plan
    * @return array of rules validation
    */
    public function rulesValidation()
    {
        $rules = [
            'titulo' => 'required|string',
            'descripcion' => 'nullable',
            'precio' => 'required|integer',
            'imagen' => 'required|string',
            'ubicacion' => 'required|string',
            'link' => 'required|string',
            'marketplace' => 'required|string'
        ];
        return $rules;
    }
}
