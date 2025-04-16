// Declarando as variáveis e funções no escopo global
let currentStep = 0;
let steps;
let progressBar;

// Funções de validação específicas para cada tipo de campo
function validarNome(nome) {
    // Nome deve conter apenas letras e espaços
    const regexNome = /^[a-zA-ZÀ-ÖØ-öø-ÿ\s]+$/;
    return regexNome.test(nome);
}

function validarCPF(cpf) {
    // Remover caracteres não numéricos
    const cpfNumerico = cpf.replace(/\D/g, '');
    // Verificar se tem exatamente 11 dígitos
    return cpfNumerico.length === 11;
}

function validarTelefone(telefone) {
    // Remover caracteres não numéricos
    const telefoneNumerico = telefone.replace(/\D/g, '');
    // Verificar se tem exatamente 11 dígitos (com DDD)
    return telefoneNumerico.length === 11;
}

function validarSenha(senha) {
    // Senha deve ter no mínimo 6 caracteres
    return senha.length >= 6;
}

// Função para validar os campos da etapa atual
function validateCurrentStep() {
    try {
        console.log("Iniciando validação da etapa:", currentStep);
        
        // Verificar se steps está definido
        if (!steps || steps.length === 0) {
            console.error("Array steps não está definido ou está vazio");
            return false;
        }
        
        const currentStepElement = steps[currentStep];
        console.log("Elemento da etapa atual:", currentStepElement);
        
        // Obter todos os campos de input dentro da etapa atual
        const requiredFields = currentStepElement.querySelectorAll('input[required]');
        console.log("Campos obrigatórios encontrados:", requiredFields.length);
        
        // Debugar cada campo encontrado
        requiredFields.forEach((field, index) => {
            console.log(`Campo ${index}: id=${field.id}, name=${field.name}, valor="${field.value}", vazio=${!field.value.trim()}`);
        });
        
        let isValid = true;
        
        // Limpar mensagens de erro anteriores
        const errorMessages = currentStepElement.querySelectorAll('.error-message');
        errorMessages.forEach(error => error.remove());
        
        // Verificar cada campo obrigatório
        requiredFields.forEach(field => {
            // Remover classes de erro anteriores
            field.classList.remove('input-error');
            field.classList.remove('input-valid');
            
            const valor = field.value.trim();
            let mensagemErro = '';
            
            // 1. Verificar se o campo está vazio
            if (!valor) {
                isValid = false;
                mensagemErro = 'Este campo é obrigatório';
            } 
            // 2. Se não estiver vazio, validar conforme o tipo de campo
            else {
                // Usar ID ou name para identificar o campo - mais flexível
                const fieldIdentifier = field.id.toLowerCase() || field.name.toLowerCase();
                console.log("Validando campo:", fieldIdentifier);
                
                if (fieldIdentifier.includes('nome')) {
                    if (!validarNome(valor)) {
                        isValid = false;
                        mensagemErro = 'O nome deve conter apenas letras';
                    }
                }
                else if (fieldIdentifier.includes('cpf')) {
                    if (!validarCPF(valor)) {
                        isValid = false;
                        mensagemErro = 'O CPF deve conter 11 números';
                    }
                }
                else if (fieldIdentifier.includes('telefone')) {
                    if (!validarTelefone(valor)) {
                        isValid = false;
                        mensagemErro = 'O telefone deve conter 11 números (com DDD)';
                    }
                }
                else if (fieldIdentifier.includes('senha')) {
                    if (!validarSenha(valor)) {
                        isValid = false;
                        mensagemErro = 'A senha deve ter no mínimo 6 caracteres';
                    }
                }
            }
            
            // Se houve erro, mostrar mensagem
            if (mensagemErro) {
                // Adicionar classe de erro ao campo
                field.classList.add('input-error');
                
                // Criar mensagem de erro
                const errorMsg = document.createElement('div');
                errorMsg.className = 'error-message';
                errorMsg.innerText = mensagemErro;
                errorMsg.style.color = 'red';
                errorMsg.style.fontSize = '12px';
                errorMsg.style.marginTop = '5px';
                
                // Inserir a mensagem após o campo
                field.parentNode.appendChild(errorMsg);
                
                // Focar no primeiro campo com erro
                if (document.querySelectorAll('.input-error').length === 1) {
                    field.focus();
                }
                
                console.log(`Campo ${field.id || field.name} inválido: ${mensagemErro}`);
            } else {
                // Marcar campo como válido
                field.classList.add('input-valid');
            }
        });
        
        console.log("Resultado da validação:", isValid ? "Válido" : "Inválido");
        return isValid;
    } catch (error) {
        console.error("Erro durante a validação:", error);
        return false;
    }
}

// Função para avançar para a próxima etapa - declarada globalmente
function nextStep() {
    console.log("Função nextStep() chamada. Etapa atual antes de avançar:", currentStep);
    
    // Validar campos da etapa atual antes de avançar
    const isValid = validateCurrentStep();
    console.log("Resultado da validação:", isValid);
    
    if (!isValid) {
        console.log("Validação falhou. Permanecendo na etapa:", currentStep);
        // Removido o alert
        return false; // Não avança se a validação falhar
    }
    
    // Verificar se estamos na última etapa
    if (currentStep < steps.length - 1) {
        // Remover classe 'active' da etapa atual
        steps[currentStep].classList.remove('active');
        
        // Avançar para a próxima etapa
        currentStep++;
        
        // Adicionar classe 'active' à nova etapa atual
        steps[currentStep].classList.add('active');
        
        // Atualizar a barra de progresso
        updateProgressBar();
        console.log("Avançou para etapa:", currentStep);
    }
    
    return true;
}

