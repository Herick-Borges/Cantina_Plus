<?php
session_start();
require_once 'banco/conexão.php';

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
                echo "<script>
                    alert('Login realizado com sucesso!');
                    window.location.href = 'dashboard.php';
                    </script>";
                exit();
            }
        }
        echo "<script>
            alert('Usuário ou senha inválidos');
            window.history.back();
            </script>";
    } else {
        echo "<script>
            alert('Por favor, preencha todos os campos');
            window.history.back();
            </script>";
    }
} catch (PDOException $e) {
    echo "<script>
        alert('Erro ao processar login');
        window.history.back();
        </script>";
}
