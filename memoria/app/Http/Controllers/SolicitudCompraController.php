<?php

namespace App\Http\Controllers;

use App\Models\SolicitudCompra;
use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Comuna;
use Validator;

class SolicitudCompraController extends Controller
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
        /**
        $rules = [
            'usuario_id' => 'required|integer',
            'direccion'=> 'required|string',
            'numero' => 'required|integer',
            'otra_info'=> 'required|string',
            'celular' => 'required|integer',
            'region_id' => 'required|integer',
            'comuna_id' => 'required|integer',
            'pedido' => 'required', 
            'estado_id' => 'required|integer',
        ];

        $rules = [
            'usuario_id' => 'required|integer',
            'direccion'=> 'required|string',
            'numero' => 'required|integer',
            'otra_info'=> 'required|string',
            'celular' => 'required|integer',
            'region' => 'required|string',
            'comuna' => 'required|string',
            'pedido' => 'required'
        ];
         */
        $validator = Validator::make($request->all(), $this->rulesValidation());
        if ($validator->fails()) {
            return response()->json(['code' => '400', 'message' => 'Request not valid for create a new purchase request'], 400);
        }
        try {
            $usuario = User::findOrFail($request->usuario_id);
            $region = Region::where($request->region, 'nombre')->first();
            $comuna = Comuna::where($request->comuna, 'nombre')->first();
            if (!is_null($region) && !is_null($comuna)) {
                $newProductoCartUsuario = new MiCartUser();
                $newProductoCartUsuario->producto_id = $request->producto_id;
                $newProductoCartUsuario->usuario_id = $request->usuario_id;
                $newProductoCartUsuario->save();
                $newSolicitudCompra = new SolicitudCompra();
                $newSolicitudCompra->usuario_id = $request->usuario_id;
                $newSolicitudCompra->direccion = $request->direccion;
                $newSolicitudCompra->numero = $request->numero;
                $newSolicitudCompra->otra_info = $request->otra_info;
                $newSolicitudCompra->region_id = $region->id;
                $newSolicitudCompra->comunda_id = $comuna->id;
                $newSolicitudCompra->pedido =  $request->pedido;
                $newSolicitudCompra->estado_id = 1;
            } else {
                $cartUsuario->cantidad = $cartUsuario->cantidad + 1;
                $cartUsuario->save();
                return response()->json(['code' => '200','message' => 'Product plus from CartUser'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Item of UserList created'], 200);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MiListaUser  $miListaUser
     * @return \Illuminate\Http\Response
     */
    public function show(MiListaUser $miListaUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MiListaUser  $miListaUser
     * @return \Illuminate\Http\Response
     */
    public function edit(MiListaUser $miListaUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MiListaUser  $miListaUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MiListaUser $miListaUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $usuario_id)
    {
        //
        try {
            $usuario = User::findOrFail($usuario_id);
            $producto = Producto::findOrFail($id);
            $cartUsuario = MiCartUser::where('usuario_id', $usuario_id)->where('producto_id', $id)->first();
            if($cartUsuario->usuario_id == $usuario_id){
                if ($cartUsuario->cantidad > 1) {
                    $cartUsuario->cantidad = $cartUsuario->cantidad - 1;
                    $cartUsuario->save();
                    return response()->json(['code' => '200','message' => 'Product subtracted from CartUser'], 200);
                } else {
                    $cartUsuario->delete();
                }
            } else {
                return response()->json(['code' => '400','message' => 'Only the user who add product to UserList can delete it'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Product of CartUser deleted'], 200);
    }

        /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyRaiz($id, $usuario_id)
    {
        //
        try {
            $usuario = User::findOrFail($usuario_id);
            $producto = Producto::findOrFail($id);
            $cartUsuario = MiCartUser::where('usuario_id', $usuario_id)->where('producto_id', $id)->first();
            if($cartUsuario->usuario_id == $usuario_id){
                $cartUsuario->delete();
            } else {
                return response()->json(['code' => '400','message' => 'Only the user who add product to UserList can delete it'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Product of CartUser deleted'], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function destroyAux($id, $usuario_id)
    {
        //
        try {
            $usuario = User::findOrFail($usuario_id);
            $productoMiLista = MiListaUser::findOrFail($id);
            if($productoMiLista->usuario_id == $usuario_id){
                $productoMiLista->delete();
            } else {
                return response()->json(['code' => '400','message' => 'Only the user who add product to UserList can delete it'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Product of UserList deleted'], 200);
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function onUserList($usuario_id, $producto_id)
    {
        //
        try {
            $usuario = User::findOrFail($usuario_id);
            $producto = Producto::findOrFail($producto_id);
            $productoMiLista = MiListaUser::where('usuario_id', '=', $usuario_id)->where('producto_id', '=', $producto_id)->get();
            if($productoMiLista->isEmpty()) {
                return response()->json(['code' => '200','onUserList' => false], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','onUserList' => true], 200);
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function userCartList($usuario_id)
    {
        //
        $productosMiCart= array();
        try {
            $usuario = User::findOrFail($usuario_id);
            $cartUsuario = MiCartUser::where('usuario_id', $usuario_id)->get();
            if($cartUsuario->isEmpty()) {
                return response()->json(['code' => '200','data' => $productosMiCart], 200);
            } else {
                foreach($cartUsuario as $aux){
			        $producto = Producto::findOrFail($aux->producto_id);
                    $producto->cantidad = $aux->cantidad;
                    array_push($productosMiCart , $producto);
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','data' => $productosMiCart], 200);
    }


    /**
    * Rules validation of request mi lista user
    * @return array of rules validation
    */
    public function rulesValidation()
    {
        $rules = [
            'usuario_id' => 'required|integer',
            'direccion'=> 'required|string',
            'numero' => 'required|integer',
            'otra_info'=> 'required|string',
            'celular' => 'required|integer',
            'region' => 'required|string',
            'comuna' => 'required|string',
            'pedido' => 'required'
        ];
        return $rules;
    }
}
