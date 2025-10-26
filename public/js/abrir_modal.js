// function abrirModal(id) {
//   const modal = document.getElementById(id);
//   if (modal) {
//     modal.style.display = 'flex';
//   }
// }

// function fecharModal(id) {
//   const modal = document.getElementById(id);
//   if (modal) {
//     modal.style.display = 'none';
//   }
// }

// // Fecha modal ao clicar fora do conteúdo
// window.onclick = function(event) {
//   const modais = document.querySelectorAll('.modal');
//   modais.forEach(modal => {
//     if (event.target === modal) {
//       modal.style.display = 'none';
//     }
//   });
// }

// // Validação básica do formulário Login
// function validarLogin() {
//   const email = document.getElementById('emailLogin').value.trim();
//   const senha = document.getElementById('senhaLogin').value.trim();
//   const erroDiv = document.getElementById('loginErro');
//   erroDiv.textContent = '';

//   if (!email || !senha) {
//     erroDiv.textContent = 'Preencha todos os campos.';
//     return false;
//   }

//   // Aqui você pode adicionar chamada AJAX para enviar o login sem recarregar a página

//   // Para teste, fecha o modal e permite submit (remova se usar AJAX)
//   fecharModal('modalLogin');
//   return true;
// }

// // Validação básica do formulário Cadastro
// function validarCadastro() {
//   const nome = document.getElementById('nomeCadastro').value.trim();
//   const email = document.getElementById('emailCadastro').value.trim();
//   const senha = document.getElementById('senhaCadastro').value.trim();
//   const erroDiv = document.getElementById('cadastroErro');
//   erroDiv.textContent = '';

//   if (!nome || !email || !senha) {
//     erroDiv.textContent = 'Preencha todos os campos.';
//     return false;
//   }

//   // Você pode adicionar validações extras aqui (ex: senha mínima, formato de email, etc)

//   // Para teste, fecha o modal e permite submit (remova se usar AJAX)
//   fecharModal('modalCadastro');
//   return true;
// }
