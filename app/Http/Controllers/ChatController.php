<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return Chat::all();
    }

    public function store(Request $request)
    {
        $user_1 = User::where('id', $request->input('user_1'))->first();
        if( !$user_1 ) return response()->json([ 'error' => 'Usuario con id ' . $request->input('user_1') . ' no encontrado' ], 404);

        $user_2 = User::where('id', $request->input('user_2'))->first();
        if( !$user_2 ) return response()->json([ 'error' => 'Usuario con id ' . $request->input('user_2') . ' no encontrado' ], 404);

        if ( $user_1 == $user_2 ) return response()->json([ 'error' => 'No se puede crear un chat consigo mismo' ], 400);

        if( Chat::where('user_1', $user_1->id)->where('user_2', $user_2->id)->exists() || Chat::where('user_1', $user_2->id)->where('user_2', $user_1->id)->exists() ) {
            $chats = Chat::where(function ($query) use ($user_1, $user_2) {
                $query->where('user_1', $user_1->id)
                      ->where('user_2', $user_2->id);
            })->orWhere(function ($query) use ($user_1, $user_2) {
                $query->where('user_1', $user_2->id)
                      ->where('user_2', $user_1->id);
            })->first();
            return response()->json($chats, 200);
        }
        
        $chat = Chat::create([
            'user_1' => $user_1->id,
            'user_2' => $user_2->id
        ]);
        
        return response()->json($chat, 201);
    }

    public function show($id)
    {
        $chat = Chat::find($id);
        if( !$chat ) return response()->json([ 'error' => 'Chat con id ' . $id . ' no encontrado' ], 404);

        $users = User::where('id', $chat->user_1)->orWhere('id', $chat->user_2)->get();
        
        $messages = $chat->messages()->with('user')->orderBy('created_at', 'asc')->get();
        
        return response()->json([
            'data' => [
                'chat' => $users,
                'messages' => $messages
            ],
        ], 200);
    }
}
