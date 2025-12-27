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
    <title>Ficha de Avaliação Física</title>
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

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
        }

        .btn-enviar {
            display: block;
            width: 100%;
            background: #28a745;
            color: white;
            font-weight: bold;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-enviar:hover {
            background: #218838;
        }

        .voltar {
            display: inline-block;
            margin-bottom: 15px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>

    <div class="form-container">
        <a href="/ACADEMY/public/instrutor/avaliacaoEscolher" class="voltar"></a>
        <h2>Ficha de Avaliação Física</h2>

        <form id="formAvaliacao">
            <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($usuario['id']) ?>">

            <fieldset>
                <legend>Identificação</legend>
                <label>Nome do Aluno:</label>
                <input type="text" value="<?= htmlspecialchars($usuario['nome']) ?>" disabled>
                <label>Data da Avaliação:</label>
                <input type="date" name="data_avaliacao" required>
            </fieldset>

            <fieldset>
 <label>Peso (kg):</label>
<input type="text" name="peso" id="peso">

<label>Estatura (m):</label>
<input type="text" name="estatura" id="estatura">

<label>IMC (kg/m²):</label>
<input type="text" name="imc" id="imc" readonly>


            </fieldset>

            <fieldset>
                <legend>Dobras Cutâneas (mm)</legend>
                <label>Subescapular:</label><input type="text" name="subescapular">
                <label>Tríceps:</label><input type="text" name="triceps">
                <label>Axilar Média:</label><input type="text" name="axilar_media">
                <label>Torácica:</label><input type="text" name="toracica">
                <label>Supra-ilíaca:</label><input type="text" name="supra_iliaca">
                <label>Abdômen:</label>
<input type="text" name="abdomen_med">

                <label>Coxa:</label><input type="text" name="coxa">
            </fieldset>

            <fieldset>
                <legend>Composição Corporal</legend>
                <label>% de Gordura:</label><input type="text" name="percentual_gordura">
                <label>Massa Magra (kg):</label><input type="text" name="massa_magra">
                <label>Massa Gorda (kg):</label><input type="text" name="massa_gorda">
            </fieldset>

            <fieldset>
                <legend>Perímetros (cm)</legend>
                <label>Tórax:</label><input type="text" name="torax">
                <label>Cintura:</label><input type="text" name="cintura">
                <label>Abdômen:</label><input type="text" name="abdomen">
                <label>Quadril:</label><input type="text" name="quadril">
                <label>Coxa Direita:</label><input type="text" name="coxa_direita">
                <label>Coxa Esquerda:</label><input type="text" name="coxa_esquerda">
                <label>Perna Direita:</label><input type="text" name="perna_direita">
                <label>Perna Esquerda:</label><input type="text" name="perna_esquerda">
                <label>Braço Direito:</label><input type="text" name="braco_direito">
                <label>Braço Esquerdo:</label><input type="text" name="braco_esquerdo">
                <label>Antebraço Direito:</label><input type="text" name="antebraco_direito">
                <label>Antebraço Esquerdo:</label><input type="text" name="antebraco_esquerdo">
            </fieldset>

            <fieldset>
                <legend>Metabolismo e Hábitos</legend>
                <label>RCQ:</label><input type="text" name="rcq">
                <label>Nível de Atividade Física:</label>
                <select name="nivel_atividade">
                    <option value="">Selecione</option>
                   <option value="1">Sedentário</option>
<option value="2">Leve</option>
<option value="3">Moderado</option>
<option value="4">Intenso</option>

                </select>
                <label>Taxa de Metabolismo Basal (kcal):</label><input type="text" name="tmb">
                <label>Necessidade Energética Diária (kcal):</label><input type="text" name="necessidade_energetica">
            </fieldset>

            <fieldset>
                <legend>Informações Complementares</legend>
                <label>Você já realizou alguma cirurgia?</label><textarea name="cirurgia"></textarea>
                <label>Diagnóstico ortopédico ou dores recorrentes?</label><textarea name="patologia"></textarea>
                <label>Uso de medicamento ou suplemento?</label><textarea name="medicamento"></textarea>
                <label>Patologias ou fatores de risco à saúde:</label><textarea name="fatores_risco"></textarea>
                <label>Atividade física atual:</label><textarea name="atividade_atual"></textarea>
                <label>Rotina diária:</label><textarea name="rotina"></textarea>
                <label>Objetivo do acompanhamento:</label><textarea name="objetivo"></textarea>
                <label>Observações adicionais:</label><textarea name="observacoes"></textarea>
            </fieldset>

            <fieldset>
                <legend>Avaliador</legend>
                <input type="text" name="avaliador" value="<?= htmlspecialchars($_SESSION['usuario']['nome']) ?>"
                    readonly>
            </fieldset>


            <button type="submit" class="btn-enviar">Salvar Avaliação</button>
        </form>
    </div>
    <?php include __DIR__ . '/../templates/footer.php'; ?>
    <script>
        document.getElementById('formAvaliacao').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/ACADEMY/public/instrutor/salvarAvaliacao', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.mensagem);
                    if (data.status === 'sucesso') {
                        window.location.href = data.redirect;
                    }
                })
                .catch(err => console.error(err));
        });

    </script>
   <script>
document.addEventListener('DOMContentLoaded', function () {

    const pesoInput = document.getElementById('peso');
    const estaturaInput = document.getElementById('estatura');
    const imcInput = document.getElementById('imc');

    function calcularIMC() {
        let peso = parseFloat(pesoInput.value.replace(',', '.'));
        let estatura = parseFloat(estaturaInput.value.replace(',', '.'));

        if (isNaN(peso) || isNaN(estatura) || peso <= 0 || estatura <= 0) {
            imcInput.value = '';
            return;
        }

        // 👉 Se a estatura for maior que 3, considera cm e converte para metros
        if (estatura > 3) {
            estatura = estatura / 100;
        }

        const imc = peso / (estatura * estatura);
        imcInput.value = imc.toFixed(2);
    }

    pesoInput.addEventListener('input', calcularIMC);
    estaturaInput.addEventListener('input', calcularIMC);

});
</script>


</body>

</html>