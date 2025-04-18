<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CantinaPlus</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/toast.css"> <!-- Adicionar estilo para Toast -->
</head>
<body>
    <div class="header">
        <img src="imagens/logo.svg" alt="CantinaPlus Logo" class="logo">
    </div>

    <div class="container">
        <h2>Login:</h2>
        <form action="login.php" method="post" class="login-box">
            <div class="input-group">
                <div class="input-wrapper">
                    <label for="usuario">Usuário:</label>
                    <input type="text" name="usuario" id="usuario" placeholder="digite seu nome de usuario:" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-wrapper">
                    <label for="senha">Senha:</label>
                    <div class="password-container">
                        <input 
                            type="password" 
                            name="senha" 
                            id="senha" 
                            placeholder="digite sua senha:" 
                            required
                        />
                    </div>
                </div>
            </div>

            <button type="submit">Acessar</button>

            
            <div class="register-link">
                Ainda não tem conta? <a href="cadDadosPessoais.php">Clique aqui.</a><br>
                <a href="recuperar_senha.php">Esqueceu a senha?</a>
            </div>
        </form>
    </div>

    <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso'): ?>
        <div class="toast toast-sucesso">
            <p>Cadastro realizado com sucesso! Faça login para continuar.</p>
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.toast').style.display = 'none';
            }, 5000); // Esconde o Toast após 5 segundos
        </script>
    <?php endif; ?>

    <footer>
        <p>Todos os direitos reservados <span style= "color: black;">CantinaPlus</span> Inc</p>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            // ...existing code...
        });
    });
    </script>
</body>
</html>

