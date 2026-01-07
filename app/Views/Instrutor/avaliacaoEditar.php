<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>

<div class="container">

<?php if (!$avaliacao): ?>
    <p style="color:red;">Avaliação não encontrada!</p>
    <?php return; ?>
<?php endif; ?>

<h2>Avaliação Física — <?= htmlspecialchars($avaliacao['nome_usuario'] ?? 'Aluno') ?></h2>
<p><strong>Data:</strong> <?= date('d/m/Y', strtotime($avaliacao['data_avaliacao'])) ?></p>

<form method="POST"
      action="/ACADEMY/public/instrutor/avaliacaoEditar/<?= $avaliacao['id'] ?>">

<!-- ================= MEDIDAS / CIRCUNFERÊNCIAS / METABOLISMO ================= -->
<div class="tabela-avaliacao">

    <!-- COLUNA 1 -->
    <div class="coluna-avaliacao">
        <h4>Medidas Corporais</h4>

        <label>Estatura</label>
        <input type="number" step="0.01" name="estatura" value="<?= $avaliacao['estatura'] ?? '' ?>">

        <label>Peso</label>
        <input type="number" step="0.01" name="peso" value="<?= $avaliacao['peso'] ?? '' ?>">

        <label>IMC</label>
        <input type="number" step="0.01" name="imc" value="<?= $avaliacao['imc'] ?? '' ?>">

        <label>% Gordura</label>
        <input type="number" step="0.01" name="percentual_gordura" value="<?= $avaliacao['percentual_gordura'] ?? '' ?>">

        <label>Massa Magra</label>
        <input type="number" step="0.01" name="massa_magra" value="<?= $avaliacao['massa_magra'] ?? '' ?>">

        <label>Massa Gorda</label>
        <input type="number" step="0.01" name="massa_gorda" value="<?= $avaliacao['massa_gorda'] ?? '' ?>">
    </div>

    <!-- COLUNA 2 -->
    <div class="coluna-avaliacao">
        <h4>Circunferências Corporais</h4>

        <label>Tórax</label>
        <input type="number" step="0.01" name="torax" value="<?= $avaliacao['torax'] ?? '' ?>">

        <label>Cintura</label>
        <input type="number" step="0.01" name="cintura" value="<?= $avaliacao['cintura'] ?? '' ?>">

        <label>Abdômen</label>
        <input type="number" step="0.01" name="abdomen_med" value="<?= $avaliacao['abdomen_med'] ?? '' ?>">

        <label>Quadril</label>
        <input type="number" step="0.01" name="quadril" value="<?= $avaliacao['quadril'] ?? '' ?>">
    </div>

    <!-- COLUNA 3 -->
    <div class="coluna-avaliacao">
        <h4>Metabolismo / Saúde</h4>

        <label>Nível de Atividade</label>
        <input type="text" name="nivel_atividade" value="<?= $avaliacao['nivel_atividade'] ?? '' ?>">

        <label>TMB</label>
        <input type="number" step="0.01" name="tmb" value="<?= $avaliacao['tmb'] ?? '' ?>">

        <label>Necessidade Energética</label>
        <input type="number" step="0.01" name="necessidade_energetica"
               value="<?= $avaliacao['necessidade_energetica'] ?? '' ?>">

        <label>RCQ</label>
        <input type="number" step="0.01" name="rcq" value="<?= $avaliacao['rcq'] ?? '' ?>">
    </div>

</div>

<!-- ================= MEMBROS ================= -->
<div class="tabela-avaliacao tabela-2-colunas">

    <div class="coluna-avaliacao">
        <h4>Membros</h4>

        <label>Coxa Direita</label>
        <input type="number" step="0.01" name="coxa_direita" value="<?= $avaliacao['coxa_direita'] ?? '' ?>">

        <label>Perna Direita</label>
        <input type="number" step="0.01" name="perna_direita" value="<?= $avaliacao['perna_direita'] ?? '' ?>">

        <label>Braço Direito</label>
        <input type="number" step="0.01" name="braco_direito" value="<?= $avaliacao['braco_direito'] ?? '' ?>">

        <label>Antebraço Direito</label>
        <input type="number" step="0.01" name="antebraco_direito" value="<?= $avaliacao['antebraco_direito'] ?? '' ?>">
    </div>

    <div class="coluna-avaliacao">
        <h4>&nbsp;</h4>

        <label>Coxa Esquerda</label>
        <input type="number" step="0.01" name="coxa_esquerda" value="<?= $avaliacao['coxa_esquerda'] ?? '' ?>">

        <label>Perna Esquerda</label>
        <input type="number" step="0.01" name="perna_esquerda" value="<?= $avaliacao['perna_esquerda'] ?? '' ?>">

        <label>Braço Esquerdo</label>
        <input type="number" step="0.01" name="braco_esquerdo" value="<?= $avaliacao['braco_esquerdo'] ?? '' ?>">

        <label>Antebraço Esquerdo</label>
        <input type="number" step="0.01" name="antebraco_esquerdo" value="<?= $avaliacao['antebraco_esquerdo'] ?? '' ?>">
    </div>

</div>

<!-- ================= HISTÓRICO / OBSERVAÇÕES ================= -->
<div class="tabela-avaliacao">

    <div class="coluna-avaliacao">
        <h4>Histórico</h4>

        <label>Cirurgia</label>
        <textarea name="cirurgia"><?= $avaliacao['cirurgia'] ?? '' ?></textarea>

        <label>Patologia</label>
        <textarea name="patologia"><?= $avaliacao['patologia'] ?? '' ?></textarea>

        <label>Medicamentos</label>
        <textarea name="medicamento"><?= $avaliacao['medicamento'] ?? '' ?></textarea>
    </div>

    <div class="coluna-avaliacao">
        <h4>&nbsp;</h4>

        <label>Fatores de Risco</label>
        <textarea name="fatores_risco"><?= $avaliacao['fatores_risco'] ?? '' ?></textarea>

        <label>Atividade Atual</label>
        <textarea name="atividade_atual"><?= $avaliacao['atividade_atual'] ?? '' ?></textarea>
    </div>

    <div class="coluna-avaliacao">
        <h4>&nbsp;</h4>

        <label>Rotina</label>
        <textarea name="rotina"><?= $avaliacao['rotina'] ?? '' ?></textarea>

        <label>Objetivo</label>
        <textarea name="objetivo"><?= $avaliacao['objetivo'] ?? '' ?></textarea>

        <label>Observações</label>
        <textarea name="observacoes"><?= $avaliacao['observacoes'] ?? '' ?></textarea>
    </div>

</div>

<!-- ================= BOTÕES ================= -->
<div class="acoes-avaliacao">
    <button type="submit" class="btn btn-primary">Salvar</button>

    <a href="/ACADEMY/public/instrutor/avaliacaoVer/<?= $avaliacao['id'] ?>"
       class="btn btn-secondary">Voltar</a>
</div>

</form>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
