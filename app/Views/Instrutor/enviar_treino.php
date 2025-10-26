<?php


if (session_status() === PHP_SESSION_NONE)
    session_start();
?>
<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial;
            margin: 20px;
        }

        form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            max-width: 800px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        button,
        .btn {
            background-color: #007bff;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-btn {
            margin-top: 10px;
            background-color: #28a745;
        }

        .add-btn:hover {
            background-color: #218838;
        }
    </style> -->
</head>

<body>

    <h1>Enviar Treino</h1>

    <?php if (!empty($_SESSION['msg_erro'])): ?>
        <div style="color:red"><?= $_SESSION['msg_erro'];
        unset($_SESSION['msg_erro']); ?></div>
    <?php endif; ?>

    <form action="/ACADEMY/public/instrutor/enviar_treino" method="POST">

        <label for="usuario_id">Selecione o Aluno:</label>
        <select name="usuario_id" id="usuario_id" required>
            <option value="">-- Selecione um aluno --</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?> (<?= htmlspecialchars($u['email']) ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label for="treino_tipo">Escolha o Treino:</label>
        <select name="treino_tipo" id="treino_tipo" required>
            <option value="">-- Selecione o tipo --</option>
            <option value="A">Treino A</option>
            <option value="B">Treino B</option>
            <option value="C">Treino C</option>
            <option value="D">Treino D</option>
        </select>

        <h2>Exercícios</h2>
        <table id="tabela-exercicios">
            <thead>
                <tr>
                    <th>Nome do Exercício</th>
                    <th>Séries</th>
                    <th>Repetições</th>
                    <th>Carga (kg)</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="exercicios[0][nome]" id="select-exercicio" required>
                            <option value="">-- Escolha o exercício --</option>
                        </select>
                    </td>
                    <td><input type="number" name="exercicios[0][series]" required></td>
                    <td><input type="number" name="exercicios[0][repeticoes]" required></td>
                    <td><input type="number" name="exercicios[0][carga]" step="0.1" required></td>
                    <td><button type="button" class="btn remover-linha">Remover</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="add-btn" id="add-exercicio">+ Adicionar Exercício</button>
        <br><br>
        <button type="submit">Enviar Treino</button>

    </form>

    <script>
        // Listas fixas de exercícios por tipo de treino
        const exerciciosPorTreino = {
            A: [
                "Mesa Flexora",
                "Elevação de Pelve",
                "V Squat",
                "Cadeira Abdutora",
                "Glúteo 4 Apoio",
                "Dead Bug",
                "Prancha"
            ],
            B: [
                "Remada Curvada Banco",
                "Remada Convergente Peg Neutra",
                "Puxada Triângulo",
                "Desenvolvimento Máquina",
                "Tríceps Pulley",
                "Rosca Direta Halter",
                "Prancha Lateral",
                "Bird Dog"
            ],
            C: [
                "Leg Press 180°",
                "Cadeira Extensora",
                "Cadeira Abdutora",
                "Elevação de Quadril Unilateral",
                "Afundo Step Atrás"
            ],
            D: [
                "Remada Unilateral Halter",
                "Face Pull",
                "Crucifixo Invertido Peck Deck",
                "Superman",
                "Prancha",
                "Alongamento Lombar e Torácico"
            ]
        };

        let index = 1;
        const selectTreino = document.getElementById('treino_tipo');
        const tabela = document.getElementById('tabela-exercicios').querySelector('tbody');

        // Função para atualizar os exercícios conforme o treino
        function atualizarSelects(treino) {
            const lista = exerciciosPorTreino[treino] || [];
            const options = ['<option value="">-- Escolha o exercício --</option>']
                .concat(lista.map(ex => `<option value="${ex}">${ex}</option>`))
                .join('');

            // Atualiza todos os selects existentes
            tabela.querySelectorAll('select').forEach(sel => sel.innerHTML = options);
        }

        // Detecta mudança no tipo de treino
        selectTreino.addEventListener('change', e => {
            atualizarSelects(e.target.value);
        });

        // Botão para adicionar nova linha
        document.getElementById('add-exercicio').addEventListener('click', function () {
            const treino = selectTreino.value;
            const lista = exerciciosPorTreino[treino] || [];
            const options = ['<option value="">-- Escolha o exercício --</option>']
                .concat(lista.map(ex => `<option value="${ex}">${ex}</option>`))
                .join('');

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><select name="exercicios[${index}][nome]" required>${options}</select></td>
                <td><input type="number" name="exercicios[${index}][series]" required></td>
                <td><input type="number" name="exercicios[${index}][repeticoes]" required></td>
                <td><input type="number" name="exercicios[${index}][carga]" step="0.1" required></td>
                <td><button type="button" class="btn remover-linha">Remover</button></td>
            `;
            tabela.appendChild(row);
            index++;
        });

        // Remover linha
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remover-linha')) {
                e.target.closest('tr').remove();
            }
        });
    </script>