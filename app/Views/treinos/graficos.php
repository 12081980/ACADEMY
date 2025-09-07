<?php
// Garante que a sessão esteja iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nome do usuário
$usuarioNome = $_SESSION['usuario']['nome'] ?? 'Usuário';

// Garante que $dadosTreinos existe
if (!isset($dadosTreinos) || !is_array($dadosTreinos)) {
    $dadosTreinos = [];
}

// Formata os dados para JS
$dadosTreinosJson = json_encode(
    $dadosTreinos,
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
);
?>

<?php
include __DIR__ . '/../../Views/templates/header.php';
?>

<h1>Evolução do Treino de <?= htmlspecialchars($usuarioNome) ?></h1>

<canvas id="graficoEvolucao" width="500" height="200"></canvas>
<br>
<button onclick="window.location.href='/ACADEMY/public/home'">Voltar</button>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dadosTreinos = <?= $dadosTreinosJson ?>;

    const labels = dadosTreinos.map(t => {
        if (t.data_realizacao) {
            const dt = new Date(t.data_realizacao);
            return dt.toLocaleDateString('pt-BR');
        }
        return '—';
    });

    const duracoes = dadosTreinos.map(t => t.duracao ? parseFloat(t.duracao) : 0);

    const quantExercicios = dadosTreinos.map(t => {
        if (t.exercicios) {
            try {
                const exs = JSON.parse(t.exercicios);
                return exs.length;
            } catch {
                return 0;
            }
        }
        return 0;
    });

    const pesos = dadosTreinos.map(t => t.peso ? parseFloat(t.peso) : null);

    const ctx = document.getElementById('graficoEvolucao').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Duração do Treino (min)',
                    data: duracoes,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.3,
                    yAxisID: 'y'
                },
                {
                    label: 'Quantidade de Exercícios',
                    data: quantExercicios,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                    tension: 0.3,
                    yAxisID: 'y1'
                },
                {
                    label: 'Peso (kg)',
                    data: pesos,
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    fill: false,
                    tension: 0.3,
                    yAxisID: 'y2'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            stacked: false,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: { display: true, text: 'Duração (min)' }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    offset: true,
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Quantidade de Exercícios' }
                },
                y2: {
                    beginAtZero: false,
                    position: 'right',
                    offset: true,
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Peso (kg)' }
                },
                x: { title: { display: true, text: 'Data' } }
            }
        }
    });
</script>
<?php
include __DIR__ . '/../../Views/templates/footer.php';
?>