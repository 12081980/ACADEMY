<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <base href="/ACADEMY/">
    <title>Avaliação Física Realizada</title>
    <link rel="stylesheet" href="public/css/style.css">

    <style>
        .form-container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        fieldset {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
        }

        legend {
            font-weight: bold;
            color: #333;
        }

        .voltar {
            display: inline-block;
            margin-bottom: 15px;
            color: #007bff;
            text-decoration: none;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
        }

        textarea {
            resize: vertical;
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>

    <div class="form-container">
        <a href="/ACADEMY/public/instrutor/avaliacoesRealizadas" class="voltar">← Voltar</a>

        <h2>Avaliação Física Realizada</h2>

        <fieldset>
            <legend>Identificação</legend>
            <label>Nome do Usuário:</label>
            <input type="text" value="<?= htmlspecialchars($avaliacao['nome_usuario']) ?>" disabled>

            <label>Data da Avaliação:</label>
            <input type="text" value="<?= htmlspecialchars($avaliacao['data_avaliacao']) ?>" disabled>
        </fieldset>

        <fieldset>
            <legend>Medidas Básicas</legend>
            <label>Estatura:</label><input value="<?= $avaliacao['estatura'] ?>" disabled>
            <label>Peso:</label><input value="<?= $avaliacao['peso'] ?>" disabled>
            <label>IMC:</label><input value="<?= $avaliacao['imc'] ?>" disabled>
        </fieldset>

        <fieldset>
            <legend>Dobras Cutâneas</legend>
            <label>Subescapular:</label><input value="<?= $avaliacao['subescapular'] ?>" disabled>
            <label>Tríceps:</label><input value="<?= $avaliacao['triceps'] ?>" disabled>
            <label>Axilar Média:</label><input value="<?= $avaliacao['axilar_media'] ?>" disabled>
            <label>Torácica:</label><input value="<?= $avaliacao['toracica'] ?>" disabled>
            <label>Supra-ilíaca:</label><input value="<?= $avaliacao['supra_iliaca'] ?>" disabled>
            <label>Abdominal:</label><input value="<?= $avaliacao['abdominal'] ?>" disabled>
            <label>Coxa:</label><input value="<?= $avaliacao['coxa'] ?>" disabled>
        </fieldset>

        <fieldset>
            <legend>Composição Corporal</legend>
            <label>% Gordura:</label><input value="<?= $avaliacao['percentual_gordura'] ?>" disabled>
            <label>Massa Magra:</label><input value="<?= $avaliacao['massa_magra'] ?>" disabled>
            <label>Massa Gorda:</label><input value="<?= $avaliacao['massa_gorda'] ?>" disabled>
        </fieldset>

        <fieldset>
            <legend>Perímetros</legend>
            <label>Tórax:</label><input value="<?= $avaliacao['torax'] ?>" disabled>
            <label>Cintura:</label><input value="<?= $avaliacao['cintura'] ?>" disabled>
            <label>Abdômen:</label><input value="<?= $avaliacao['abdomen'] ?>" disabled>
            <label>Quadril:</label><input value="<?= $avaliacao['quadril'] ?>" disabled>
            <label>Coxa Direita:</label><input value="<?= $avaliacao['coxa_direita'] ?>" disabled>
            <label>Coxa Esquerda:</label><input value="<?= $avaliacao['coxa_esquerda'] ?>" disabled>
            <label>Perna Direita:</label><input value="<?= $avaliacao['perna_direita'] ?>" disabled>
            <label>Perna Esquerda:</label><input value="<?= $avaliacao['perna_esquerda'] ?>" disabled>
            <label>Braço Direito:</label><input value="<?= $avaliacao['braco_direito'] ?>" disabled>
            <label>Braço Esquerdo:</label><input value="<?= $avaliacao['braco_esquerdo'] ?>" disabled>
            <label>Antebraço Direito:</label><input value="<?= $avaliacao['antebraco_direito'] ?>" disabled>
            <label>Antebraço Esquerdo:</label><input value="<?= $avaliacao['antebraco_esquerdo'] ?>" disabled>
        </fieldset>

        <fieldset>
            <legend>Metabolismo e Hábitos</legend>
            <label>RCQ:</label><input value="<?= $avaliacao['rcq'] ?>" disabled>
            <label>Nível de Atividade:</label><input value="<?= $avaliacao['nivel_atividade'] ?>" disabled>
            <label>TMB:</label><input value="<?= $avaliacao['tmb'] ?>" disabled>
            <label>Necessidade Energética:</label><input value="<?= $avaliacao['necessidade_energetica'] ?>" disabled>
        </fieldset>

        <fieldset>
            <legend>Informações Complementares</legend>
            <label>Cirurgia:</label><textarea disabled><?= $avaliacao['cirurgia'] ?></textarea>
            <label>Patologia:</label><textarea disabled><?= $avaliacao['patologia'] ?></textarea>
            <label>Medicamento:</label><textarea disabled><?= $avaliacao['medicamento'] ?></textarea>
            <label>Fatores de Risco:</label><textarea disabled><?= $avaliacao['fatores_risco'] ?></textarea>
            <label>Atividade Atual:</label><textarea disabled><?= $avaliacao['atividade_atual'] ?></textarea>
            <label>Rotina:</label><textarea disabled><?= $avaliacao['rotina'] ?></textarea>
            <label>Objetivo:</label><textarea disabled><?= $avaliacao['objetivo'] ?></textarea>
            <label>Observações:</label><textarea disabled><?= $avaliacao['observacoes'] ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Avaliador</legend>
            <input type="text" value="<?= htmlspecialchars($avaliacao['avaliador']) ?>" disabled>
        </fieldset>

    </div>

</body>

</html>