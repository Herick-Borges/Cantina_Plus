<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CantinaPlus</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="header">
        <img src="imagens/logo.svg" alt="CantinaPlus Logo" class="logo">
    </div>

    <div class="container">
        <h2>Login:</h2>
        <form action="login.php" method="post" class="login-box">
            <div class="input-group">  
                    <label for="usuario">Usuário:</label>
                    <input type="text" name="usuario" id="usuario" placeholder="digite seu nome de usuario:" required>
            </div>

            <div class="input-group">
                <div class="input-wrapper">
                    <label for="CPF">Senha:</label>
                    <input type="text" name="CPF" id="CPF" placeholder="digite seu CPF:" required>
                </div>
            </div>

            <button type="submit">Acessar</button>
            <p class="register-link">Ainda não tem conta? <a href="#">Clique aqui.</a></p>
        </form>
    </div>

    <footer>
        <p>Todos os direitos reservados <span style= "color: black;">CantinaPlus</span> Inc</p>
    </footer>
</body>
</html>
