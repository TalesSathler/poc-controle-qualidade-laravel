<?php

namespace App\Modules\Processos;

use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    protected $table = 'setores';
    protected $primaryKey = 'setor_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setor_id', 'setor_nome', 'setor_descricao',
    ];

    public function processos()
    {
        return $this->belongsToMany('\App\Modules\Processos\Setor', 'processos', 'setor_id', 'setor_id');
    }
}
