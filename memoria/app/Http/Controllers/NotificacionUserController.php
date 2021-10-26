<?php

namespace App\Http\Controllers;

use App\Models\NotificacionUser;
use Illuminate\Http\Request;
use App\Models\User;

class NotificacionUserController extends Controller
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
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($usuario_id)
    {
        //
        $notifications = [];
        $newNotifications = 0;
        try {
            $usuario = User::findOrFail($usuario_id);
            $notifications = NotificacionUser::where('usuario_id',$usuario_id)->get();
            $newNotifications = $notifications->where('notificacion_leida',0)->count();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','data' => $notifications, 'count' => $newNotifications], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function readNotify($usuario_id)
    {
        try {
            $usuario = User::findOrFail($usuario_id);
            $notifications = NotificacionUser::where('usuario_id',$usuario_id)->where('notificacion_leida',0)->get();
            if(!$notifications->isEmpty()){
                foreach($notifications  as $aux){
                    $notify = NotificacionUser::findOrFail($aux->id);
                    $notify->notificacion_leida = 1;
                    $notify->save();
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Notifications updated'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NotificacionUser  $notificacionUser
     * @return \Illuminate\Http\Response
     */
    public function edit(NotificacionUser $notificacionUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NotificacionUser  $notificacionUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NotificacionUser $notificacionUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $usuario_id)
    {
        //
        try {
            $usuario = User::findOrFail($usuario_id);
            $notify = NotificacionUser::findOrFail($id);
            if($usuario_id == $notify->usuario_id){
                $notify->delete();
            } else {
                return response()->json(['code' => '400','message' => 'Only the user owner can delete the notification'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()],400);
        }
        return response()->json(['code' => '200','message' => 'Notification deleted'], 200);
    }
}
