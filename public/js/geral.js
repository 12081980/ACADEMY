// ========== FUNÇÕES AUXILIARES ==========
async function postFormData(url, form) {
    const response = await fetch(url, { method: 'POST', body: new FormData(form) });
    return response.json();
}

async function postSimple(url) {
    const response = await fetch(url, { method: 'POST' });
    return response.json();
}

// ========== MODAIS ==========
function abrirModal(id) {
    document.getElementById(id)?.classList.add("open");
}
function fecharModal(id) {
    document.getElementById(id)?.classList.remove("open");
}
document.addEventListener('click', e => {
    if (e.target.matches('[data-modal]')) abrirModal(e.target.dataset.modal);
    if (e.target.matches('[data-close]')) fecharModal(e.target.closest('.modal').id);
});
window.addEventListener('click', e => {
    document.querySelectorAll('.modal').forEach(modal => {
        if (e.target === modal) fecharModal(modal.id);
    });
});

// ========== CADASTRO ==========
// document.getElementById('formCadastro')?.addEventListener('submit', async function(e) {
//     e.preventDefault();
//     try {
//         const data = await postFormData('/ACADEMY/public/register', this);
//         alert(data.mensagem);
//         if (data.status === 'sucesso') window.location.href = data.redirect;
//     } catch(err) {
//         console.error(err);
//         alert('Erro ao conectar com o servidor.');
//     }
// });

// ========== LOGIN ==========
// document.getElementById('formLogin')?.addEventListener('submit', async function(e) {
//     e.preventDefault();
//     try {
//         const data = await postFormData('/ACADEMY/public/login', this);
//         if (data.status === 'sucesso') window.location.href = data.redirect;
//         else alert(data.mensagem);
//     } catch(err) {
//         console.error(err);
//         alert('Erro ao conectar com o servidor.');
//     }
// });

// ========== INICIAR TREINO ==========
// document.querySelectorAll("form[id^='formTreino']").forEach(form => {
//     form.addEventListener("submit", async function(e) {
//         e.preventDefault();

//         const formData = new FormData(form);
//         const nomes = formData.getAll('exercicio[]');

//         if (!nomes.some(nome => nome.trim() !== '')) {
//             alert('Preencha pelo menos um exercício!');
//             return;
//         }

//         const series = formData.getAll('series[]');
//         const repeticoes = formData.getAll('repeticoes[]');
//         const pesos = formData.getAll('peso[]');
//         const treino = formData.get('treino') || 'Treino sem nome';

//         const exercicios = nomes.map((nome, i) => ({
//             nome,
//             series: series[i] || 0,
//             repeticoes: repeticoes[i] || 0,
//             peso: pesos[i] || 0
//         }));

//         const payload = new FormData();
//         payload.append("nome", treino);
//         payload.append("descricao", "Treino personalizado");
//         payload.append("exercicios", JSON.stringify(exercicios));

//         try {
//             const response = await fetch("/ACADEMY/public/treinos/iniciar", {
//                 method: "POST",
//                 body: payload
//             });

//             let data;
//             try {
//                 data = await response.json();
//             } catch (e) {
//                 console.error("Resposta inválida do servidor:", await response.text());
//                 alert("Erro inesperado no servidor.");
//                 return;
//             }

//             if (data.status === "sucesso") {
//                 window.location.href = data.redirect;
//             } else {
//                 alert(data.mensagem);
//             }
//         } catch (err) {
//             console.error(err);
//             alert("Erro ao conectar com o servidor.");
//         }
//     });
// });

// ========== FINALIZAR TREINO ==========
// (você ainda precisa implementar aqui)

// ========== EXCLUIR CONTA ==========
// document.getElementById('btnExcluirConta')?.addEventListener('click', async () => {
//     if (!confirm("Tem certeza que deseja excluir sua conta? Esta ação não poderá ser desfeita.")) return;

//     try {
//         const data = await postSimple('/ACADEMY/public/usuario/excluir-perfil');
//         alert(data.mensagem);
//         if (data.status === "sucesso") window.location.href = data.redirect;
//     } catch(err) {
//         console.error(err);
//         alert("Erro ao conectar com o servidor.");
//     }
// });

// ========== ATUALIZAR PERFIL ==========
// document.getElementById('formPerfil')?.addEventListener('submit', async function(e) {
//     e.preventDefault();
//     try {
//         const data = await postFormData('/ACADEMY/public/usuario/atualizar', this);
//         alert(data.mensagem);
//         if (data.status === "sucesso") window.location.href = data.redirect;
//     } catch(err) {
//         console.error(err);
//         alert("Erro ao conectar com o servidor.");
//     }
// });

// const formPerfil = document.getElementById('formPerfil');
// const btnExcluir = document.getElementById('btnExcluirPerfil');

// // Atualizar perfil
// formPerfil.addEventListener('submit', async function(e) {
//     e.preventDefault();
//     const formData = new FormData(this);

//     try {
//         const res = await fetch('/ACADEMY/public/usuario/atualizar', {
//             method: 'POST',
//             body: formData
//         });
//         const data = await res.json();
//         alert(data.mensagem);
//         if (data.status === 'sucesso' && data.redirect) {
//             window.location.href = data.redirect;
//         }
//     } catch (err) {
//         console.error(err);
//         alert('Erro ao atualizar perfil.');
//     }
// });

// Excluir perfil
// btnExcluir.addEventListener('click', async function() {
//     if (!confirm('Tem certeza que deseja excluir seu perfil?')) return;

//     try {
//         const res = await fetch('/ACADEMY/public/usuario/excluir-perfil', {
//             method: 'POST'
//         });
//         const data = await res.json();
//         alert(data.mensagem);
//         if (data.status === 'sucesso' && data.redirect) {
//             window.location.href = data.redirect;
//         }
//     } catch (err) {
//         console.error(err);
//         alert('Erro ao excluir perfil.');
//     }
// });
