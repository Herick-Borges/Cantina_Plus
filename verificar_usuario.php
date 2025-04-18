<?php
require_once 'banco/conexão.php';

header('Content-Type: application/json');

$usuario = $_GET['usuario'] ?? '';
$response = ['existe' => false, 'erro' => false, 'mensagem' => ''];

if (empty($usuario)) {
    $response['erro'] = true;
    $response['mensagem'] = 'O nome de usuário não pode estar vazio.';
    echo json_encode($response);
    exit;
}

try {
    $sql = "SELECT COUNT(*) FROM Usuarios WHERE usuario = :usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $response['existe'] = $stmt->fetchColumn() > 0;
} catch (PDOException $e) {
    $response['erro'] = true;
    $response['mensagem'] = 'Erro ao consultar o banco de dados.';
    error_log("Erro ao verificar nome de usuário: " . $e->getMessage());
}

echo json_encode($response);
