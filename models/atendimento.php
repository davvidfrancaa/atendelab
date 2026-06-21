<?php

class Atendimento
{
    private $pdo;
    private $tabela = "atendimentos";

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
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->tabela} WHERE id=?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->tabela}
            (pessoa_id,tipo_id,data_atendimento,descricao)
            VALUES(?,?,?,?)"
        );

        return $stmt->execute([
            $dados['pessoa_id'],
            $dados['tipo_id'],
            $dados['data_atendimento'],
            $dados['descricao']
        ]);
    }

    public function atualizar($id,$dados)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE {$this->tabela}
            SET pessoa_id=?, tipo_id=?, data_atendimento=?, descricao=?
            WHERE id=?"
        );

        return $stmt->execute([
            $dados['pessoa_id'],
            $dados['tipo_id'],
            $dados['data_atendimento'],
            $dados['descricao'],
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