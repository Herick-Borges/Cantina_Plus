<?php
session_start();
require_once 'banco/conexão.php';
header('Content-Type: application/json');

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

try {
    if (!empty($usuario) && !empty($senha)) {
        $sql = "SELECT Id, Usuario, Senha, nome FROM Usuarios WHERE Usuario = :usuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($senha, $row['Senha'])) {
                $_SESSION['usuario_id'] = $row['Id'];
                $_SESSION['usuario_nome'] = $row['nome'];
                echo json_encode(['status' => 'success', 'message' => 'Login realizado com sucesso']);
                exit();
            }
        }
        echo json_encode(['status' => 'error', 'message' => 'Usuário ou senha inválidos']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, preencha todos os campos']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao processar login']);
}
