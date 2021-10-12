<?php

namespace App\Http\Controllers;

use App\Models\ValoracionProducto;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\User;
use Validator;

class ValoracionProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($producto_id)
    {
        //
        try {
            $producto = Producto::findOrFail($producto_id);
            $Valoraciones = ValoracionProducto::where('producto_id', '=', $producto_id)->get();
            return response()->json(['code' => '200','data' => $Valoraciones], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','data' => []], 200);
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
        $validator = Validator::make($request->all(), $this->rulesValidation());
        if ($validator->fails()) {
            return response()->json(['code' => '400', 'message' => 'Request not valid for create rating'], 400);
        }
        try {
            $usuario = User::findOrFail($request->usuario_id);
            $producto = Producto::findOrFail($request->producto_id);
            $newValoracion = new ValoracionProducto();
            $newValoracion->value = $request->value;
            $newValoracion->comentario = $request->comentario;
            $newValoracion->nombre_usuario = $request->nombre_usuario;
            $newValoracion->nombre_producto = $request->nombre_producto;
            $newValoracion->producto_id = $request->producto_id;
            $newValoracion->usuario_id = $request->usuario_id;
            $newValoracion->save();
            $AvgValoraciones = ValoracionProducto::where('producto_id','=', $request->producto_id)->avg('value');
            $CantValoraciones = ValoracionProducto::where('producto_id','=', $request->producto_id)->count();
            $producto->valoracion = $AvgValoraciones;
            $producto->cantidad_valoraciones = $CantValoraciones;
            $producto->puntaje_tendencia = $this->obtenerTendencia($producto->visualizaciones, $producto->descuento, $producto->valoracion);
            $producto->save();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Rating created'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return tendencia
     */
    public function obtenerTendencia($visualizaciones, $descuento, $valoracion) {
        $alfa = 0.33;
        $beta = 0.33;
        $gama = 0.34;
        $tendencia = 0;
        $visualizacionesAux = 100;
        $valoracionPorcentaje = $this->obtenerPorcentajeEstrellas($valoracion);
        if ($visualizaciones > 100){
            $tendencia = ($visualizacionesAux * $alfa) + ($descuento * $beta) + ($valoracionPorcentaje * $gama);
        } else {
            $tendencia = ($visualizaciones * $alfa) + ($descuento * $beta) + ($valoracionPorcentaje * $gama);
        }
        return intval($tendencia);
    }

    public function obtenerPorcentajeEstrellas($valoracion) {
        $porcentaje = ((float)$valoracion * 100) / 5; // Regla de tres
        $porcentaje = round($porcentaje, 0);  // Quitar los decimales
        return $porcentaje;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ValoracionProducto  $valoracionProducto
     * @return \Illuminate\Http\Response
     */
    public function show(ValoracionProducto $valoracionProducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ValoracionProducto  $valoracionProducto
     * @return \Illuminate\Http\Response
     */
    public function edit(ValoracionProducto $valoracionProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), $this->rulesValidation());
        if ($validator->fails()) {
            return response()->json(['code' => '400', 'message' => 'Request not valid for update rating'], 400);
        }

        try {
            $usuario = User::findOrFail($request->usuario_id);
            $newValoracion = ValoracionProducto::findOrFail($id);
            if($newValoracion->usuario_id  == $request->usuario_id) {
                if($newValoracion->producto_id == $request->producto_id && $newValoracion->id == $id) {
                    $producto = Producto::findOrFail($request->producto_id);
                    $newValoracion->value = $request->value;
                    $newValoracion->comentario = $request->comentario;
                    $newValoracion->nombre_usuario = $request->nombre_usuario;
                    $newValoracion->nombre_producto = $request->nombre_producto;
                    $newValoracion->producto_id = $request->producto_id;
                    $newValoracion->usuario_id = $request->usuario_id;
                    $newValoracion->save();
                    $AvgValoraciones = ValoracionProducto::where('producto_id','=', $request->producto_id)->avg('value');
                    $CantValoraciones = ValoracionProducto::where('producto_id','=', $request->producto_id)->count();
                    $producto->valoracion = $AvgValoraciones;
                    $producto->cantidad_valoraciones = $CantValoraciones;
                    $producto->puntaje_tendencia = $this->obtenerTendencia($producto->visualizaciones, $producto->descuento, $producto->valoracion);
                    $producto->save();
                } else {
                    return response()->json(['code' => '400','message' => 'Only the product must be updated with the same ID'], 200);
                }
            } else {
                return response()->json(['code' => '400','message' => 'Only the user who created the rating can update it'], 200);
            }   
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Rating updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ValoracionProducto  $valoracionProducto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $usuario_id)
    {
        //
        try {
            $usuario = User::findOrFail($usuario_id);
            $Valoracion = ValoracionProducto::findOrFail($id);
            if($Valoracion->usuario_id == $usuario_id){
                $Valoracion->delete();
            } else {
                return response()->json(['code' => '400','message' => 'Only the user who created the rating can delete it'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Rating deleted'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function isRating($usuario_id, $producto_id)
    {
        //
        try {
            $usuario = User::findOrFail($usuario_id);
            $producto = Producto::findOrFail($producto_id);
            $Valoracion = ValoracionProducto::where('usuario_id', '=', $usuario_id)->where('producto_id', '=', $producto_id)->get();
            if($Valoracion->isEmpty()) {
                return response()->json(['code' => '200','isRating' => false], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','isRating' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function ratingUser($usuario_id)
    {
        //
        try {
            $usuario = User::findOrFail($usuario_id);
            $Valoraciones = ValoracionProducto::where('usuario_id', '=', $usuario_id)->get();
            return response()->json(['code' => '200','data' => $Valoraciones], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','data' => []], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function detailsRating($producto_id)
    {
        //
        try {
            $producto = Producto::findOrFail($producto_id);
            $AuxValoracion = ValoracionProducto::where('producto_id','=', $producto_id)->get();
            if(!$AuxValoracion ->isEmpty()) {
                $AvgValoraciones = $AuxValoracion->avg('value');
                $CantValoraciones = $AuxValoracion->count();
                $Five = $AuxValoracion->where('value','=', 5)->count();
                $Four = $AuxValoracion->where('value','=', 4)->count();
                $Three = $AuxValoracion->where('value','=', 3)->count();
                $Two = $AuxValoracion->where('value','=', 2)->count();
                $One = $AuxValoracion->where('value','=', 1)->count();
                return response()->json(['code' => '200','Valoraciones'=> $AuxValoracion,'avg' =>  $AvgValoraciones, 'cant' =>  $CantValoraciones, 'Five' =>$Five, 'Four' => $Four, 'Three' => $Three, 'Two' => $Two, 'One' => $One], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','Valoraciones'=> [],'avg' =>  0, 'cant' =>  0, 'Five' =>0, 'Four' => 0, 'Three' => 0, 'Two' => 0, 'One' => 0], 200);
    }

    /**
    * Rules validation of request valoracion
    * @return array of rules validation
    */
    public function rulesValidation()
    {
        $rules = [
            'value' => 'required|integer',
            'comentario' => 'required|string',
            'nombre_usuario' => 'required|string',
            'nombre_producto' => 'required|string',
            'producto_id' => 'required|integer',
            'usuario_id' => 'required|integer',
        ];
        return $rules;
    }
}
