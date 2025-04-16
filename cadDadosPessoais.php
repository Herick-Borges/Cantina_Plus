<?php
session_start();

// Recuperar dados do formulário em caso de erro
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['form_data']); // Limpar depois de usar

// Recuperar erros
$erros = isset($_SESSION['erros']) ? $_SESSION['erros'] : [];
unset($_SESSION['erros']); // Limpar depois de usar
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - CantinaPlus</title>
    <link rel="stylesheet" href="css/cadastro.css">
</head>
<body>
    <div class="header">
        <img src="imagens/logo.svg" alt="CantinaPlus Logo" class="logo">
    </div>

    <div class="container">
        <?php if (!empty($erros)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach($erros as $erro): ?>
                        <li><?php echo htmlspecialchars($erro); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="progress-bar">
            <div class="progress" id="progress"></div>
        </div>

        <form id="multiStepForm" action="cadastro.php" method="post" class="box">
            <!-- Etapa 1: Dados Pessoais -->
            <div class="step active">
                <h2>Dados Pessoais:</h2>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="Nome">Nome:</label>
                        <input type="text" name="Nome" id="Nome" placeholder="Digite seu nome completo:" required 
                               value="<?php echo isset($form_data['Nome']) ? htmlspecialchars($form_data['Nome']) : ''; ?>">
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="CPF">CPF:</label>
                        <input type="text" name="CPF" id="CPF" placeholder="Digite seu CPF:" required
                               value="<?php echo isset($form_data['CPF']) ? htmlspecialchars($form_data['CPF']) : ''; ?>">
                    </div>
                </div>
                <button type="button" id="nextBtn1">Próximo</button>
            </div>

            <!-- Etapa 2: Contato -->
            <div class="step">
                <h2>Contato:</h2>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="Email">Email:</label>
                        <input type="email" name="Email" id="Email" placeholder="Digite seu email:" required
                               value="<?php echo isset($form_data['Email']) ? htmlspecialchars($form_data['Email']) : ''; ?>">
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="Telefone">Telefone:</label>
                        <input type="tel" name="Telefone" id="Telefone" placeholder="Digite seu telefone:" required
                               value="<?php echo isset($form_data['Telefone']) ? htmlspecialchars($form_data['Telefone']) : ''; ?>">
                    </div>
                </div>
                <button type="button" id="prevBtn2">Voltar</button>
                <button type="button" id="nextBtn2">Próximo</button>
            </div>

            <!-- Etapa 3: Registro -->
            <div class="step">
                <h2>Cadastro:</h2>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="usuario">Usuário:</label>
                        <input type="text" name="usuario" id="usuario" placeholder="Digite seu nome de usuário:" required
                               value="<?php echo isset($form_data['usuario']) ? htmlspecialchars($form_data['usuario']) : ''; ?>">
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="senha">Senha:</label>
                        <input type="password" name="senha" id="senha" placeholder="Digite sua senha:" required>
                    </div>
                </div>
                <button type="button" id="prevBtn3">Voltar</button>
                <button type="submit" id="submitBtn">Cadastrar</button>
            </div>
        </form>
    </div>

    <footer>
        <p>Todos os direitos reservados <span style="color: black;">CantinaPlus</span> Inc</p>
    </footer>

    <script src="js/cadastro.js"></script>
    
    <!-- Script personalizado para garantir o funcionamento correto -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar os botões de navegação
            document.getElementById('nextBtn1').addEventListener('click', function(e) {
                e.preventDefault();
                nextStep();
            });
            
            document.getElementById('prevBtn2').addEventListener('click', function(e) {
                e.preventDefault();
                prevStep();
            });
            
            document.getElementById('nextBtn2').addEventListener('click', function(e) {
                e.preventDefault();
                nextStep();
            });
            
            document.getElementById('prevBtn3').addEventListener('click', function(e) {
                e.preventDefault();
                prevStep();
            });
            
            // Adicionar evento de log para o botão de cadastro
            document.getElementById('submitBtn').addEventListener('click', function(event) {
                // Não prevenimos o evento padrão aqui para permitir o envio do formulário
                console.log("Botão Cadastrar clicado. Enviando formulário...");
                
                // Forçar envio do formulário após validação
                const form = document.getElementById('multiStepForm');
                if (validateCurrentStep()) {
                    console.log("Validação passou! Enviando formulário...");
                    // Desativa a prevenção padrão dos outros handlers
                    event.stopImmediatePropagation();
                    form.submit();
                } else {
                    console.log("Validação falhou. O formulário não será enviado.");
                    event.preventDefault();
                }
            });
            
            // Modificar o handler de submit do formulário para depuração
            document.getElementById('multiStepForm').addEventListener('submit', function(e) {
                console.log("Formulário sendo enviado para cadastro.php");
                // Não chamar e.preventDefault() aqui para permitir o envio
            });
            
            console.log("Handlers de eventos configurados para os botões");
        });
    </script>
</body>
</html>
