<?php

namespace App\Services\Processos;

use App\Services\AbstractService;
use App\Modules\Processos\Processo;

class ProcessoService extends AbstractService
{
    /**
     * @param $setor_id
     * @return mixed
     */
    public function getOne($setor_id)
    {
        return Processo::find($setor_id);
    }

    /**
     * Retornar o(s) registro(s) do(s) processo(es)
     * @param $processo_id
     * @param array $array_requisicao
     * @return array
     */
    public function get($processo_id, $array_requisicao = [])
    {
        $busca = Processo::join('setores', 'setores.setor_id', 'processos.setor_id');

        if ($processo_id) {
            $obj_processo = $busca->find($processo_id);
            if (!$obj_processo) {
                return [];
            }

            return $obj_processo->toArray();
        }
        else if (isset($array_requisicao['pagina']) && $array_requisicao['pagina']) {
            $this->paginarTabela($busca, $array_requisicao);

            $array_dados = $busca->get()->toArray();
            $total = Processo::count();

            return [
                'itens' => $array_dados,
                'total' => $total
            ];
        }

        return $busca->orderBy('processo_id', 'asc')->get();
    }

    /**
     * Adicionar e validar um registro do processo
     * @param $array_requisicao
     * @return array
     */
    public function add($array_requisicao)
    {
        $obj_processo = new Processo($array_requisicao);
        $obj_processo->save();
        return $obj_processo->toArray();
    }

    /**
     * Editar um registro do processo
     * @param $obj_processo
     * @param $array_requisicao
     * @return mixed
     */
    public function edit(\App\Modules\Processos\Processo $obj_processo, $array_requisicao)
    {
        $obj_processo->update($array_requisicao);
        return $obj_processo->toArray();
    }

    /**
     * Excluir um registro do processo
     * @param Processo $obj_processo
     * @throws \Exception
     */
    public function remove(\App\Modules\Processos\Processo $obj_processo)
    {
        $obj_processo->delete();
    }
}
