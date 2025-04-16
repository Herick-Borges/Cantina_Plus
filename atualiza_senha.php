<?php
require_once 'banco/conexão.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';
    
    if ($senha === $confirma_senha) {
        $sql = "SELECT Id FROM Usuarios WHERE reset_token = :token AND token_expira > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            $sql = "UPDATE Usuarios SET Senha = :senha, reset_token = NULL, token_expira = NULL WHERE Id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':senha', $senha_hash);
            $stmt->bindParam(':id', $user['Id']);
            
            if ($stmt->execute()) {
                echo "<script>
                    alert('Senha atualizada com sucesso!');
                    window.location.href = 'index.php';
                    </script>";
            }
        }
    } else {
        echo "<script>
            alert('As senhas não correspondem');
            window.history.back();
            </script>";
    }
}
