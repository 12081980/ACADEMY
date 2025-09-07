   function abrirModal(id) {
        document.getElementById(id).classList.add("open");
    }

    function fecharModal(id) {
        document.getElementById(id).classList.remove("open");
    }

    // Fecha modal ao clicar fora
    window.onclick = function (event) {
        document.querySelectorAll('.modal').forEach(modal => {
            if (event.target === modal) {
                modal.classList.remove('open');
            }
        });
    }


     document.getElementById('formCadastro').addEventListener('submit', function (e) {
        e.preventDefault();

        fetch('/ACADEMY/public/register', {
            method: 'POST',
            body: new FormData(this)
        })
            .then(res => res.json())
            .then(data => {
                alert(data.mensagem);
                if (data.status === 'sucesso') {
                    window.location.href = data.redirect;
                }
            })
            .catch(err => {
                console.error('Erro:', err);
                alert('Erro ao conectar com o servidor.');
            });
    });

       document.addEventListener('click', e => {
        if (e.target.matches('[data-modal]')) {
            document.getElementById(e.target.dataset.modal).classList.add('open');
        }
        if (e.target.matches('[data-close]')) {
            e.target.closest('.modal').classList.remove('open');
        }
    });

    window.onclick = function (event) {
        document.querySelectorAll('.modal').forEach(modal => {
            if (event.target === modal) modal.classList.remove('open');
        });
    };

    document.querySelectorAll("form[id^='formTreino']").forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault(); // Previne envio automático

            const formData = new FormData(form);

            const nomes = formData.getAll('exercicio[]');
            const series = formData.getAll('series[]');
            const repeticoes = formData.getAll('repeticoes[]');
            const pesos = formData.getAll('peso[]');
            const treino = formData.get('treino');

            // Monta array de exercícios
            const exercicios = nomes.map((nome, i) => ({
                nome,
                series: series[i],
                repeticoes: repeticoes[i],
                peso: pesos[i]
            }));

            // Prepara dados finais
            const payload = new FormData();
            payload.append("nome", treino);
            payload.append("descricao", "Treino personalizado");
            payload.append("exercicios", JSON.stringify(exercicios));

            // Envia com fetch
            fetch("/ACADEMY/public/treinos/iniciar", {
                method: "POST",
                body: payload
            }).then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.text().then(text => alert("Erro: " + text));
                }
            });
        });
    });

    document.getElementById('formLogin').addEventListener('submit', function (e) {
        e.preventDefault();

        fetch('/ACADEMY/public/login', {
            method: 'POST',
            body: new FormData(this)
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'sucesso') {
                    window.location.href = data.redirect;
                } else {
                    alert(data.mensagem);
                }
            })
            .catch(err => {
                console.error('Erro:', err);
                alert('Erro ao conectar com o servidor.');
            });
    });