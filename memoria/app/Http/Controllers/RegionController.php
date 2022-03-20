<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Validator;

class RegionController extends Controller
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
            $regiones = Region::all();
            return response()->json(['code' => '200','data' => $regiones], 200);
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
        $validator = Validator::make($request->all(), $this->rulesValidation());
        if ($validator->fails()) {
            return response()->json(['code' => '400', 'message' => 'Request not valid for create region'], 400);
        }
        try {
            $region = new Region();
            $region->identificadorRomano = $request->identificadorRomano;
            $region->nombre = $request->nombre;
            $region->save();
            
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Region created'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
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
         $validator = Validator::make($request->all(), $this->rulesValidation2());
        if ($validator->fails()) {
            return response()->json(['code' => '400', 'message' => 'Request not valid for update region'], 400);
        }
        try {
            $validarRomano = Region::where("identificadorRomano", "=", $request->identificadorRomano)->where("id","!=", $id )->get();
            $validarNombre = Region::where("nombre", "=", $request->nombre)->where("id", "!=", $id )->get();
            if (!$validarRomano->isEmpty()){
                return response()->json(['code' => '400', 'message' => 'Request not valid for update region'], 400);
            } elseif(!$validarNombre->isEmpty()){
                return response()->json(['code' => '400', 'message' => 'Request not valid for update region'], 400);
            } else {
                $region = Region::findOrFail($id);
                $region->identificadorRomano = $request->identificadorRomano;
                $region->nombre = $request->nombre;
                $region->save();
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Region updated'], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $find = Region::findOrFail($id);
            $find->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Region deleted'], 200);
    }

    /**
    * Rules validation of request comuna
    * @return array of rules validation
    */
    public function rulesValidation()
    {
        $rules = [
            'identificadorRomano' => 'required|string|unique:regiones,identificadorRomano',
            'nombre' => 'required|string|unique:regiones,nombre',
        ];
        return $rules;
    }


    /**
    * Rules validation of request comuna
    * @return array of rules validation
    */
    public function rulesValidation2()
    {
        $rules = [
            'identificadorRomano' => 'required|string',
            'nombre' => 'required|string',
        ];
        return $rules;
    }

    function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);
 
		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );
 
		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );
 
		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );
 
		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );
 
		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}
}
