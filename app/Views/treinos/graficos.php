<?php include __DIR__ . '/../templates/header.php'; ?>

<h2>Gráfico de Evolução</h2>

<canvas id="graficoEvolucao" width="600" height="300"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById("graficoEvolucao").getContext("2d");

        // Dados vindos do controller (passados do PHP para JS)
        const treinos = <?= json_encode($treinos ?? []); ?>;

        // Organizar dados para o gráfico
        const labels = treinos.map(t => t.data_fim || t.data_inicio);
        const qtdExercicios = treinos.map(t => parseInt(t.qtd_exercicios || 0));
        const pesoTotal = treinos.map(t => parseFloat(t.peso_total || 0));

        new Chart(ctx, {
            type: "bar", // ✅ gráfico de colunas
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Qtd Exercícios",
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
                        text: "Evolução dos Treinos"
                    }
                },
                scales: {
                    x: {
                        title: { display: true, text: "Data do Treino" }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: "Valores" }
                    }
                }
            }
        });
    });
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>