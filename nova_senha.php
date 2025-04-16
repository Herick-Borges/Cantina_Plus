<?php
require_once 'banco/conexão.php';

$token = $_GET['token'] ?? '';
$token_valido = false;

if (!empty($token)) {
    $sql = "SELECT Id FROM Usuarios WHERE reset_token = :token AND token_expira > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    
    $token_valido = $stmt->fetch() !== false;
}

if (!$token_valido) {
    echo "<script>
        alert('Token inválido ou expirado');
        window.location.href = 'index.php';
        </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Senha - CantinaPlus</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="header">
        <img src="imagens/logo.svg" alt="CantinaPlus Logo" class="logo">
    </div>

    <div class="container">
        <div class="login-box">
            <h2>Nova Senha</h2>
            <form action="atualiza_senha.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="senha">Nova Senha:</label>
                        <input type="password" name="senha" required>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="confirma_senha">Confirme a Senha:</label>
                        <input type="password" name="confirma_senha" required>
                    </div>
                </div>
                <button type="submit">Alterar Senha</button>
            </form>
        </div>
    </div>
</body>
</html>
