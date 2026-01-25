  <!-- MODAL CARDIO -->
<div class="modal" id="modalCardio">
    <div class="modal-content">
        <button class="close-btn" data-close>&times;</button>
        <h2>Treino Cardio</h2>

   <form class="formTreino" data-tipo="CARDIO">
    <input type="hidden" name="descricao" value="Treino Cardio">

    <!-- SELECT -->
    <select id="tipoCardio" required>
        <option value="Caminhada">🚶 Caminhada</option>
        <option value="Corrida">🏃 Corrida</option>
    </select>

    <!-- TEMPO -->
    <select id="tempoCardio" required>
    <option value="">Tempo (min)</option>
    <?php for ($i = 5; $i <= 60; $i += 5): ?>
        <option value="<?= $i ?>"><?= $i ?> min</option>
    <?php endfor; ?>
</select>



    <!-- RITMO -->
<select id="ritmoCardio">
    <option value="">Ritmo (opcional)</option>
    <option value="Leve"> Leve</option>
    <option value="Moderado"> Moderado</option>
    <option value="Intenso"> Intenso</option>
</select>


    <button type="button" id="btnIniciarCardio" class="btn-iniciar-treino">
    Iniciar Cardio
</button>

</form>

    </div>
</div>
