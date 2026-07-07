<?php
// Controller da entidade de usuários.
class UsuariosController 
{
    private PDO $pdo;

    public function __construct()
    {
        // Importa o arquivo que inicializa o objeto $pdo.
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        // Define saída em JSON para APIs/consumo por front-end.
        header("Content-Type: application/json; charset=utf-8");
        
        // Consulta todos os usuários com ordenação decrescente por ID.
        $sql = 'SELECT id, nome, email, perfil, status, criado_em FROM usuarios ORDER BY id DESC';
        $stmt = $this->pdo->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retorna os dados formatados
        echo json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
}