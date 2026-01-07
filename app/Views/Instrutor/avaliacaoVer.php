<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>

<div class="container">

<?php if (!$avaliacao): ?>
    <p style="color:red;">Avaliação não encontrada!</p>
    <?php return; ?>
<?php endif; ?>

<h2>Avaliação Física — <?= htmlspecialchars($avaliacao['nome_usuario'] ?? 'Aluno') ?></h2>
<p><strong>Data:</strong> <?= date('d/m/Y', strtotime($avaliacao['data_avaliacao'])) ?></p>

<!-- ================= MEDIDAS / COMPOSIÇÃO / METABOLISMO ================= -->
<div class="tabela-avaliacao">

    <div class="coluna-avaliacao">
        <h4>Medidas Corporais</h4>
        <div class="linha"><strong>Peso:</strong><span><?= $avaliacao['peso'] ?? '-' ?></span></div>
        <div class="linha"><strong>Estatura:</strong><span><?= $avaliacao['estatura'] ?? '-' ?></span></div>
        <div class="linha"><strong>IMC:</strong><span><?= $avaliacao['imc'] ?? '-' ?></span></div>
        <div class="linha"><strong>% Gordura:</strong><span><?= $avaliacao['percentual_gordura'] ?? '-' ?></span></div>
        <div class="linha"><strong>Massa Magra:</strong><span><?= $avaliacao['massa_magra'] ?? '-' ?></span></div>
        <div class="linha"><strong>Massa Gorda:</strong><span><?= $avaliacao['massa_gorda'] ?? '-' ?></span></div>
    </div>

    <div class="coluna-avaliacao">
        <h4>Dobras Cutâneas</h4>
        <div class="linha"><strong>Subescapular:</strong><span><?= $avaliacao['subescapular'] ?? '-' ?></span></div>
        <div class="linha"><strong>Tríceps:</strong><span><?= $avaliacao['triceps'] ?? '-' ?></span></div>
        <div class="linha"><strong>Axilar Média:</strong><span><?= $avaliacao['axilar_media'] ?? '-' ?></span></div>
        <div class="linha"><strong>Torácica:</strong><span><?= $avaliacao['toracica'] ?? '-' ?></span></div>
        <div class="linha"><strong>Supra-ilíaca:</strong><span><?= $avaliacao['supra_iliaca'] ?? '-' ?></span></div>
        <div class="linha"><strong>Coxa:</strong><span><?= $avaliacao['coxa'] ?? '-' ?></span></div>
    </div>

    <div class="coluna-avaliacao">
        <h4>Metabolismo / Saúde</h4>
        <div class="linha"><strong>Nível Atividade:</strong><span><?= $avaliacao['nivel_atividade'] ?? '-' ?></span></div>
        <div class="linha"><strong>TMB:</strong><span><?= $avaliacao['tmb'] ?? '-' ?></span></div>
        <div class="linha"><strong>Nec. Energética:</strong><span><?= $avaliacao['necessidade_energetica'] ?? '-' ?></span></div>
        <div class="linha"><strong>RCQ:</strong><span><?= $avaliacao['rcq'] ?? '-' ?></span></div>
    </div>

</div>

<!-- ================= PERÍMETROS / MEMBROS ================= -->
<div class="tabela-avaliacao tabela-2-colunas">

    <div class="coluna-avaliacao">
        <h4>Perímetros</h4>
        <div class="linha"><strong>Tórax:</strong><span><?= $avaliacao['torax'] ?? '-' ?></span></div>
        <div class="linha"><strong>Cintura:</strong><span><?= $avaliacao['cintura'] ?? '-' ?></span></div>
        <div class="linha"><strong>Abdômen:</strong><span><?= $avaliacao['abdomen'] ?? '-' ?></span></div>
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
        <div class="linha"><strong>Antebraço Dir.:</strong><span><?= $avaliacao['antebraco_direito'] ?? '-' ?></span></div>
        <div class="linha"><strong>Antebraço Esq.:</strong><span><?= $avaliacao['antebraco_esquerdo'] ?? '-' ?></span></div>
    </div>

</div>

<!-- ================= HISTÓRICO / OBJETIVOS ================= -->
<div class="bloco-texto">
    <h4>Histórico / Informações Complementares</h4>

    <p><strong>Cirurgia:</strong><br><?= nl2br(htmlspecialchars($avaliacao['cirurgia'] ?? '')) ?></p>
    <p><strong>Patologia:</strong><br><?= nl2br(htmlspecialchars($avaliacao['patologia'] ?? '')) ?></p>
    <p><strong>Medicamentos:</strong><br><?= nl2br(htmlspecialchars($avaliacao['medicamento'] ?? '')) ?></p>
    <p><strong>Fatores de Risco:</strong><br><?= nl2br(htmlspecialchars($avaliacao['fatores_risco'] ?? '')) ?></p>
    <p><strong>Atividade Atual:</strong><br><?= nl2br(htmlspecialchars($avaliacao['atividade_atual'] ?? '')) ?></p>
    <p><strong>Rotina:</strong><br><?= nl2br(htmlspecialchars($avaliacao['rotina'] ?? '')) ?></p>
    <p><strong>Objetivo:</strong><br><?= nl2br(htmlspecialchars($avaliacao['objetivo'] ?? '')) ?></p>
    <p><strong>Observações:</strong><br><?= nl2br(htmlspecialchars($avaliacao['observacoes'] ?? '')) ?></p>
</div>

<!-- ================= BOTÕES ================= -->
<div class="acoes-avaliacao">
    <a href="/ACADEMY/public/instrutor/avaliacaoEditar/<?= $avaliacao['id'] ?>" class="btn btn-warning">Editar</a>

    <a href="/ACADEMY/public/instrutor/avaliacaoExcluir/<?= $avaliacao['id'] ?>"
       class="btn btn-danger"
       onclick="return confirm('Deseja excluir esta avaliação?')">Excluir</a>

    <a href="/ACADEMY/public/instrutor/avaliacaoPdf/<?= $avaliacao['id'] ?>"
       class="btn btn-secondary">PDF</a>
</div>

</div>

<?php include __DIR__ . '/../templates/footerInstrutor.php'; ?>
