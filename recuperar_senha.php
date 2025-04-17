<?php
// recuperar_senha.php
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha - CantinaPlus</title>
    <link rel="stylesheet" href="css/recuperar_senha.css">
</head>
<body>
    <div class="header">
        <img src="imagens/logo.svg" alt="CantinaPlus Logo" class="logo">
    </div>

    <div class="container">
        <h2>Recuperação de Senha</h2>
        <form action="processar_recuperacao.php" method="post" class="recuperacao-box">
            <div class="input-group">
                <div class="input-wrapper">
                    <label for="Email">Email:</label>
                    <input type="email" name="Email" id="Email" placeholder="Digite seu email cadastrado:" required>
                </div>
            </div>
            
            <button type="submit">Enviar</button>
            
            <div class="login-link">
                <a href="index.php">Voltar para Login</a>
            </div>
        </form>
    </div>

    <footer>
        <p>Todos os direitos reservados <span style="color: black;">CantinaPlus</span> Inc</p>
    </footer>
</body>
</html>