// Função para voltar para a etapa anterior - declarada globalmente
function prevStep() {
    console.log("Função prevStep() chamada. Etapa atual antes de retroceder:", currentStep);
    // Verificar se não estamos na primeira etapa
    if (currentStep > 0) {
        // Remover classe 'active' da etapa atual
        steps[currentStep].classList.remove('active');
        
        // Voltar para a etapa anterior
        currentStep--;
        
        // Adicionar classe 'active' à nova etapa atual
        steps[currentStep].classList.add('active');
        
        // Atualizar a barra de progresso
        updateProgressBar();
        console.log("Voltou para etapa:", currentStep);
    }
}

// Função para atualizar a barra de progresso
function updateProgressBar() {
    const progressPercentage = (currentStep / (steps.length - 1)) * 100;
    progressBar.style.width = progressPercentage + '%';
    console.log("Barra de progresso atualizada para:", progressPercentage + '%');
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM carregado, inicializando...");
    
    steps = document.querySelectorAll('.step');
    progressBar = document.getElementById('progress');

    // Verificar se os elementos foram encontrados
    if (!steps.length) {
        console.error("Elementos com classe .step não foram encontrados!");
        return;
    }
    
    if (!progressBar) {
        console.error("Elemento com id 'progress' não foi encontrado!");
        return;
    }
    
    console.log("Total de etapas encontradas:", steps.length);
    
    // Inicializar a barra de progresso
    updateProgressBar();

    // Prevenir envio do formulário se Enter for pressionado
    const form = document.getElementById('multiStepForm');
    if (form) {
        form.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && currentStep < steps.length - 1) {
                e.preventDefault();
                nextStep();
            }
        });
        
        // Validar todos os campos antes de enviar o formulário
        form.addEventListener('submit', function(e) {
            // Verificar campos da última etapa - apenas logar, não prevenir o envio
            const isValid = validateCurrentStep();
            console.log("Formulário sendo enviado, validação:", isValid ? "Válida" : "Inválida");
            
            // Se estamos na última etapa e o formulário não é válido, prevenir o envio
            if (!isValid) {
                e.preventDefault();
                console.log("Formulário não enviado devido a campos obrigatórios vazios.");
                alert("Por favor, preencha todos os campos corretamente antes de enviar."); // Adicionei alerta para o usuário
            } else {
                console.log("Formulário validado com sucesso. Enviando...");
            }
        });
    } else {
        console.error("Formulário não encontrado!");
    }
    
    // Substituir os eventos onclick dos botões para garantir o funcionamento
    document.querySelectorAll('button[onclick="nextStep()"]').forEach(button => {
        button.onclick = function(e) {
            e.preventDefault();
            nextStep();
        };
    });
    
    document.querySelectorAll('button[onclick="prevStep()"]').forEach(button => {
        button.onclick = function(e) {
            e.preventDefault();
            prevStep();
        };
    });

    // Aplicar máscaras para facilitar entrada de dados
    const cpfInput = document.getElementById('CPF');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            
            if (value.length > 9) {
                value = value.replace(/^(\d{3})(\d{3})(\d{3})/, '$1.$2.$3-');
            } else if (value.length > 6) {
                value = value.replace(/^(\d{3})(\d{3})/, '$1.$2.');
            } else if (value.length > 3) {
                value = value.replace(/^(\d{3})/, '$1.');
            }
            
            e.target.value = value;
        });
    }
    
    const telefoneInput = document.getElementById('Telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            
            if (value.length > 10) {
                value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length > 6) {
                value = value.replace(/^(\d{2})(\d{4})/, '($1) $2-');
            } else if (value.length > 2) {
                value = value.replace(/^(\d{2})/, '($1) ');
            }
            
            e.target.value = value;
        });
    }

    // Configurar validação em tempo real nos campos
    document.querySelectorAll('input[required]').forEach(field => {
        field.addEventListener('blur', function() {
            // Remover classes de erro anteriores
            this.classList.remove('input-error');
            this.classList.remove('input-valid');
            
            // Remover mensagens de erro anteriores
            const previousErrors = this.parentNode.querySelectorAll('.error-message');
            previousErrors.forEach(error => error.remove());
            
            const valor = this.value.trim();
            let mensagemErro = '';
            
            // Verificar se o campo está vazio
            if (!valor) {
                mensagemErro = 'Este campo é obrigatório';
            } else {
                // Validar conforme o tipo de campo
                const fieldIdentifier = this.id.toLowerCase() || this.name.toLowerCase();
                
                if (fieldIdentifier.includes('nome')) {
                    if (!validarNome(valor)) {
                        mensagemErro = 'O nome deve conter apenas letras';
                    }
                }
                else if (fieldIdentifier.includes('cpf')) {
                    if (!validarCPF(valor)) {
                        mensagemErro = 'O CPF deve conter 11 números';
                    }
                }
                else if (fieldIdentifier.includes('telefone')) {
                    if (!validarTelefone(valor)) {
                        mensagemErro = 'O telefone deve conter 11 números (com DDD)';
                    }
                }
                else if (fieldIdentifier.includes('senha')) {
                    if (!validarSenha(valor)) {
                        mensagemErro = 'A senha deve ter no mínimo 6 caracteres';
                    }
                }
            }
            
            // Se houve erro, mostrar mensagem
            if (mensagemErro) {
                this.classList.add('input-error');
                
                const errorMsg = document.createElement('div');
                errorMsg.className = 'error-message';
                errorMsg.innerText = mensagemErro;
                errorMsg.style.color = 'red';
                errorMsg.style.fontSize = '12px';
                errorMsg.style.marginTop = '5px';
                
                this.parentNode.appendChild(errorMsg);
            } else {
                this.classList.add('input-valid');
            }
        });
    });
});
