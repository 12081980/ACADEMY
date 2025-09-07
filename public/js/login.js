//  document.addEventListener("DOMContentLoaded", () => {
//      const formLogin = document.getElementById("formLogin");

//      if (formLogin) {
//          formLogin.addEventListener("submit", async function(e) {
//              e.preventDefault();

//              let formData = new FormData(this);

//              try {
//                  let resp = await fetch("/login/autenticar", {
//                      method: "POST",
//                      body: formData
//                  });

//                  let dados = await resp.json();

//                  if (dados.status === "sucesso") {
//                      window.location.href = dados.redirect;
//                  } else {
//                      alert(dados.mensagem);
//                  }
//              } catch (err) {
//                  alert("Erro inesperado ao tentar fazer login.");
//              }
//          });
//      }
//  });
