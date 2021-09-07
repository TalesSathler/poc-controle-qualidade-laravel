<?php

namespace App\Services;

use App\User;

class UsuarioService extends AbstractService
{
    /**
     * Retornar o(s) registro(s) do(s) user(s)
     * @param $user_id
     * @param $array_requisicao
     * @return array
     */
    public function get($user_id, $array_requisicao)
    {
        if ($user_id) {
            $obj_user = User::find($user_id);
            if (!$obj_user) {
                return [];
            }

            return $obj_user->toArray();
        }
        else if (isset($array_requisicao['pagina']) && $array_requisicao['pagina']) {
            $busca = new User();
            $this->paginarTabela($busca, $array_requisicao);

            $array_dados = $busca->get()->toArray();
            $total = User::count();

            return [
                'itens' => $array_dados,
                'total' => $total
            ];
        }

        return User::orderBy('id', 'asc')->get();
    }

    /**
     * Adicionar e validar um registro do user
     * @param $array_requisicao
     * @return array
     */
    public function add($array_requisicao)
    {
        $obj_user = new User($array_requisicao);
        $obj_user->save();
        return $obj_user->toArray();
    }

    /**
     * Editar um registro do user
     * @param $obj_user
     * @param $array_requisicao
     * @return mixed
     */
    public function edit(\App\User $obj_user, $array_requisicao)
    {
        if (isset($array_requisicao['password']) && !$array_requisicao['password']) {
            unset($array_requisicao['password']);
        }

        $obj_user->update($array_requisicao);
        return $obj_user->toArray();
    }

    /**
     * Excluir um registro do user
     * @param User $obj_user
     * @throws \Exception
     */
    public function remove(\App\User $obj_user)
    {
        $obj_user->delete();
    }
}
