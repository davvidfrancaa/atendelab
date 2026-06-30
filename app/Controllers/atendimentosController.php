<?php

class AtendimentosController {
    private PDO $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function listar(): void {
        header("Content-Type: application/json; charset=utf-8");
        try {
            $stmt = $this->pdo->query("SELECT * FROM atendimentos ORDER BY id DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao listar: ' . $e->getMessage()]);
        }
    }

    public function buscarPorId(): void {
        header("Content-Type: application/json; charset=utf-8");
        $id = $_GET['id'] ?? null;
        if (!$id) { echo json_encode(['erro' => 'ID inválido.']); return; }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM atendimentos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: ['erro' => 'Atendimento não encontrado.']);
        } catch (PDOException $e) {
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }

    public function cadastrar(): void {
        header("Content-Type: application/json; charset=utf-8");
        
        $pessoa_id = $_POST['pessoa_id'] ?? $_GET['pessoa_id'] ?? 1;
        $usuario_id = $_POST['usuario_id'] ?? $_GET['usuario_id'] ?? 1;
        $obs = $_POST['observacao'] ?? $_GET['observacao'] ?? 'Primeiro contato';

        // Busca dinamicamente o ID do tipo_atendimentos para evitar falha de FK
        try {
            $buscaTipo = $this->pdo->query("SELECT id FROM tipo_atendimentos LIMIT 1");
            $tipo = $buscaTipo->fetch(PDO::FETCH_ASSOC);
            $tipo_id = $tipo['id'] ?? 1;
        } catch (PDOException $e) {
            $tipo_id = 1;
        }

        try {
            $stmt = $this->pdo->prepare("INSERT INTO atendimentos (pessoa_id, tipo_atendimento, usuario_id, data_atendimento, hora_atendimento, observacao, status) VALUES (:pid, :tid, :uid, NOW(), NOW(), :obs, 'Aberto')");
            $stmt->execute([
                ':pid' => $pessoa_id, 
                ':tid' => $tipo_id, 
                ':uid' => $usuario_id,
                ':obs' => $obs
            ]);
            echo json_encode(['sucesso' => 'Atendimento aberto com sucesso!', 'id' => $this->pdo->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao abrir atendimento: ' . $e->getMessage()]);
        }
    }

    public function iniciar(): void {
        header("Content-Type: application/json; charset=utf-8");
        $id = $_POST['id'] ?? $_GET['id'] ?? null;

        if (!$id) { echo json_encode(['erro' => 'ID é obrigatório.']); return; }

        try {
            $stmt = $this->pdo->prepare("UPDATE atendimentos SET status = 'Em andamento' WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['sucesso' => 'Status alterado para em andamento!']);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao iniciar: ' . $e->getMessage()]);
        }
    }

    public function concluir(): void {
        header("Content-Type: application/json; charset=utf-8");
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        $obs_final = $_POST['observacao_final'] ?? $_GET['observacao_final'] ?? 'Finalizado';

        if (!$id) { echo json_encode(['erro' => 'ID é obrigatório.']); return; }

        try {
            $stmt = $this->pdo->prepare("UPDATE atendimentos SET status = 'Concluído', observacao = :obs WHERE id = :id");
            $stmt->execute([':obs' => $obs_final, ':id' => $id]);
            echo json_encode(['sucesso' => 'Atendimento concluído com sucesso!']);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao concluir: ' . $e->getMessage()]);
        }
    }
}