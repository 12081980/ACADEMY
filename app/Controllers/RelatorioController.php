<?php

namespace App\Controllers;
use App\Models\AcessoModel;

class RelatorioController
{
    public function acessos()
    {
        require_once __DIR__ . '/../Models/AcessoModel.php';
        $model = new AcessoModel(conn());
        $lista = $model->listar();

        require __DIR__ . '/../../Views/admin/acesso.php';
    }
}
