<?php

class PessoasController {
    private PDO $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function listar(): void {
        header("Content-Type: application/json; charset=utf-8");
        $stmt = $this->pdo->query("SELECT * FROM pessoas ORDER BY id DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function buscarPorId(): void {
        header("Content-Type: application/json; charset=utf-8");
        $id = $_GET['id'] ?? null;
        if (!$id) { echo json_encode(['erro' => 'ID inválido.']); return; }

        $stmt = $this->pdo->prepare("SELECT * FROM pessoas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: ['erro' => 'Pessoa não encontrada.']);
    }

    public function cadastrar(): void {
        header("Content-Type: application/json; charset=utf-8");
        
        $nome = $_POST['nome'] ?? $_GET['nome'] ?? null;
        $documento = $_POST['documento'] ?? $_GET['documento'] ?? null;
        $telefone = $_POST['telefone'] ?? $_GET['telefone'] ?? null;
        $curso = $_POST['curso'] ?? $_GET['curso'] ?? null;
        $periodo = $_POST['periodo'] ?? $_GET['periodo'] ?? null;

        if (!$nome) { 
            $dadosBrutos = json_decode(file_get_contents('php://input'), true);
            $nome = $dadosBrutos['nome'] ?? null;
            $documento = $dadosBrutos['documento'] ?? null;
        }

        if (!$nome) { echo json_encode(['erro' => 'Nome é obrigatório.']); return; }

        try {
            $stmt = $this->pdo->prepare("INSERT INTO pessoas (nome, documento, telefone, curso, periodo, status) VALUES (:nome, :doc, :tel, :curso, :per, 'Ativo')");
            $stmt->execute([
                ':nome' => $nome,
                ':doc' => $documento,
                ':tel' => $telefone,
                ':curso' => $curso,
                ':per' => $periodo
            ]);
            echo json_encode(['sucesso' => 'Pessoa cadastrada com sucesso!', 'id' => $this->pdo->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao cadastrar: ' . $e->getMessage()]);
        }
    }

    public function atualizar(): void {
        header("Content-Type: application/json; charset=utf-8");
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        $nome = $_POST['nome'] ?? $_GET['nome'] ?? null;

        if (!$id || !$nome) { echo json_encode(['erro' => 'ID e Nome são obrigatórios.']); return; }

        $stmt = $this->pdo->prepare("UPDATE pessoas SET nome = :nome WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':id' => $id]);
        echo json_encode(['sucesso' => 'Pessoa atualizada com sucesso!']);
    }

    public function inativar(): void {
        header("Content-Type: application/json; charset=utf-8");
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if (!$id) { echo json_encode(['erro' => 'ID é obrigatório.']); return; }

        $stmt = $this->pdo->prepare("UPDATE pessoas SET status = 'Inativo' WHERE id = :id");