<?php
// recuperar_senha.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="header">
        <img src="imagens/logo.svg" alt="CantinaPlus Logo" class="logo">
    </div>

    <div class="container">
        <div class="form-wrapper">
            <h2>Recuperar Senha</h2>
            <p>Digite seu e-mail para receber um link de redefinição de senha.</p>
            <form action="processa_recuperar_senha.php" method="POST" class="login-box">
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="Email">Email:</label>
                        <input type="email" name="Email" id="Email" placeholder="Digite seu email:" required
                               value="<?php echo isset($form_data['Email']) ? htmlspecialchars($form_data['Email']) : ''; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>