<section class="treinos-grid">
    <!-- CARD A -->
    <article class="treino-card">
        <h3>✔ Treino A</h3>
        <span>Glúteos + Posteriores + Core</span>
        <button class="btn" data-modal="modalA">Ver detalhes</button>
    </article>

    <!-- CARD B -->
    <article class="treino-card">
        <h3>✔ Treino B</h3>
        <span>Membros Superiores + Core</span>
        <button class="btn" data-modal="modalB">Ver detalhes</button>
    </article>

    <!-- CARD C -->
    <article class="treino-card">
        <h3>✔ Treino C</h3>
        <span>Quadríceps + Glúteos + Core</span>
        <button class="btn" data-modal="modalC">Ver detalhes</button>
    </article>

    <!-- CARD D -->
    <article class="treino-card">
        <h3>✔ Treino D</h3>
        <span>Superiores Posturais + Core</span>
        <button class="btn" data-modal="modalD">Ver detalhes</button>
    </article>
</section>

<!-- MODAL A -->
<div class="modal" id="modalA">
    <div class="modal-content">
        <button class="close-btn" data-close>&times;</button>
        <h2>Montar Treino A</h2>
        <form class="formTreino" data-tipo="A">
            <input type="hidden" name="nome" value="Treino A">
            <input type="hidden" name="descricao" value="Glúteos + Posteriores + Core">

            <div class="exercicio">
                <input list="listaExerciciosA" name="exercicio[]" placeholder="Exercício" required>
                <input type="number" name="series[]" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" placeholder="Repetições" required>
                <input type="text" name="peso[]" placeholder="Peso (kg ou livre)" required>
            </div>

            <button type="submit" class="btn-iniciar-treino">Iniciar Treino</button>
        </form>

        <datalist id="listaExerciciosA">
            <option value="Mesa Flexora">
            <option value="Elevação de Pelve">
            <option value="V Squat">
            <option value="Cadeira Abdutora">
            <option value="Glúteo 4 Apoio">
            <option value="Dead Bug">
            <option value="Prancha">
        </datalist>
    </div>
</div>

<!-- MODAL B -->
<div class="modal" id="modalB">
    <div class="modal-content">
        <button class="close-btn" data-close>&times;</button>
        <h2>Montar Treino B</h2>
        <form class="formTreino" data-tipo="B">
            <input type="hidden" name="nome" value="Treino B">
            <input type="hidden" name="descricao" value="Membros Superiores + Core">

            <div class="exercicio">
                <input list="listaExerciciosB" name="exercicio[]" placeholder="Exercício" required>
                <input type="number" name="series[]" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" placeholder="Repetições" required>
                <input type="text" name="peso[]" placeholder="Peso (kg ou livre)" required>
            </div>

            <button type="submit" class="btn-iniciar-treino">Iniciar Treino</button>
        </form>

        <datalist id="listaExerciciosB">
            <option value="Supino Reto">
            <option value="Puxada Frontal">
            <option value="Remada Curvada">
            <option value="Tríceps Corda">
            <option value="Rosca Direta">
            <option value="Prancha">
            <option value="Abdominal Infra">
        </datalist>
    </div>
</div>

<!-- MODAL C -->
<div class="modal" id="modalC">
    <div class="modal-content">
        <button class="close-btn" data-close>&times;</button>
        <h2>Montar Treino C</h2>
        <form class="formTreino" data-tipo="C">
            <input type="hidden" name="nome" value="Treino C">
            <input type="hidden" name="descricao" value="Quadríceps + Glúteos + Core">

            <div class="exercicio">
                <input list="listaExerciciosC" name="exercicio[]" placeholder="Exercício" required>
                <input type="number" name="series[]" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" placeholder="Repetições" required>
                <input type="text" name="peso[]" placeholder="Peso (kg ou livre)" required>
            </div>

            <button type="submit" class="btn-iniciar-treino">Iniciar Treino</button>
        </form>

        <datalist id="listaExerciciosC">
            <option value="Agachamento Livre">
            <option value="Cadeira Extensora">
            <option value="Passada com Halteres">
            <option value="Stiff">
            <option value="Cadeira Abdutora">
            <option value="Prancha Lateral">
            <option value="Abdominal Supra">
        </datalist>
    </div>
</div>

<!-- MODAL D -->
<div class="modal" id="modalD">
    <div class="modal-content">
        <button class="close-btn" data-close>&times;</button>
        <h2>Montar Treino D</h2>
        <form class="formTreino" data-tipo="D">
            <input type="hidden" name="nome" value="Treino D">
            <input type="hidden" name="descricao" value="Superiores Posturais + Core">

            <div class="exercicio">
                <input list="listaExerciciosD" name="exercicio[]" placeholder="Exercício" required>
                <input type="number" name="series[]" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" placeholder="Repetições" required>
                <input type="text" name="peso[]" placeholder="Peso (kg ou livre)" required>
            </div>

            <button type="submit" class="btn-iniciar-treino">Iniciar Treino</button>
        </form>

        <datalist id="listaExerciciosD">
            <option value="Remada Baixa">
            <option value="Desenvolvimento com Halteres">
            <option value="Crucifixo Inverso">
            <option value="Face Pull">
            <option value="Elevação Lateral">
            <option value="Prancha com Rotação">
            <option value="Abdominal Prancha">
        </datalist>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Abrir modal
        document.querySelectorAll(".btn[data-modal]").forEach(btn => {
            btn.addEventListener("click", function () {
                const modal = document.getElementById(this.dataset.modal);
                modal.classList.add("open");
            });
        });

        // Fechar modal
        document.querySelectorAll(".close-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                this.closest(".modal").classList.remove("open");
            });
        });

        // Iniciar treino
        document.querySelectorAll(".formTreino").forEach(form => {
            form.addEventListener("submit", async function (e) {
                e.preventDefault();

                const formData = new FormData(form);
                const nome = formData.get('nome') || 'Treino Personalizado';
                const descricao = formData.get('descricao') || '';

                const nomes = formData.getAll('exercicio[]');
                const series = formData.getAll('series[]');
                const repeticoes = formData.getAll('repeticoes[]');
                const pesos = formData.getAll('peso[]');

                const exercicios = nomes.map((n, i) => ({
                    nome: n.trim(),
                    series: parseInt(series[i]) || 0,
                    repeticoes: repeticoes[i] || '',
                    carga: parseFloat(pesos[i].replace(',', '.')) || 0
                })).filter(ex => ex.nome !== '');

                if (exercicios.length === 0) {
                    alert("⚠️ Adicione pelo menos um exercício!");
                    return;
                }

                try {
                    const response = await fetch("/ACADEMY/public/treinos/iniciar", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ nome, descricao, exercicios })
                    });

                    const data = await response.json();

                    if (data.status === "sucesso") {
                        alert(data.mensagem);
                        window.location.href = data.redirect;
                    } else {
                        alert("❌ " + data.mensagem);
                    }
                } catch (err) {
                    console.error(err);
                    alert("❌ Erro ao conectar com o servidor.");
                }
            });
        });
    });
</script>