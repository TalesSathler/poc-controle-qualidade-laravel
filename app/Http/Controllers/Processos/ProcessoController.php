<?php

namespace App\Http\Controllers\Processos;

use App\Http\Controllers\Controller;
use App\Services\Processos\ProcessoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProcessoController extends Controller
{
    private $service;
    /**
     * Criar uma nova instância para o controlador
     *
     * @return void
     */
    public function __construct()
    {
        $this->service = new ProcessoService();
    }

    public function get($processo_id = null)
    {
        $array_requisicao = Request::capture()->all();
        $array_resultado_busca = $this->service->get($processo_id, $array_requisicao);

        if ($processo_id && !$array_resultado_busca) {
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

    /**
     * Criar um novo registro de processo
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $array_requisicao = Request::capture()->all();

        $validator = Validator::make($array_requisicao, [
            'setor_id' => 'required|exists:\App\Modules\Processos\Setor,setor_id',
            'processo_nome' => 'required|max:255',
            'processo_descricao' => 'required|max:255',
            'processo_horario' => 'required|max:25'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível salvar, verifique os dados enviados!',
                'dados' => $validator->getMessageBag()->getMessages()
            ]);
        }

        try {
            $array_dados_processo = $this->service->add($array_requisicao);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => 'Sucesso ao salvar o registro!',
            'dados' => $array_dados_processo
        ]);
    }

    /**
     * Alterar um registro de processo existente
     * @param $processo_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($processo_id)
    {
        $array_requisicao = Request::capture()->all();
        if (!$obj_processo = $this->service->getOne($processo_id)) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível salvar, verifique os dados enviados!',
                'dados' => $array_requisicao
            ]);
        }

        $validator = Validator::make($array_requisicao, [
            'setor_id' => 'sometimes|exists:\App\Modules\Processos\Setor,setor_id',
            'processo_nome' => 'sometimes|max:255',
            'processo_descricao' => 'sometimes|max:255',
            'processo_horario' => 'sometimes|max:25'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível salvar, verifique os dados enviados!',
                'dados' => $validator->getMessageBag()->getMessages()
            ]);
        }

        try {
            $array_dados_processo = $this->service->edit($obj_processo, $array_requisicao);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => 'Sucesso ao salvar o registro!',
            'dados' => $array_dados_processo
        ]);
    }

    /**
     * Remover um registro de processo existente
     * @param $processo_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($processo_id)
    {
        if (!$obj_processo = $this->service->getOne($processo_id)) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível remover o registro, verifique os dados enviados pela requisição!'
            ]);
        }

        try {
            $this->service->remove($obj_processo);
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
