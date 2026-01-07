<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container avaliacao-ver">

<?php if (!$avaliacao): ?>
    <p style="color:red;">Avaliação não encontrada!</p>
    <?php return; ?>
<?php endif; ?>

<h2>📊 Avaliação Física</h2>

<p>
    <strong>Data da avaliação:</strong>
    <?= date('d/m/Y H:i', strtotime($avaliacao['data_avaliacao'])) ?>
</p>

<!-- ================= MEDIDAS / COMPOSIÇÃO ================= -->
<div class="tabela-avaliacao">

    <div class="coluna-avaliacao">
        <h4>Medidas Corporais</h4>
        <div class="linha"><strong>Estatura:</strong><span><?= $avaliacao['estatura'] ?? '-' ?> cm</span></div>
        <div class="linha"><strong>Peso:</strong><span><?= $avaliacao['peso'] ?? '-' ?> kg</span></div>
        <div class="linha"><strong>IMC:</strong><span><?= $avaliacao['imc'] ?? '-' ?></span></div>
        <div class="linha"><strong>% Gordura:</strong><span><?= $avaliacao['percentual_gordura'] ?? '-' ?>%</span></div>
        <div class="linha"><strong>Massa Magra:</strong><span><?= $avaliacao['massa_magra'] ?? '-' ?> kg</span></div>
        <div class="linha"><strong>Massa Gorda:</strong><span><?= $avaliacao['massa_gorda'] ?? '-' ?> kg</span></div>
    </div>

    <div class="coluna-avaliacao">
        <h4>Dobras Cutâneas</h4>
        <div class="linha"><strong>Tríceps:</strong><span><?= $avaliacao['triceps'] ?? '-' ?></span></div>
        <div class="linha"><strong>Subescapular:</strong><span><?= $avaliacao['subescapular'] ?? '-' ?></span></div>
        <div class="linha"><strong>Axilar Média:</strong><span><?= $avaliacao['axilar_media'] ?? '-' ?></span></div>
        <div class="linha"><strong>Torácica:</strong><span><?= $avaliacao['toracica'] ?? '-' ?></span></div>
        <div class="linha"><strong>Supra-ilíaca:</strong><span><?= $avaliacao['supra_iliaca'] ?? '-' ?></span></div>
        <div class="linha"><strong>Coxa:</strong><span><?= $avaliacao['coxa'] ?? '-' ?></span></div>
    </div>

    <div class="coluna-avaliacao">
        <h4>Metabolismo / Saúde</h4>
        <div class="linha"><strong>Nível de Atividade:</strong><span><?= $avaliacao['nivel_atividade'] ?? '-' ?></span></div>
        <div class="linha"><strong>TMB:</strong><span><?= $avaliacao['tmb'] ?? '-' ?></span></div>
        <div class="linha"><strong>Nec. Energética:</strong><span><?= $avaliacao['necessidade_energetica'] ?? '-' ?></span></div>
        <div class="linha"><strong>RCQ:</strong><span><?= $avaliacao['rcq'] ?? '-' ?></span></div>
    </div>

</div>

<!-- ================= PERÍMETROS / MEMBROS ================= -->
<div class="tabela-avaliacao tabela-2-colunas">

    <div class="coluna-avaliacao">
        <h4>Circunferências</h4>
        <div class="linha"><strong>Tórax:</strong><span><?= $avaliacao['torax'] ?? '-' ?></span></div>
        <div class="linha"><strong>Cintura:</strong><span><?= $avaliacao['cintura'] ?? '-' ?></span></div>
        <div class="linha"><strong>Quadril:</strong><span><?= $avaliacao['quadril'] ?? '-' ?></span></div>
    </div>

    <div class="coluna-avaliacao">
        <h4>Membros</h4>
        <div class="linha"><strong>Coxa Dir.:</strong><span><?= $avaliacao['coxa_direita'] ?? '-' ?></span></div>
        <div class="linha"><strong>Coxa Esq.:</strong><span><?= $avaliacao['coxa_esquerda'] ?? '-' ?></span></div>
        <div class="linha"><strong>Perna Dir.:</strong><span><?= $avaliacao['perna_direita'] ?? '-' ?></span></div>
        <div class="linha"><strong>Perna Esq.:</strong><span><?= $avaliacao['perna_esquerda'] ?? '-' ?></span></div>
        <div class="linha"><strong>Braço Dir.:</strong><span><?= $avaliacao['braco_direito'] ?? '-' ?></span></div>
        <div class="linha"><strong>Braço Esq.:</strong><span><?= $avaliacao['braco_esquerdo'] ?? '-' ?></span></div>
    </div>

</div>

<!-- ================= OBSERVAÇÕES ================= -->
<div class="bloco-texto">
    <h4>📝 Observações do Instrutor</h4>

    <p>
        <?= nl2br(htmlspecialchars($avaliacao['observacoes'] ?? 'Nenhuma observação.')) ?>
    </p>
</div>

<!-- ================= VOLTAR ================= -->
<div class="acoes-avaliacao">
    <a href="/ACADEMY/public/" class="btn btn-secondary">
        ⬅
