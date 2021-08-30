<?php

namespace App\Http\Controllers;

use App\Models\PrecioProducto;
use Illuminate\Http\Request;
use Validator;

class PrecioProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //
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
        
    }

    /**
     * Display the specified resource.

     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $historial = PrecioProducto::orderBy('created_at', 'DESC')
                ->where('producto_id', '=', $id)
                ->select('fecha', 'precio')
                ->distinct('fecha')
                ->take(30)
                ->get();
            return response()->json(['code' => '200','data' => $historial], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PrecioProducto  $precioProducto
     * @return \Illuminate\Http\Response
     */
    public function edit(PrecioProducto $precioProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PrecioProducto  $precioProducto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrecioProducto $precioProducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PrecioProducto  $precioProducto
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrecioProducto $precioProducto)
    {
        //
    }
}
