<?php

namespace App\Http\Controllers;

use App\Services\UsuarioService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    private $service;
    /**
     * Criar uma nova instância para o controlador
     *
     * @return void
     */
    public function __construct()
    {
        $this->service = new UsuarioService();
    }

    public function get($user_id = null)
    {
        $array_requisicao = Request::capture()->all();
        $array_resultado_busca = $this->service->get($user_id, $array_requisicao);

        if ($user_id && !$array_resultado_busca) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foram encontrados dados!'
            ]);
        }

        return response()->json([
            'status' => 'sucesso',
            'dados' => $array_resultado_busca
        ]);
    }

    public function store()
    {
        try {
            $array_requisicao = Request::capture()->all();

            $validator = Validator::make($array_requisicao, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'erro',
                    'mensagem' => 'Não foi possível salvar, verifique os dados enviados!',
                    'dados' => $validator->getMessageBag()->getMessages()
                ]);
            }

            $obj_usuario = User::create([
                'name' => $array_requisicao['name'],
                'email' => $array_requisicao['email'],
                'password' => Hash::make($array_requisicao['password']),
            ]);

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => 'Sucesso ao salvar o registro!',
                'dados' => $obj_usuario
            ]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Alterar um registro de user existente
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($user_id)
    {
        $array_requisicao = Request::capture()->all();
        if (!$obj_user = User::find($user_id)) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível salvar, verifique os dados enviados!',
                'dados' => $array_requisicao
            ]);
        }

        $validator = Validator::make($array_requisicao, [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,'. $user_id],
            'password' => ['sometimes', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível salvar, verifique os dados enviados!',
                'dados' => $validator->getMessageBag()->getMessages()
            ]);
        }

        try {
            $array_dados_user = $this->service->edit($obj_user, $array_requisicao);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => 'Sucesso ao salvar o registro!',
            'dados' => $array_dados_user
        ]);
    }

    /**
     * Remover um registro de user existente
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($user_id)
    {
        if (!$obj_user = User::find($user_id)) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível remover o registro, verifique os dados enviados pela requisição!'
            ]);
        }

        try {
            $this->service->remove($obj_user);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => 'Sucesso ao remover o registro!'
        ]);
    }
}
