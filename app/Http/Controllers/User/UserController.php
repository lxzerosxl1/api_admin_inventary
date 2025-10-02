<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get("search");

        $users = User::where("name", "LIKE", "%{$search}%")->get();

        return response()->json([
            "users" => $users->map(function ($user) {
                return [
                    "id" => $user->id,
                    "name" => $user->name,
                    "apellido" => $user->apellido,
                    "fullname" => $user->name . " " . $user->apellido,
                    "genero" => $user->genero,
                    "telefono" => $user->telefono,
                    "email" => $user->email,
                    "role_id" => $user->role_id,
                    "role" => [
                        "name" => $user->role->name,
                    ],
                    "sede_id" => $user->sede_id,
                    "sede" => [
                        "name" => $user->sede->name,
                    ],
                    "tipo_documento" => $user->tipo_documento,
                    "numero_documento" => $user->numero_documento,
                    "foto" => $user->foto ? env('APP_URL') . '/storage/' . $user->foto : null,
                    "created_at" => $user->created_at->format('Y-m-d A h:i'),
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_user_exist = User::where("email", $request->email)->first();

        if ($is_user_exist) {
            return response()->json([
                "message" => 403,
                "message_text" => "El usuario ya existe",
            ]);
        }

        if($request->hasFile("foto")) {
            $path = Storage::putFile('users', $request->file('foto'));
            $request->request->add(['foto' => $path]);
        }

        if($request->password) {
            $request->request->add(['password' => bcrypt($request->password)]);
        }

        $user = User::create($request->all());
        $rol = Role::findOrFail($request->role_id);

        $user->assignRole($rol);

        return response()->json([
            "message" => 200,
            "message_text" => "El usuario se registro correctamente",
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "apellido" => $user->apellido,
                "fullname" => $user->name . " " . $user->apellido,
                "genero" => $user->genero,
                "telefono" => $user->telefono,
                "email" => $user->email,
                "role_id" => $user->role_id,
                "role" => [
                    "name" => $user->role->name,
                ],
                "sede_id" => $user->sede_id,
                "sede" => [
                    "name" => $user->sede->name,
                ],
                "tipo_documento" => $user->tipo_documento,
                "numero_documento" => $user->numero_documento,
                "foto" => $user->foto ? env('APP_URL') . '/storage/' . $user->foto : null,
                "created_at" => $user->created_at->format('Y-m-d A h:i'),
            ]
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $is_user_exist = User::where("email", $request->email)->where("id", "<>", $id)->first();

        if ($is_user_exist) {
            return response()->json([
                "message" => 403,
                "message_text" => "El usuario ya existe",
            ]);
        }

        $user = User::findOrFail($id);

        if($request->hasFile("foto")) {
            if($user->foto) {
                Storage::delete($user->foto);
            }

            $path = Storage::putFile('users', $request->file('foto'));
            $request->request->add(['foto' => $path]);
        }

        if($request->password) {
            $request->request->add(['password' => bcrypt($request->password)]);
        }

        $user->update($request->all());
        if($request->role_id != $user->role_id){
            $role_old = Role::findOrFail($user->role_id);
            $user->removeRole($role_old);

            $role_new = Role::findOrFail($request->role_id);
            $user->assignRole($role_new);
        }

        return response()->json([
            "message" => 200,
            "message_text" => "El usuario se registro correctamente",
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "apellido" => $user->apellido,
                "fullname" => $user->name . " " . $user->apellido,
                "genero" => $user->genero,
                "telefono" => $user->telefono,
                "email" => $user->email,
                "role_id" => $user->role_id,
                "role" => [
                    "name" => $user->role->name,
                ],
                "sede_id" => $user->sede_id,
                "sede" => [
                    "name" => $user->sede->name,
                ],
                "tipo_documento" => $user->tipo_documento,
                "numero_documento" => $user->numero_documento,
                "foto" => $user->foto ? env('APP_URL') . '/storage/' . $user->foto : null,
                "created_at" => $user->created_at->format('Y-m-d A h:i'),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            "message" => 200,
            "message_text" => "El usuario se Elimino correctamente",
        ]);
    }
}
