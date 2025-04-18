document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("multiStepForm");
    const submitBtn = document.getElementById("submitBtn");
    const toastContainer = document.createElement("div");
    toastContainer.id = "toast-container";
    document.body.appendChild(toastContainer);

    const cpfInput = document.getElementById("CPF");
    const emailInput = document.getElementById("Email");
    const telefoneInput = document.getElementById("Telefone");
    const usuarioInput = document.getElementById("usuario");
    const senhaInput = document.getElementById("senha");
    const confirmaSenhaInput = document.getElementById("confirmaSenha");
    const senhaForca = document.getElementById("senha-forca");
    const nomeInput = document.getElementById("Nome");

    // Validação ao clicar no botão "Cadastrar"
    submitBtn.addEventListener("click", async (e) => {
        e.preventDefault();
        const erros = await validarFormulario();

        if (erros.length > 0) {
            exibirToast(erros);
        } else {
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                exibirToast([{ campo: null, mensagem: "Por favor, complete o CAPTCHA." }]);
                return;
            }
            form.submit();
        }
    });

    // Validação em tempo real do nome de usuário
    usuarioInput.addEventListener("input", async () => {
        const usuario = usuarioInput.value.trim();
        const usuarioStatus = document.getElementById("usuario-status");
        if (usuario.length > 0) {
            usuarioStatus.textContent = "Verificando...";
            usuarioStatus.className = "status-message verificando";
            try {
                const disponivel = await verificarUsuarioNoBanco(usuario);
                usuarioStatus.textContent = disponivel ? "Disponível" : "Indisponível";
                usuarioStatus.className = disponivel ? "status-message valido" : "status-message invalido";
            } catch (error) {
                usuarioStatus.textContent = error.message || "Erro ao verificar disponibilidade.";
                usuarioStatus.className = "status-message erro";
            }
        } else {
            usuarioStatus.textContent = "";
        }
    });

    // Validação em tempo real da força da senha
    senhaInput.addEventListener("input", () => {
        const senha = senhaInput.value;
        const forca = calcularForcaSenha(senha);
        senhaForca.textContent = `Força da senha: ${forca}`;
    });

    async function validarFormulario() {
        const erros = [];

        // Validação do Nome
        const nome = nomeInput.value.trim();
        if (nome.length === 0) {
            erros.push({ campo: nomeInput, mensagem: "O nome é obrigatório." });
        } else if (!/^[a-zA-ZÀ-ÖØ-öø-ÿ\s]+$/.test(nome)) {
            erros.push({ campo: nomeInput, mensagem: "O nome deve conter apenas letras." });
        } else if (nome.split(" ").length < 2) {
            erros.push({ campo: nomeInput, mensagem: "O nome deve conter pelo menos duas palavras." });
        }

        // Validação do CPF
        const cpf = cpfInput.value.replace(/\D/g, "");
        if (!validaCPF(cpf)) {
            erros.push({ campo: cpfInput, mensagem: "CPF inválido." });
        } else {
            try {
                const existeNoBanco = await verificarCPFNoBanco(cpf);
                if (existeNoBanco) {
                    erros.push({ campo: cpfInput, mensagem: "CPF já cadastrado." });
                }
            } catch {
                erros.push({ campo: cpfInput, mensagem: "Erro ao verificar CPF no banco." });
            }
        }

        // Validação do Telefone
        const telefone = telefoneInput.value.trim();
        if (!validarTelefone(telefone)) {
            erros.push({ campo: telefoneInput, mensagem: "Telefone inválido. Deve conter 11 dígitos e estar no formato correto (ex.: 11987654321)." });
        }

        // Validação do Email
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email.length === 0) {
            erros.push({ campo: emailInput, mensagem: "O email é obrigatório." });
        } else if (!emailRegex.test(email)) {
            erros.push({ campo: emailInput, mensagem: "O email informado é inválido." });
        }

        // Validação do Nome de Usuário
        const usuario = usuarioInput.value.trim();
        if (usuario.length === 0) {
            erros.push({ campo: usuarioInput, mensagem: "O campo de nome de usuário não pode estar vazio." });
        } else {
            try {
                const disponivel = await verificarUsuarioNoBanco(usuario);
                if (!disponivel) {
                    erros.push({ campo: usuarioInput, mensagem: "Nome de usuário já está em uso." });
                }
            } catch {
                erros.push({ campo: usuarioInput, mensagem: "Erro ao verificar nome de usuário no banco." });
            }
        }

        // Validação da Senha
        const senha = senhaInput.value;
        if (!validarSenha(senha)) {
            erros.push({ campo: senhaInput, mensagem: "A senha deve ter entre 6 e 20 caracteres, incluindo letras maiúsculas, minúsculas, números e caracteres especiais." });
        }

        // Validação da Confirmação de Senha
        const confirmaSenha = confirmaSenhaInput.value;
        if (senha !== confirmaSenha) {
            erros.push({ campo: confirmaSenhaInput, mensagem: "As senhas não coincidem." });
        }

        return erros;
    }

    function validaCPF(cpf) {
        let Soma = 0;
        let Resto;

        const strCPF = String(cpf).replace(/[^\d]/g, '');

        if (strCPF.length !== 11) return false;

        if ([
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
        ].includes(strCPF)) return false;

        for (let i = 1; i <= 9; i++) {
            Soma += parseInt(strCPF.substring(i - 1, i)) * (11 - i);
        }

        Resto = (Soma * 10) % 11;
        if (Resto === 10 || Resto === 11) Resto = 0;
        if (Resto !== parseInt(strCPF.substring(9, 10))) return false;

        Soma = 0;
        for (let i = 1; i <= 10; i++) {
            Soma += parseInt(strCPF.substring(i - 1, i)) * (12 - i);
        }

        Resto = (Soma * 10) % 11;
        if (Resto === 10 || Resto === 11) Resto = 0;
        if (Resto !== parseInt(strCPF.substring(10, 11))) return false;

        return true;
    }

    async function verificarCPFNoBanco(cpf) {
        const response = await fetch(`verificar_cpf.php?cpf=${cpf}`);
        if (!response.ok) {
            throw new Error("Erro ao verificar CPF no banco.");
        }
        const data = await response.json();
        return data.existe;
    }

    async function verificarUsuarioNoBanco(usuario) {
        const response = await fetch(`verificar_usuario.php?usuario=${usuario}`);
        if (!response.ok) {
            throw new Error("Erro ao verificar nome de usuário no banco.");
        }
        const data = await response.json();
        return !data.existe;
    }

    function calcularForcaSenha(senha) {
        let forca = 0;
        if (senha.length >= 6) forca++;
        if (/[A-Z]/.test(senha)) forca++;
        if (/[a-z]/.test(senha)) forca++;
        if (/\d/.test(senha)) forca++;
        if (/[@$!%*?&]/.test(senha)) forca++;
        return ["Muito Fraca", "Fraca", "Média", "Forte", "Muito Forte"][forca - 1] || "Muito Fraca";
    }

    function validarSenha(senha) {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,20}$/;
        return regex.test(senha);
    }

    function validarTelefone(telefone) {
        const telefoneNumerico = telefone.replace(/\D/g, "");

        if (telefoneNumerico.length !== 11) {
            return false;
        }

        const ddd = telefoneNumerico.substring(0, 2);
        if (!/^[1-9][0-9]$/.test(ddd)) {
            return false;
        }

        const numero = telefoneNumerico.substring(2);
        if (!/^9[0-9]{8}$/.test(numero)) {
            return false;
        }

        return true;
    }

    function exibirToast(erros) {
        toastContainer.innerHTML = "";
        const primeiroErro = erros[0];
        if (primeiroErro.campo) primeiroErro.campo.focus();
        erros.forEach((erro) => {
            const toast = document.createElement("div");
            toast.className = "toast";
            toast.textContent = erro.mensagem;
            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 5000);
        });
    }
});
