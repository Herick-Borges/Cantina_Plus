<?php
require_once 'banco/conexão.php';
require_once 'config/mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['Email'] ?? '';
    
    if (!empty($email)) {
        try {
            $sql = "SELECT Id, nome FROM Usuarios WHERE Email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $token = bin2hex(random_bytes(32));
                $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $sql = "UPDATE Usuarios SET reset_token = :token, token_expira = :expira WHERE Id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':expira', $expira);
                $stmt->bindParam(':id', $user['Id']);
                $stmt->execute();
                
                $reset_link = "http://localhost/cantina_plus/nova_senha.php?token=" . $token;
                
                $corpo = "
                    <h2>Recuperação de Senha - CantinaPlus</h2>
                    <p>Olá {$user['nome']},</p>
                    <p>Clique no link abaixo para redefinir sua senha:</p>
                    <p><a href='{$reset_link}'>{$reset_link}</a></p>
                    <p>Este link expira em 1 hora.</p>
                ";
                
                if(enviarEmail($email, $user['nome'], "Recuperação de Senha - CantinaPlus", $corpo)) {
                    echo "<script>
                        alert('Email de recuperação enviado com sucesso!');
                        window.location.href = 'index.php';
                        </script>";
                } else {
                    throw new Exception("Erro ao enviar email");
                }
            } else {
                throw new Exception("Email não encontrado");
            }
        } catch (Exception $e) {
            echo "<script>
                alert('Erro: " . $e->getMessage() . "');
                window.location.href = 'recuperar_senha.php';
                </script>";
        }
    }
}
