<?php

namespace App\Services\Processos;

use App\Services\AbstractService;
use App\Modules\Processos\Setor;

class SetorService extends AbstractService
{
    /**
     * @param $setor_id
     * @return mixed
     */
    public function getOne($setor_id)
    {
        return Setor::find($setor_id);
    }

    /**
     * Retornar o(s) registro(s) do(s) setor(es)
     * @param $setor_id
     * @param array $array_requisicao
     * @return array
     */
    public function get($setor_id, $array_requisicao = [])
    {
        if ($setor_id) {
            $obj_setor = Setor::find($setor_id);
            if (!$obj_setor) {
                return [];
            }

            return $obj_setor->toArray();
        }
        else if (isset($array_requisicao['limitar']) && $array_requisicao['limitar']) {
            $buscar = "%". (isset($array_requisicao['buscar']) ? $array_requisicao['buscar'] : '') ."%";

            return Setor::where('setor_nome', 'LIKE', $buscar)->limit(20)->get()->toArray();
        }
        else if (isset($array_requisicao['pagina']) && $array_requisicao['pagina']) {
            $busca = new Setor();
            $this->paginarTabela($busca, $array_requisicao);

            $array_dados = $busca->get()->toArray();
            $total = Setor::count();

            return [
                'itens' => $array_dados,
                'total' => $total
            ];
        }

        return Setor::orderBy('setor_id', 'asc')->get();
    }

    /**
     * Adicionar e validar um registro do setor
     * @param $array_requisicao
     * @return array
     */
    public function add($array_requisicao)
    {
        $obj_setor = new Setor($array_requisicao);
        $obj_setor->save();
        return $obj_setor->toArray();
    }

    /**
     * Editar um registro do setor
     * @param $obj_setor
     * @param $array_requisicao
     * @return mixed
     */
    public function edit(\App\Modules\Processos\Setor $obj_setor, $array_requisicao)
    {
        $obj_setor->update($array_requisicao);
        return $obj_setor->toArray();
    }

    /**
     * Excluir um registro do setor
     * @param Setor $obj_setor
     * @throws \Exception
     */
    public function remove(\App\Modules\Processos\Setor $obj_setor)
    {
        //Validar se tem processo usando este setor
        if ($obj_setor->processos()->count('processos.processo_id')) {
            throw new \Exception('Não é possível remover o setor, pois possui processos vinculados!');
        }

        $obj_setor->delete();
    }
}
