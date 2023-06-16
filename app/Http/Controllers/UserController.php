<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id) ?? response()->json(['msg' => 'Usuario con id '.$id.' no encontrado'], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if( !$user ) return response()->json(['error' => 'Usuario con id '.$id.' no encontrado'], 404);

        $file = $request->file('image');
        if ($file) {
            $imageName = uniqid(time() . '_') . '.' . $file->extension();
            $imagePath = $file->storeAs('public/images/profiles', $imageName);
    
            // Eliminar la imagen anterior si existe
            if ($user->image) {
                Storage::delete(str_replace('/storage', 'public', $user->image));
            }
    
            $user->image = Storage::url($imagePath);
        }

        $user->name = $request->input('name') ?? $user->name;
        $user->username = $request->input('username') ?? $user->username;
        $user->email = $request->input('email') ?? $user->email;

        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    // Relaciones
    
    public function role($id)
    {
        $user = User::find($id);
        return $user->role ?? response()->json(['msg' => 'Usuario con id '.$id.' no encontrado'], 404);
    }

    public function purchases($id)
    {
        $purchases = User::find($id)->purchases;
        return $purchases ?? response()->json(['msg' => 'Usuario con id '.$id.' no encontrado'], 404);
    }

    public function comments($id)
    {
        $user = User::find($id);
        return $user->comments ?? response()->json(['msg' => 'Usuario con id '.$id.' no encontrado'], 404);
    }

    public function products($id)
    {
        $user = User::find($id);
        return $user->products ?? response()->json(['msg' => 'Usuario con id '.$id.' no encontrado'], 404);
    }
    
    public function chats($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['msg' => 'Usuario con id '.$id.' no encontrado'], 404);
        }
    
        $chats = Chat::where('user_1', $id)
                    ->orWhere('user_2', $id)
                    ->get();
    
        return $chats;
    }
}
