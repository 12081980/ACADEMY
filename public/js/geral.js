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

