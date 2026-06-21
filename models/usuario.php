<?php

class Usuario
{
    private $pdo;
    private $tabela = "usuarios";

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar()
    {
        $sql = "SELECT * FROM {$this->tabela}";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id)
    {
        $sql = "SELECT * FROM {$this->tabela} WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        $sql = "INSERT INTO {$this->tabela} (nome,email,senha)
                VALUES (?,?,?)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $dados['nome'],
            $dados['email'],
            password_hash($dados['senha'], PASSWORD_DEFAULT)
        ]);
    }

    public function atualizar($id,$dados)
    {
        $sql = "UPDATE {$this->tabela}
                SET nome=?, email=?
                WHERE id=?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $dados['nome'],
            $dados['email'],
            $id
        ]);
    }

    public function excluir($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->tabela} WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function login($email,$senha)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tabela} WHERE email=?");
        $stmt->execute([$email]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if($usuario && password_verify($senha,$usuario['senha'])){
            return $usuario;
        }

        return false;
    }
}