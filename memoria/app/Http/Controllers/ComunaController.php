<?php

namespace App\Http\Controllers;

use App\Models\Comuna;
use Illuminate\Http\Request;
use Validator;

class ComunaController extends Controller
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
            $comunas = Comuna::all();
            return response()->json(['code' => '200','data' => $comunas], 200);
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
            return response()->json(['code' => '400', 'message' => 'Request not valid for create comune'], 400);
        }
        try {
            $find = Comuna::where('nombre', $request->nombre)->first();
            if ($find == null) {
                $comuna = new Comuna();
                $comuna->nombre = $request->nombre;
                $comuna->region = $request->region;
                $comuna->save();
            } else {
                $find->nombre = $request->nombre;
                $find->region = $request->region;
                $find->save();
                return response()->json(['code' => '200','message' => 'Comune updated'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Comune created'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comuna  $comuna
     * @return \Illuminate\Http\Response
     */
    public function show(Comuna $comuna)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comuna  $comuna
     * @return \Illuminate\Http\Response
     */
    public function edit(Comuna $comuna)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comuna  $comuna
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), $this->rulesValidation());
        if ($validator->fails()) {
            return response()->json(['code' => '400', 'message' => 'Request not valid for update comune'], 400);
        }
        try {
            $find = Comuna::findOrFail($id);
            $find->nombre = $request->nombre;
            $find->region = $request->region;
            $find->save();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Comune updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comuna  $comuna
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $find = Comuna::findOrFail($id);
            $find->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Comune deleted'], 200);
    }


    /**
    * Rules validation of request comuna
    * @return array of rules validation
    */
    public function rulesValidation()
    {
        $rules = [
            'nombre' => 'required|string',
            'region' => 'required|string',
        ];
        return $rules;
    }
}
