<?php

namespace App\Http\Controllers;

use Pusher\Pusher;
use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

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
        
        Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $request->input('message'),
        ]);

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );
        
        $pusher->trigger('chat.'. $chat->id, 'message-sent', $request->message);

        return response()->json([
           'success' => 'Mensaje enviado correctamente',
        ]);
    }

    public function show($id)
    {
        $message = Message::find($id);
        if(!$message) return response()->json([ 'error' => 'Mensaje con id ' . $id . ' no encontrado']);

        return $message->with('user')->get();
    }
}
