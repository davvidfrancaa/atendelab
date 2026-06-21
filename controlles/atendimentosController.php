<?php

require_once __DIR__ . '/../models/atendimento.php';

class AtendimentosController
{
    private $model;

    public function __construct($conexao)
    {
        $this->model = new Atendimento($conexao);
    }

    public function listar()
    {
        return $this->model->listar();
    }

    public function buscar($id)
    {
        return $this->model->buscar($id);
    }

    public function criar($dados)
    {
        return $this->model->criar($dados);
    }

    public function atualizar($id, $dados)
    {
        return $this->model->atualizar($id, $dados);
    }

    public function excluir($id)
    {
        return $this->model->excluir($id);
    }
}