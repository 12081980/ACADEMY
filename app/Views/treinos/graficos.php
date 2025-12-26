<?php include __DIR__ . '/../templates/header.php'; ?>

<h2>Gráfico de Evolução</h2>

<canvas id="graficoEvolucao"></canvas>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("graficoEvolucao").getContext("2d");

    // Dados do PHP
    const treinos = <?= json_encode($treinos ?? []); ?>;

    // Arrays para o gráfico
    const labels = treinos.map(t => t.data_treino);
    const qtdExercicios = treinos.map(t => parseInt(t.qtd_exercicios));
    const pesoTotal = treinos.map(t => parseFloat(t.peso_total));
    const volume = treinos.map(t => parseFloat(t.peso_total) * parseInt(t.repeticoes_total ?? 0));
    const mediaCarga = treinos.map(t => parseFloat(t.peso_total) / (parseInt(t.qtd_exercicios) || 1));

    new Chart(ctx, {
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Peso Total (kg)",
                    type: "bar",
                    data: pesoTotal,
                    backgroundColor: "rgba(255, 99, 132, 0.7)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    borderWidth: 1,
                    yAxisID: "y"
                },
                {
                    label: "Qtd. Exercícios",
                    type: "bar",
                    data: qtdExercicios,
                    backgroundColor: "rgba(54, 162, 235, 0.7)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1,
                    yAxisID: "y"
                },
                {
                    label: "Média de Carga (kg/exercício)",
                    type: "line",
                    data: mediaCarga,
                    borderColor: "rgba(255, 205, 86, 1)",
                    borderWidth: 3,
                    tension: 0.4,
                    yAxisID: "y1"
                },
                {
                    label: "Volume Total (Carga x Repetições)",
                    type: "line",
                    data: volume,
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 3,
                    tension: 0.4,
                    yAxisID: "y1"
                }
            ]
        },
        options: {
            responsive: true,
             maintainAspectRatio: false,
    
            plugins: {
                tooltip: {
                    callbacks: {
                        afterBody: function(context) {
                            const i = context[0].dataIndex;
                            return [
                                "—— Detalhes ——",
                                "Exercícios: " + qtdExercicios[i],
                                "Peso Total: " + pesoTotal[i].toFixed(2) + " kg",
                                "Volume: " + volume[i].toFixed(2),
                                "Média Carga: " + mediaCarga[i].toFixed(2) + " kg/ex"
                            ];
                        }
                    }
                },
                title: {
                    display: true,
                    text: "Evolução Completa dos Treinos"
                },
                legend: { position: "top" }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: "Peso / Exercícios" }
                },
                y1: {
                    position: "right",
                    beginAtZero: true,
                    title: { display: true, text: "Volume / Média" },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>