<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
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
}
