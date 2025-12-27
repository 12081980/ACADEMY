<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>
<?php if (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
    </div>
<?php endif; ?>

<div class="container">
    <h2>✏ Editar Avaliação — <?= htmlspecialchars($avaliacao['nome_usuario']) ?></h2>
    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($avaliacao['data_avaliacao'])) ?></p>

    <form action="/ACADEMY/public/instrutor/avaliacaoEditar/<?= $avaliacao['id'] ?>" method="POST" class="form-grid">

        <h3>📏 Medidas Corporais</h3>

        <label>Estatura:</label>
        <input type="number" step="0.01" name="estatura" value="<?= $avaliacao['estatura'] ?>">

        <label>Peso:</label>
        <input type="number" step="0.01" name="peso" value="<?= $avaliacao['peso'] ?>">

        <label>IMC:</label>
        <input type="number" step="0.01" name="imc" value="<?= $avaliacao['imc'] ?>">

        <hr>

        <h3>📌 Dobras Cutâneas / Circunferências</h3>

        <?php
        $campos = [
            "subescapular",
            "triceps",
            "axilar_media",
            "toracica",
            "supra_iliaca",           
            "coxa",
            "percentual_gordura",
            "massa_magra",
            "massa_gorda",
            "torax",
            "cintura",
            "abdomen_med",
            "quadril",
            "coxa_direita",
            "coxa_esquerda",
            "perna_direita",
            "perna_esquerda",
            "braco_direito",
            "braco_esquerdo",
            "antebraco_direito",
            "antebraco_esquerdo",
            "rcq"
        ];

        foreach ($campos as $campo): ?>
    <label><?= ucwords(str_replace("_", " ", $campo)) ?>:</label>
    <input type="number" step="0.01"
           name="<?= $campo ?>"
           value="<?= htmlspecialchars($avaliacao[$campo] ?? '') ?>">
<?php endforeach; ?>


        <hr>

        <h3>🔥 Dados Metabólicos</h3>

        <label>Nível de Atividade:</label>
        <input type="text" name="nivel_atividade" value="<?= $avaliacao['nivel_atividade'] ?>">

        <label>TMB:</label>
        <input type="number" step="0.01" name="tmb" value="<?= $avaliacao['tmb'] ?>">

        <label>Necessidade Energética:</label>
        <input type="number" step="0.01" name="necessidade_energetica"
            value="<?= $avaliacao['necessidade_energetica'] ?>">

        <hr>

        <h3>⚠ Histórico e Saúde</h3>

        <label>Cirurgia:</label>
        <textarea name="cirurgia"><?= $avaliacao['cirurgia'] ?></textarea>

        <label>Patologia:</label>
        <textarea name="patologia"><?= $avaliacao['patologia'] ?></textarea>

        <label>Medicamento:</label>
        <textarea name="medicamento"><?= $avaliacao['medicamento'] ?></textarea>

        <label>Fatores de Risco:</label>
        <textarea name="fatores_risco"><?= $avaliacao['fatores_risco'] ?></textarea>

        <hr>

        <h3>🏃 Atividade e Rotina</h3>

        <label>Atividade Atual:</label>
        <textarea name="atividade_atual"><?= $avaliacao['atividade_atual'] ?></textarea>

        <label>Rotina:</label>
        <textarea name="rotina"><?= $avaliacao['rotina'] ?></textarea>

        <label>Objetivo:</label>
        <textarea name="objetivo"><?= $avaliacao['objetivo'] ?></textarea>

        <label>Observações:</label>
        <textarea name="observacoes"><?= $avaliacao['observacoes'] ?></textarea>

        <br>

        <button type="submit" class="btn btn-primary">💾 Salvar Alterações</button>
        <a href="/ACADEMY/public/instrutor/avaliacaoVer/<?= $avaliacao['id'] ?>" class="btn btn-secondary">⬅ Voltar</a>

    </form>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
<style>
    .container {
        max-width: 900px;
        margin: auto;
        padding: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    textarea {
        grid-column: span 2;
        height: 80px;
    }

    h3 {
        grid-column: span 2;
        margin-top: 20px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 5px;
    }

    button,
    a.btn {
        margin-top: 20px;
    }
</style>