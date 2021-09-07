<?php

namespace App\Modules\Processos;

use Illuminate\Database\Eloquent\Model;

class Processo extends Model
{
    protected $table = 'processos';
    protected $primaryKey = 'processo_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setor_id', 'processo_nome', 'processo_descricao', 'processo_horario',
    ];


}
