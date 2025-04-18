<?php
require_once 'banco/conexão.php';

header('Content-Type: application/json');

$cpf = $_GET['cpf'] ?? '';
$response = ['existe' => false, 'erro' => false, 'mensagem' => ''];

function validarCPF($cpf) {
    // Remove caracteres não numéricos
    $cpf = preg_replace('/\D/', '', $cpf);

    // Verifica se o CPF tem 11 dígitos
    if (strlen($cpf) !== 11) return false;

    // Verifica se todos os dígitos são iguais
    if (preg_match('/^(\d)\1+$/', $cpf)) return false;

    // Calcula os dígitos verificadores
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }

    return true;
}

if (!validarCPF($cpf)) {
    $response['erro'] = true;
    $response['mensagem'] = 'CPF inválido.';
    echo json_encode($response);
    exit;
}

try {
    $sql = "SELECT COUNT(*) FROM Usuarios WHERE CPF = :cpf";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();
    $response['existe'] = $stmt->fetchColumn() > 0;
} catch (PDOException $e) {
    $response['erro'] = true;
    $response['mensagem'] = 'Erro ao consultar o banco de dados.';
    error_log("Erro ao verificar CPF: " . $e->getMessage());
}

echo json_encode($response);
