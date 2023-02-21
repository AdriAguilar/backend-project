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
        
        // Crear token
        $token = $user->createToken('access_token')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }
    public function login(Request $request)
    {
        // Username or email
        $field = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        

        // Validar
        $data = Validator::make($request->all() ,[
            'username' => 'required|min:3|max:25',
            'password' => 'required',
            $field => ( $field === 'email' ) ? 'email': 'required|min:3|max:25'

        ]);

        if( $data->fails() ) {
            return response()->json($data->errors(), 400);
        }

        $request->merge([$field => $request->input('username')]);
        $credentials = $request->only($field, 'password');

        if( !Auth::attempt( $credentials ) ) {
            return response()->json(['error' => 'Credenciales incorrectas.'], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('access_token')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout()
    {
        $user = auth()->guard('api')->user();

        foreach ($user->tokens as $token) {
            $token->delete();
        }
        
        return response()->json([
            'success' => 'Sesión cerrada con éxito',
            'user' => $user
        ]);
    }

    public function whoIsLogged() {
        return response()->json( Auth::guard('api')->user() );
    }
}
