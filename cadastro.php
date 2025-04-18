<?php
session_start();

// Adicionar log para depuração
error_log("Iniciando processo de cadastro");

// Incluir arquivo de conexão - Verificar se o caminho está correto
if (file_exists('banco/conexão.php')) {
    require_once 'banco/conexão.php';
    error_log("Arquivo de conexão incluído com sucesso");
} else {
    error_log("ERRO: Arquivo banco/conexão.php não encontrado");
    die("Erro: Arquivo de conexão não encontrado. Contate o administrador.");
}

// Verificar se a conexão foi estabelecida
if (!isset($conn) || $conn === null) {
    error_log("ERRO: Variável de conexão não definida");
    die("Erro na conexão com o banco de dados. Contate o administrador.");
}

// Função para validar telefone
function validarTelefone($telefone) {
    // Remove caracteres não numéricos
    $telefone = preg_replace('/\D/', '', $telefone);

    // Verifica se o telefone tem exatamente 11 dígitos e começa com DDD válido
    if (strlen($telefone) !== 11) {
        return false;
    }

    // Verifica se o DDD é válido (exemplo: 11 a 99)
    $ddd = substr($telefone, 0, 2);
    if (!preg_match('/^[1-9][0-9]$/', $ddd)) {
        return false;
    }

    // Verifica se o número segue o padrão 9XXXXYYYY
    $numero = substr($telefone, 2);
    if (!preg_match('/^[9][0-9]{8}$/', $numero)) {
        return false;
    }

    return true;
}

// Função para validar CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) !== 11) return false;

    if (in_array($cpf, [
        '00000000000',
        '11111111111',
        '22222222222',
        '33333333333',
        '44444444444',
        '55555555555',
        '66666666666',
        '77777777777',
        '88888888888',
        '99999999999',
    ])) return false;

    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }

    return true;
}

// Adicionar logs para depuração
error_log("Arquivo cadastro.php acessado - METHOD: " . $_SERVER["REQUEST_METHOD"]);
error_log("POST data: " . print_r($_POST, true));

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os valores do formulário (usando PDO não precisamos de real_escape_string)
    $nome = isset($_POST['Nome']) ? trim($_POST['Nome']) : '';
    $cpf = isset($_POST['CPF']) ? preg_replace('/\D/', '', $_POST['CPF']) : '';
    $email = isset($_POST['Email']) ? trim($_POST['Email']) : '';
    $telefone = isset($_POST['Telefone']) ? preg_replace('/\D/', '', $_POST['Telefone']) : '';
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';
    
    // Array para armazenar mensagens de erro
    $erros = [];
    
    // Validar campos
    if (empty($nome)) {
        $erros[] = "O nome é obrigatório.";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ\s]+$/", $nome)) {
        $erros[] = "O nome deve conter apenas letras.";
    } elseif (str_word_count($nome) < 2) {
        $erros[] = "O nome deve conter pelo menos duas palavras.";
    }
    
    if (empty($cpf)) {
        $erros[] = "O CPF é obrigatório.";
    } elseif (!validarCPF($cpf)) {
        $erros[] = "CPF inválido.";
    }
    
    if (empty($email)) {
        $erros[] = "O email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email informado é inválido.";
    }
    
    if (empty($telefone)) {
        $erros[] = "O telefone é obrigatório.";
    } elseif (!validarTelefone($telefone)) {
        $erros[] = "Telefone inválido. Deve conter 11 dígitos e estar no formato correto (ex.: 11987654321).";
    }
    
    if (empty($usuario)) {
        $erros[] = "O nome de usuário é obrigatório.";
    }
    
    if (empty($senha)) {
        $erros[] = "A senha é obrigatória.";
    } elseif (strlen($senha) < 6) {
        $erros[] = "A senha deve ter no mínimo 6 caracteres.";
    }
    
    // Verificar se o CPF, email ou usuário já estão cadastrados (usando PDO)
    try {
        $sql = "SELECT cpf, email, usuario FROM Usuarios WHERE cpf = :cpf OR email = :email OR usuario = :usuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($resultado) > 0) {
            foreach ($resultado as $row) {
                if ($row['cpf'] == $cpf) {
                    $erros[] = "CPF já cadastrado.";
                }
                if ($row['email'] == $email) {
                    $erros[] = "Email já cadastrado.";
                }
                if ($row['usuario'] == $usuario) {
                    $erros[] = "Nome de usuário já está em uso.";
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Erro na consulta de verificação: " . $e->getMessage());
        $erros[] = "Erro ao verificar disponibilidade dos dados.";
    }
    
    // Se não houver erros, inserir no banco
    if (empty($erros)) {
        // Hash da senha para segurança
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        try {
            // Preparar e executar a consulta usando PDO
            $sql = "INSERT INTO Usuarios (Nome, CPF, Telefone, Email, Usuario, Senha) VALUES (:nome, :cpf, :telefone, :email, :usuario, :senha)";
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':senha', $senha_hash);
            
            // Tentar inserir os dados e verificar se houve sucesso
            if ($stmt->execute()) {
                // Redirecionar para o index com parâmetro de sucesso
                header("Location: index.php?cadastro=sucesso");
                exit(); // Importante para interromper a execução após o redirecionamento
            } else {
                throw new PDOException("Erro ao executar a consulta de inserção.");
            }
        } catch (PDOException $e) {
            error_log("Exceção no cadastro: " . $e->getMessage());
            $erros[] = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
    
    // Se houver erros, armazenar na sessão e voltar ao formulário
    if (!empty($erros)) {
        error_log("Erros encontrados: " . print_r($erros, true));
        $_SESSION['erros'] = $erros;
        $_SESSION['form_data'] = $_POST; // Manter os dados preenchidos
        
        // Redirecionar de volta para o formulário
        header("Location: cadDadosPessoais.php");
        exit();
    }
}
else {
    // Se alguém tentar acessar este arquivo diretamente
    error_log("Tentativa de acesso direto a cadastro.php sem POST");
    header("Location: cadDadosPessoais.php");
    exit();
}
?>
