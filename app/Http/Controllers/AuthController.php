<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('check.login', ['except' => ['register', 'login']]);
    }

    public function register(Request $request)
    {
        // Validar
        $request->request->add([
            'username' => Str::slug($request->username,"_"),
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id ?? 2
        ] );
        
        $data = Validator::make($request->all() ,[
            'name' => 'required|min:3|max:30',
            'username' => 'required|unique:users|min:3|max:25',
            'email' => 'required|unique:users|email',
            'password' => 'required',
            'image' => 'nullable',
            'role_id' => 'integer'
        ]);

        if( $data->fails() ) {
            return response()->json($data->errors(), 400);
        }
        
        // Crear usuario
        $user = User::create( $data->validated() );     

        return response()->json([
            'message' => 'Usuario creado correctamente!',
            'user' => $user
        ], 201);
    }
    public function login(Request $request)
    {
        // Validar
        $data = Validator::make($request->only(['username', 'password']) ,[
            'username' => 'required|min:3|max:25',
            'password' => 'required'
        ]);

        if( $data->fails() ) {
            return response()->json($data->errors(), 400);
        }
        
        if( !Auth::attempt( $data->validated() ) ) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        $user = Auth::user();

        if( $user->api_token ) {
            return response()->json(['error' => 'Ya existe un usuario logeado']);
        }
        
        $token = Str::random(60);
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Usuario logeado correctamente',
            'token' => $token
        ]);
    }

    public function logout()
    {
        $user = Auth::guard('api')->user();
        $user->api_token = null;
        $user->save();
        
        return response()->json(['message' => 'Sesión cerrada con éxito']);
    }

    public function whoIsLogged() {
        return response()->json( Auth::guard('api')->user() );
    }
}
