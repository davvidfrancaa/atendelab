<?php

require_once __DIR__ . '/../models/usuario.php';

class UsuariosController
{
    private $model;

    public function __construct($conexao)
    {
        $this->model = new Usuario($conexao);
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

    public function login($email, $senha)
    {
        return $this->model->login($email, $senha);
    }
}