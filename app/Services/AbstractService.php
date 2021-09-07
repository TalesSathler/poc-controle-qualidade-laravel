<?php

namespace App\Services;

class AbstractService
{
    /**
     * Tratar a busca por paginação nas tabelas
     * @param $busca
     * @param $array_requisicao
     */
    protected function paginarTabela(&$busca, &$array_requisicao)
    {
        $itens_por_pagina = isset($array_requisicao['porPagina']) && $array_requisicao['porPagina'] ? $array_requisicao['porPagina'] : 10;

        $pular = 0;
        if ($array_requisicao['pagina'] > 1) {
            $array_requisicao['pagina'] -= 1;
            $pular = $array_requisicao['pagina'] * $itens_por_pagina;
        }

        $busca = $busca->offset($pular)->limit($itens_por_pagina);

        if (isset($array_requisicao['ordenarCampo']) && $array_requisicao['ordenarCampo'] && isset($array_requisicao['ordenarTipo']) && $array_requisicao['ordenarTipo']) {
            $busca->orderby($array_requisicao['ordenarCampo'], $array_requisicao['ordenarTipo']);
        }
    }
}
