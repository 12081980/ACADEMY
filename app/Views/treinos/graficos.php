<?php include __DIR__ . '/../templates/header.php'; ?>

<h2>Gráfico de Evolução</h2>

<canvas id="graficoEvolucao" width="600" height="150"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById("graficoEvolucao").getContext("2d");

        // Dados vindos do controller (PHP → JS)
        const treinos = <?= json_encode($treinos ?? []); ?>;

        // Preparar arrays para o gráfico
        const labels = treinos.map(t => t.data_treino);
        const qtdExercicios = treinos.map(t => parseInt(t.qtd_exercicios));
        const pesoTotal = treinos.map(t => parseFloat(t.peso_total));

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Quantidade de Exercícios",
                        data: qtdExercicios,
                        backgroundColor: "rgba(54, 162, 235, 0.7)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 1
                    },
                    {
                        label: "Peso Total (kg)",
                        data: pesoTotal,
                        backgroundColor: "rgba(255, 99, 132, 0.7)",
                        borderColor: "rgba(255, 99, 132, 1)",
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "top" },
                    title: {
                        display: true,
                        text: "Evolução dos Treinos Realizados"
                    }
                },
                scales: {
                    x: { title: { display: true, text: "Data do Treino" } },
                    y: { beginAtZero: true, title: { display: true, text: "Valores" } }
                }
            }
        });
    });
</script>
<?php include __DIR__ . '/../templates/footer.php'; ?>