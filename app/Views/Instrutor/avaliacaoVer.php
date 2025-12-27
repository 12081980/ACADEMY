<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>

<div class="container">   

    <?php if (!$avaliacao): ?>
        <p style="color:red;">Avaliação não encontrada!</p>
    </div>
    <?php return; ?>
<?php endif; ?>

<!-- Nome do usuário -->
<p><strong>Aluno:</strong>
    <?= htmlspecialchars($avaliacao['nome_usuario'] ?? $avaliacao['nome'] ?? 'Não informado') ?>
</p>

<!-- Data -->
<p><strong>Data da Avaliação:</strong>
    <?= isset($avaliacao['data_avaliacao']) ? date("d/m/Y", strtotime($avaliacao['data_avaliacao'])) : '—' ?>
</p>

<hr>

<h3>📝 Informações da Avaliação</h3>

<table class="table" style="width:100%; border-collapse: collapse;">
    <tbody>

        <?php
        // Lista de campos que NÃO devem ser exibidos
        $ocultar = [
            'id',
            'id_usuario',
            'usuario_id',
            'id_instrutor',
            'instrutor_id',
            'nome',
            'nome_usuario',
            'data_avaliacao',
            'created_at',
            'avaliador_id'
        ];

        foreach ($avaliacao as $campo => $valor):

            // Pula valores nulos ou vazios
            if ($valor === null || $valor === "" || in_array($campo, $ocultar)) {
                continue;
            }

            // Nome formatado
            $label = ucfirst(str_replace("_", " ", $campo));

            // Valor formatado
            $exibir = nl2br(htmlspecialchars((string) $valor));
            ?>

            <tr>
                <td style="padding: 8px; border:1px solid #ccc; width:230px;">
                    <strong><?= $label ?></strong>
                </td>
                <td style="padding: 8px; border:1px solid #ccc;">
                    <?= $exibir ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </tbody>
</table>

<hr>

<a href="/ACADEMY/public/instrutor/avaliacaoEditar/<?= $avaliacao['id'] ?>" class="btn btn-warning">✏ Editar</a>

<a href="/ACADEMY/public/instrutor/avaliacaoExcluir/<?= $avaliacao['id'] ?>" class="btn btn-danger"
    onclick="return confirm('Tem certeza que deseja excluir esta avaliação?')">🗑 Excluir</a>

<a href="/ACADEMY/public/instrutor/avaliacaoPdf/<?= $avaliacao['id'] ?>" class="btn btn-secondary">📄 Gerar PDF</a>



</div>