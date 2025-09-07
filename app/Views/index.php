<!-- MODAL LOGIN -->
<div id="modalLogin" class="modal">
    <div class="modal-content">
        <span class="fechar" onclick="fecharModal('modalLogin')">&times;</span>
        <h2>Login</h2>
        <form id="formLogin">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</div>

<!-- MODAL CADASTRO -->
<div id="modalCadastro" class="modal">
    <div class="modal-content">
        <span class="fechar" onclick="fecharModal('modalCadastro')">&times;</span>
        <h2>Cadastrar Novo Usuário</h2>
        <form id="formCadastro">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</div>


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
<!-- Modal A -->
<div class="modal" id="modalA">
    <div class="modal-content">
        <button class="close-btn" data-close>&times;</button>
        <h2>Montar Treino A</h2>
        <form id="formTreinoA">
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


<!-- Modal B -->
<div class="modal" id="modalB">
    <div class="modal-content">
        <button class="close-btn" data-close>&times;</button>
        <h2>Montar Treino B</h2>
        <form id="formTreinoB">
            <div class="exercicio">
                <input list="listaExerciciosB" name="exercicio[]" placeholder="Exercício" required>
                <input type="number" name="series[]" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" placeholder="Repetições" required>
                <input type="text" name="peso[]" placeholder="Peso (kg ou livre)" required>
            </div>


            <button type="submit" class="btn-iniciar-treino">Iniciar Treino</button>
        </form>
        <datalist id="listaExerciciosB">
            <option value="Remada Curvada Banco">
            <option value="Remada Convergente Peg Neutra">
            <option value="Puxada Triângulo">
            <option value="Desenvolvimento Máquina">
            <option value="Tríceps Pulley">
            <option value="Rosca Direta Halter">
            <option value="Prancha Lateral">
            <option value="Bird Dog">
        </datalist>
    </div>
</div>

<!-- Modal C -->
<div class="modal" id="modalC">
    <div class="modal-content">
        <button class="close-btn" data-close>&times;</button>
        <h2>Montar Treino C</h2>
        <form id="formTreinoC">
            <div class="exercicio">
                <input list="listaExerciciosC" name="exercicio[]" placeholder="Exercício" required>
                <input type="number" name="series[]" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" placeholder="Repetições" required>
                <input type="text" name="peso[]" placeholder="Peso (kg ou livre)" required>
            </div>


            <button type="submit" class="btn-iniciar-treino">Iniciar Treino</button>
        </form>
        <datalist id="listaExerciciosC">
            <option value="Leg Press 180°">
            <option value="Cadeira Extensora">
            <option value="Cadeira Abdutora">
            <option value="Elevação de Quadril Unilateral">
            <option value="Afundo Step Atrás">
        </datalist>
    </div>
</div>


<!-- Modal D -->
<div class="modal" id="modalD">
    <div class="modal-content">
        <button class="close-btn" data-close>&times;</button>
        <h2>Montar Treino D</h2>
        <form id="formTreinoD">
            <div class="exercicio">
                <input list="listaExerciciosD" name="exercicio[]" placeholder="Exercício" required>
                <input type="number" name="series[]" placeholder="Séries" min="1" required>
                <input type="text" name="repeticoes[]" placeholder="Repetições" required>
                <input type="text" name="peso[]" placeholder="Peso (kg ou livre)" required>
            </div>


            <button type="submit" class="btn-iniciar-treino">Iniciar Treino</button>
        </form>
        <datalist id="listaExerciciosD">
            <option value="Remada Unilateral Halter">
            <option value="Face Pull">
            <option value="Crucifixo Invertido Peck Deck">
            <option value="Superman">
            <option value="Prancha">
            <option value="Alongamento Lombar e Torácico">
        </datalist>
    </div>
</div>

<script>

</script>
<script>

</script>
<script>

</script>

</section>
</body>

</html>