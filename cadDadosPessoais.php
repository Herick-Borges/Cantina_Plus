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
        <div class="progress-bar">
            <div class="progress" id="progress"></div>
        </div>

        <form id="multiStepForm" action="" method="post" class="box">
            <!-- Etapa 1: Dados Pessoais -->
            <div class="step active">
                <h2>Dados Pessoais:</h2>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="Nome">Nome:</label>
                        <input type="text" name="Nome" id="Nome" placeholder="Digite seu nome completo:" required>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="CPF">CPF:</label>
                        <input type="text" name="CPF" id="CPF" placeholder="Digite seu CPF:" required>
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
                        <input type="email" name="Email" id="Email" placeholder="Digite seu email:" required>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="Telefone">Telefone:</label>
                        <input type="tel" name="Telefone" id="Telefone" placeholder="Digite seu telefone:" required>
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
                        <input type="text" name="usuario" id="usuario" placeholder="Digite seu nome de usuário:" required>
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
    
    <!-- Script para depuração mais detalhada -->
    <script>
        console.log("Script de depuração carregado");
        function verificarFuncoes() {
            console.log("Verificando funções:");
            console.log("nextStep existe:", typeof nextStep === 'function');
            console.log("prevStep existe:", typeof prevStep === 'function');
            
            // Testar as funções diretamente
            console.log("Valores iniciais:");
            console.log("currentStep:", currentStep);
            console.log("steps.length:", steps ? steps.length : "steps não definido");
        }
        setTimeout(verificarFuncoes, 1000);
    </script>

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
            
            console.log("Handlers de eventos configurados para os botões");
        });
    </script>

</body>
</html>
