<?php

class Pessoa
{
    private $pdo;
    private $tabela = "pessoas";

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar()
    {
        return $this->pdo->query("SELECT * FROM {$this->tabela}")
                         ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tabela} WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->tabela}(nome,telefone,email)
             VALUES(?,?,?)"
        );

        return $stmt->execute([
            $dados['nome'],
            $dados['telefone'],
            $dados['email']
        ]);
    }

    public function atualizar($id,$dados)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE {$this->tabela}
             SET nome=?, telefone=?, email=?
             WHERE id=?"
        );

        return $stmt->execute([
            $dados['nome'],
            $dados['telefone'],
            $dados['email'],
            $id
        ]);
    }

    public function excluir($id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM {$this->tabela} WHERE id=?"
        );

        return $stmt->execute([$id]);
    }
}