<?php

namespace App\Http\Controllers;

use App\Models\MiListaUser;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\User;
use Validator;

class MiListaUserController extends Controller
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
        //
        $validator = Validator::make($request->all(), $this->rulesValidation());
        if ($validator->fails()) {
            return response()->json(['code' => '400', 'message' => 'Request not valid for create a new item of UserList'], 400);
        }
        try {
            $usuario = User::findOrFail($request->usuario_id);
            $producto = Producto::findOrFail($request->producto_id);
            $miListaUser = new MiListaUser();
            $miListaUser->producto_id = $request->producto_id;
            $miListaUser->usuario_id = $request->usuario_id;
            $miListaUser->save();
            
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
            $productoMiLista = MiListaUser::where('usuario_id',$usuario_id)->where('producto_id', $id)->firstOrFail();
            if($productoMiLista->usuario_id == $usuario_id){
                $productoMiLista->delete();
            } else {
                return response()->json(['code' => '400','message' => 'Only the user who add product to UserList can delete it'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Product of UserList deleted'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyAux($id, $usuario_id)
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function onUserList($usuario_id, $producto_id)
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function userList($usuario_id)
    {
        //
        $productosMiLista = array();
        try {
            $usuario = User::findOrFail($usuario_id);
            $listaUsuario = MiListaUser::where('usuario_id', $usuario_id)->get();
            if($listaUsuario->isEmpty()) {
                return response()->json(['code' => '200','data' => $productosMiLista], 200);
            } else {
                foreach($listaUsuario as $aux){
			        $producto = Producto::findOrFail($aux->producto_id);
                    array_push($productosMiLista , $producto);
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','data' => $productosMiLista], 200);
    }


    /**
    * Rules validation of request mi lista user
    * @return array of rules validation
    */
    public function rulesValidation()
    {
        $rules = [
            'producto_id' => 'required|integer',
            'usuario_id' => 'required|integer',
        ];
        return $rules;
    }
}
