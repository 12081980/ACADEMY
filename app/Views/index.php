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
                <input list="listaExerciciosA" name="exercicio[]" class="inp-exercicio" placeholder="Exercício"
                    required>
                <input type="number" name="series[]" class="inp-series" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" class="inp-repeticoes" placeholder="Repetições" required>
                <input type="text" name="peso[]" class="inp-peso" placeholder="Peso (kg ou livre)" required>
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
                <input list="listaExerciciosB" name="exercicio[]" class="inp-exercicio" placeholder="Exercício"
                    required>
                <input type="number" name="series[]" class="inp-series" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" class="inp-repeticoes" placeholder="Repetições" required>
                <input type="text" name="peso[]" class="inp-peso" placeholder="Peso (kg ou livre)" required>
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
                <input list="listaExerciciosC" name="exercicio[]" class="inp-exercicio" placeholder="Exercício"
                    required>
                <input type="number" name="series[]" class="inp-series" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" class="inp-repeticoes" placeholder="Repetições" required>
                <input type="text" name="peso[]" class="inp-peso" placeholder="Peso (kg ou livre)" required>
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
                <input list="listaExerciciosD" name="exercicio[]" class="inp-exercicio" placeholder="Exercício"
                    required>
                <input type="number" name="series[]" class="inp-series" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" class="inp-repeticoes" placeholder="Repetições" required>
                <input type="text" name="peso[]" class="inp-peso" placeholder="Peso (kg ou livre)" required>
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
    const usuario_id = <?= json_encode($_SESSION['usuario']['id'] ?? null) ?>;
</script>

<script>
document.querySelectorAll(".formTreino").forEach(form => {

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const nome = this.querySelector("input[name='nome']").value;
        const descricao = this.querySelector("input[name='descricao']").value;

        const dados = new FormData();
        dados.append("nome", nome);
       dados.append("tipo", this.dataset.tipo);

        dados.append("descricao", descricao);
        dados.append("usuario_id", usuario_id); // 👈 ESSENCIAL

        const exercicios = this.querySelectorAll(".exercicio");

        exercicios.forEach((div, index) => {
            dados.append(`exercicios[${index}][nome]`, div.querySelector(".inp-exercicio").value);
            dados.append(`exercicios[${index}][series]`, div.querySelector(".inp-series").value);
            dados.append(`exercicios[${index}][repeticoes]`, div.querySelector(".inp-repeticoes").value);
            dados.append(`exercicios[${index}][peso]`, div.querySelector(".inp-peso").value);
        });

        try {
            const response = await fetch("/ACADEMY/public/treinos/iniciar", {
                method: "POST",
                body: dados
            });

            const resultado = await response.json();

          if (resultado.status === "sucesso") {
    window.location.href = resultado.redirect;
} 
else if (resultado.status === "bloqueado") {
    alert("⚠ " + resultado.mensagem);
    window.location.href = resultado.redirect;
} 
else {
    alert("❌ " + resultado.mensagem);
}


        } catch (error) {
            alert("⚠ Erro ao conectar com o servidor.");
        }
    });

});

</script>