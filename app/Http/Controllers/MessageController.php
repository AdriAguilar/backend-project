<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Pusher\Pusher;

class MessageController extends Controller
{
    public function index()
    {
        return response()->json([
            'messages' => Message::with('user')->get()
        ]);
    }
    
    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();
        
        $chat = Chat::where('id', $request->input('chat'))->first();

        if(!$chat ) return response()->json([ 'error' => 'Chat no encontrado' ], 404);
        
        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $request->input('message'),
        ]);



        return response()->json([
           'success' => 'Mensaje enviado correctamente',
           $message
        ]);
    }

    public function show($id)
    {
        $message = Message::find($id);
        if(!$message) return response()->json([ 'error' => 'Mensaje con id ' . $id . ' no encontrado']);

        return $message->with('user')->get();
    }
}
