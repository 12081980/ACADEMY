document.addEventListener('DOMContentLoaded', () => {

    const btn = document.getElementById('btnIniciarCardio');
    if (!btn) return;

    btn.addEventListener('click', async () => {

        const tipo  = document.getElementById('tipoCardio').value;
        const tempo = document.getElementById('tempoCardio').value;
        const ritmo = document.getElementById('ritmoCardio').value;

        if (!tipo || !tempo) {
            alert('Informe o tipo e o tempo do cardio');
            return;
        }

        try {
            const response = await fetch('/ACADEMY/public/cardio/iniciar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ tipo, tempo, ritmo })
            });

            const data = await response.json();

            if (data.status === 'ok') {
                window.location.href = data.redirect;
            } else {
                alert(data.mensagem || 'Erro ao iniciar cardio');
            }

        } catch (e) {
            alert('Erro ao iniciar cardio');
        }
    });
});
