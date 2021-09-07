<?php

namespace App\Http\Controllers\Processos;

use App\Http\Controllers\Controller;
use App\Services\Processos\SetorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SetorController extends Controller
{
    private $service;
    /**
     * Criar uma nova instância para o controlador
     *
     * @return void
     */
    public function __construct()
    {
        $this->service = new SetorService();
    }

    public function get($setor_id = null)
    {
        $array_requisicao = Request::capture()->all();
        $array_resultado_busca = $this->service->get($setor_id, $array_requisicao);

        if ($setor_id && !$array_resultado_busca) {
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
     * Criar um novo registro de setor
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $array_requisicao = Request::capture()->all();

        $validator = Validator::make($array_requisicao, [
            'setor_nome' => 'required|max:255',
            'setor_descricao' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível salvar, verifique os dados enviados!',
                'dados' => $validator->getMessageBag()->getMessages()
            ]);
        }

        try {
            $array_dados_setor = $this->service->add($array_requisicao);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => 'Sucesso ao salvar o registro!',
            'dados' => $array_dados_setor
        ]);
    }

    /**
     * Alterar um registro de setor existente
     * @param $setor_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($setor_id)
    {
        $array_requisicao = Request::capture()->all();
        if (!$obj_setor = $this->service->getOne($setor_id)) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível salvar, verifique os dados enviados!',
                'dados' => $array_requisicao
            ]);
        }

        $validator = Validator::make($array_requisicao, [
            'setor_nome' => 'sometimes|max:255',
            'setor_descricao' => 'sometimes|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível salvar, verifique os dados enviados!',
                'dados' => $validator->getMessageBag()->getMessages()
            ]);
        }

        try {
            $array_dados_setor = $this->service->edit($obj_setor, $array_requisicao);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => 'Sucesso ao salvar o registro!',
            'dados' => $array_dados_setor
        ]);
    }

    /**
     * Remover um registro de setor existente
     * @param $setor_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($setor_id)
    {
        if (!$obj_setor = $this->service->getOne($setor_id)) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível remover o registro, verifique os dados enviados pela requisição!'
            ]);
        }

        try {
            $this->service->remove($obj_setor);
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
