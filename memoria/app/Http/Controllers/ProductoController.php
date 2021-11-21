<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\PrecioProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Models\MiListaUser;
use App\Models\User;
use App\Models\NotificacionUser;
use App\Mail\ProductoEnDescuento;
use App\Notifications\NotificationUser;
use App\Events\DescuentoUser;
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

    public function homeProducts() {
        try {
            $productosDescuento= Producto::orderBy('descuento', 'DESC')->take(3)->get();;
            $productosTendencia = Producto::orderBy('puntaje_tendencia', 'DESC')->take(11)->get();;
            return response()->json(['code' => '200','data_tendencia' => $productosTendencia, 'data_descuento' => $productosDescuento], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }
        
    }

    /**
     * Buscar producto
     * @param  \Illuminate\Http\Request  $request
    */
    public function search($producto, $comuna, $orientacion, $marketplace, $rangoprecio, $valoracion, $paginacion){
        if($producto && $comuna && $orientacion && $marketplace && $rangoprecio && $valoracion >= 0 && $paginacion){
            try {
                $p = null;
                $precioMinRango = 0;
                $PrecioMaxRango = 1;
                if($comuna == 'Todas') {
                    if($orientacion == "descuento"){
                        $p = Producto::orderBy('descuento', 'DESC')->where('titulo', 'LIKE', "%$producto%");
                    } else if ($orientacion == "tendencia"){
                        $p = Producto::orderBy('puntaje_tendencia', 'DESC')->where('titulo', 'LIKE', "%$producto%");
                    } else {
                        $p = Producto::orderBy('precio', $orientacion)->where('titulo', 'LIKE', "%$producto%");
                    }
                    
                } else {
                    if($orientacion == "descuento"){
                        $p = Producto::orderBy('descuento', 'DESC')->where('titulo', 'LIKE', "%$producto%")->where('ubicacion', '=', $comuna);
                    } else if ($orientacion == "tendencia"){
                        $p = Producto::orderBy('puntaje_tendencia', 'DESC')->where('titulo', 'LIKE', "%$producto%")->where('ubicacion', '=', $comuna);
                    } else {
                        $p = Producto::orderBy('precio', $orientacion)->where('titulo', 'LIKE', "%$producto%")->where('ubicacion', '=', $comuna);
                    }
                }
                if($marketplace !='ComunidadC+marketmaule+MercadoLibre'){
                    $auxMarketPLace = explode("+", $marketplace);
                    if(count($auxMarketPLace) == 2) {
                        $p = $p->whereIn('marketplace', [ $auxMarketPLace[0],$auxMarketPLace[1]]);
                    } else {
                        $p = $p->where('marketplace', '=', $marketplace);
                    }
                }
                $max = $p->max('precio');
                if ($rangoprecio != 'Todos') {
                    $auxRangoPrecio = explode("to", $rangoprecio);
                    if (count($auxRangoPrecio) == 2) {
                        $precioMinRango = intval($auxRangoPrecio[0]);
                        $PrecioMaxRango = intval($auxRangoPrecio[1]);
                        $p = $p->where('precio', '>=', $precioMinRango)->where('precio', '<=',$PrecioMaxRango);
                    }
                } else {
                    $precioMinRango = 0;
                    $PrecioMaxRango = $max;
                }
                $p = $p->where('valoracion', '>=', $valoracion);
                //$paginatedResult = ColectionPaginate::paginate($p, 10);
                $p = $p->get();
                return response()->json(['code' => '200','data' => $p, 'totalProductos' => $p->count(), 
                    'precioMaximo' => $max, 'precioMinRango' => $precioMinRango, 'precioMaxRango' => $PrecioMaxRango], 200);
            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()],400);
            }
        }
        return response()->json(['code' => '400','message' => 'Get not valid for search product'], 200);
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
        $flagDescuento = false;
        $precio_anterior_producto;
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
                $producto->visualizaciones= 0;
                $producto->puntaje_tendencia= 0;
                $producto->descuento = 0;
                $producto->fecha_descuento = date('Y-m-d');
                $producto->save();
                $precioProducto = new PrecioProducto();
                $precioProducto->producto_id = $producto->id;
                $precioProducto->precio = $request->precio;
                $precioProducto->fecha = date('Y-m-d');
                $precioProducto->save();
            } else {
                $precio_anterior_producto = $find->precio; 
                if($find->descuento == 0 ) {
                    $descuento = $this->obtenerDescuento($request->precio, $find->precio);
                    if($descuento > 0) {
                        $find->descuento = $descuento;
                        $find->fecha_descuento = date('Y-m-d');
                        //enviar notificaciones y correos
                        $flagDescuento = true;
                        
                    } else {
                        $find->descuento = 0;
                        $find->fecha_descuento = date('Y-m-d');
                    }
                } else if ($find->descuento > 0){
                    if ($find->precio == $request->precio){
                        $date = date("Y-m-d", strtotime("-5 day"));
                        if($find->fecha_descuento < $date){
                            $find->descuento = 0;
                            $find->fecha_descuento = date('Y-m-d');
                        }
                    }
                    else if($request->precio < $find->precio) {
                        $descuento = $this->obtenerDescuento($request->precio, $find->precio);
                        $find->descuento = $descuento;
                        $find->fecha_descuento = date('Y-m-d');
                        //enviar notificaciones y correos
                        $flagDescuento = true;
                    } else {
                        $find->descuento = 0;
                        $find->fecha_descuento = date('Y-m-d');
                    }
                }
                $find->titulo = $request->titulo;
                $find->descripcion = $request->descripcion;
                $find->precio = $request->precio;
                $find->imagen = $request->imagen;
                $find->ubicacion = $request->ubicacion;
                $find->link = $request->link;
                $find->puntaje_tendencia = $this->obtenerTendencia($find->visualizaciones, $find->descuento, $find->valoracion);
                $find->marketplace = $request->marketplace;
                $precioProducto = new PrecioProducto();
                $precioProducto->producto_id = $find->id;
                $precioProducto->precio = $request->precio;
                $precioProducto->fecha = date('Y-m-d');
                $find->save();
                $precioProducto->save();
                if ($flagDescuento){
                    $this->enviarEmailDescuento($find, $precio_anterior_producto);
                }
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
    public function mostrar($id)
    {
        //
        try {
            $producto = Producto::findOrFail($id);
            $producto->visualizaciones = $producto->visualizaciones + 1;
            $producto->puntaje_tendencia = $this->obtenerTendencia($producto->visualizaciones, $producto->descuento, $producto->valoracion);
            $producto->save();
            return response()->json(['code' => '200','data' => $producto], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productoVisitado($id)
    {
        //
        try {
            $producto = Producto::findOrFail($id);
            $producto->visualizaciones = $producto->visualizaciones + 1;
            $producto->puntaje_tendencia = $this->obtenerTendencia($producto->visualizaciones, $producto->descuento, $producto->valoracion);
            $producto->save();
            return response()->json(['code' => '200','view' => true], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
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

    /**
     * Display the specified resource.
     *
     * @return descuento
     */
    public function obtenerDescuento($precio, $precioAnterior) {
        $descuento = 0;
        if($precioAnterior != null) {
            $descuento = 100 - (($precio * 100)/$precioAnterior);
        }
        return intval($descuento);
    }

    public function obtenerPorcentajeEstrellas($valoracion) {
        $porcentaje = ((float)$valoracion * 100) / 5; // Regla de tres
        $porcentaje = round($porcentaje, 0);  // Quitar los decimales
        return $porcentaje;
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

    public function enviarEmailDescuento($producto, $precio_anterior_producto)
    {
        $lista = MiListaUser::where('producto_id', $producto->id)->get();
        if(!$lista->isEmpty()){
            foreach($lista as $aux){
                $usuario = User::findOrFail($aux->usuario_id);
                Mail::to($usuario->email)->queue(new ProductoEnDescuento($producto, $precio_anterior_producto));
                $this->sendNotificacion($aux->usuario_id, $producto);
            }
        }
    }

    public function getNotificacionUser(){
        return auth()->user()->notifications;
    }

    public function markReadNotificacion(){
        $user = auth()->user();
        $user->unreadNotifications->marAsRead();
        return $user->notifications;
    }

    public function sendNotificacion($user_id, $producto) {
        $notify =  new NotificacionUser();
        $notify->producto_id = $producto->id;
        $notify->usuario_id = $user_id;
        $notify->nombre_producto = $producto->titulo;
        $notify->precio_producto = $producto->precio;
        $notify->descuento_producto = $producto->descuento;
        $notify->save();
        event(new DescuentoUser($user_id));
    }

    public function nuevoDescuento($id_producto, $descuento){
        try {
            $find = Producto::findOrFail($id_producto);
            $precio_anterior_producto = $find->precio; 
            $aux = (($find->precio)/100) * $descuento;
            $nuevo_precio = $precio_anterior_producto - $aux; 
            if($find->descuento == 0 ) {
                $descuento = $this->obtenerDescuento($nuevo_precio, $find->precio);
                if($descuento > 0) {
                    $find->descuento = $descuento;
                    $find->fecha_descuento = date('Y-m-d');
                    $flagDescuento = true;
                } else {
                    $find->descuento = 0;
                    $find->fecha_descuento = date('Y-m-d');
                }
            } else if ($find->descuento > 0){
                if ($find->precio == $nuevo_precio){
                    $date = date("Y-m-d", strtotime("-5 day"));
                    if($find->fecha_descuento < $date){
                        $find->descuento = 0;
                        $find->fecha_descuento = date('Y-m-d');
                    }
                }
                else if($nuevo_precio < $find->precio) {
                    $descuento = $this->obtenerDescuento($nuevo_precio, $find->precio);
                    $find->descuento = $descuento;
                    $find->fecha_descuento = date('Y-m-d');
                    //enviar notificaciones y correos
                    $flagDescuento = true;
                } else {
                    $find->descuento = 0;
                    $find->fecha_descuento = date('Y-m-d');
                }
                $find->precio = $nuevo_precio;
                $find->puntaje_tendencia = $this->obtenerTendencia($find->visualizaciones, $find->descuento, $find->valoracion);
                $precioProducto = new PrecioProducto();
                $precioProducto->producto_id = $find->id;
                $precioProducto->precio = $nuevo_precio;
                $precioProducto->fecha = date('Y-m-d');
                $find->save();
                $precioProducto->save();
                if ($flagDescuento){
                    $this->enviarEmailDescuento($find, $precio_anterior_producto);
                }
                return response()->json(['code' => '200','message' => 'Product updated'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
    }

    /**
    * Rules validation of request producto
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
