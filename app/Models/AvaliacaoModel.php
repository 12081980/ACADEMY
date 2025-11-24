<?php
class AvaliacaoModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // inserir nova avaliação
    public function salvar($data)
    {
        $sql = "INSERT INTO avaliacoes_fisicas (
        usuario_id, instrutor_id, data_avaliacao, estatura, peso, imc,
        subescapular, triceps, axilar_media, toracica, supra_iliaca,
        abdominal, coxa, percentual_gordura, massa_magra, massa_gorda,
        torax, cintura, abdomen, quadril, coxa_direita, coxa_esquerda,
        perna_direita, perna_esquerda, braco_direito, braco_esquerdo,
        antebraco_direito, antebraco_esquerdo, rcq, nivel_atividade,
        tmb, necessidade_energetica, cirurgia, patologia, medicamento,
        fatores_risco, atividade_atual, rotina, objetivo, observacoes,
        avaliador
    ) VALUES (
        :usuario_id, :instrutor_id, :data_avaliacao, :estatura, :peso, :imc,
        :subescapular, :triceps, :axilar_media, :toracica, :supra_iliaca,
        :abdominal, :coxa, :percentual_gordura, :massa_magra, :massa_gorda,
        :torax, :cintura, :abdomen, :quadril, :coxa_direita, :coxa_esquerda,
        :perna_direita, :perna_esquerda, :braco_direito, :braco_esquerdo,
        :antebraco_direito, :antebraco_esquerdo, :rcq, :nivel_atividade,
        :tmb, :necessidade_energetica, :cirurgia, :patologia, :medicamento,
        :fatores_risco, :atividade_atual, :rotina, :objetivo, :observacoes,
        :avaliador
    )";

        $stmt = $this->conn->prepare($sql);

        // filtra SÓ os campos que o SQL usa
        $params = array_intersect_key($data, array_flip([
            'usuario_id',
            'instrutor_id',
            'data_avaliacao',
            'estatura',
            'peso',
            'imc',
            'subescapular',
            'triceps',
            'axilar_media',
            'toracica',
            'supra_iliaca',
            'abdominal',
            'coxa',
            'percentual_gordura',
            'massa_magra',
            'massa_gorda',
            'torax',
            'cintura',
            'abdomen',
            'quadril',
            'coxa_direita',
            'coxa_esquerda',
            'perna_direita',
            'perna_esquerda',
            'braco_direito',
            'braco_esquerdo',
            'antebraco_direito',
            'antebraco_esquerdo',
            'rcq',
            'nivel_atividade',
            'tmb',
            'necessidade_energetica',
            'cirurgia',
            'patologia',
            'medicamento',
            'fatores_risco',
            'atividade_atual',
            'rotina',
            'objetivo',
            'observacoes',
            'avaliador'
        ]));

        return $stmt->execute($params);
    }

    // obter última avaliação do usuário
    public function buscarUltimaAvaliacao($usuario_id)
    {
        $sql = "SELECT * FROM avaliacoes_fisicas WHERE usuario_id = :usuario_id ORDER BY data_avaliacao DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // listar todas as avaliações de um aluno
    public function listarAvaliacoesDoAluno($usuario_id)
    {
        $sql = "SELECT a.*, u.nome AS nome_aluno
                FROM avaliacoes_fisicas a
                JOIN usuario u ON a.usuario_id = u.id
                WHERE a.usuario_id = :usuario_id
                ORDER BY a.data_avaliacao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // buscar por id
    public function buscarPorId($id)
    {
        $sql = "SELECT a.*, u.nome AS nome_usuario
            FROM avaliacoes_fisicas a
            JOIN usuario u ON u.id = a.usuario_id
            WHERE a.id = :id
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function avaliacaoEscolher()
    {
        $usuarioModel = new UsuarioModel($this->conn);
        $usuarios = $usuarioModel->buscarUsuarios();
        include __DIR__ . '/../Views/instrutor/avaliacaoEscolher.php';
    }
    public function listarPorInstrutor($instrutor_id)
    {
        // validar parametro
        $instrutor_id = (int) $instrutor_id;
        if ($instrutor_id <= 0) {
            return [];
        }

        $sql = "SELECT a.*, u.nome AS nome_usuario
            FROM avaliacoes_fisicas a
            JOIN usuario u ON u.id = a.usuario_id
            WHERE a.instrutor_id = :instrutor_id
            ORDER BY a.data_avaliacao DESC, a.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':instrutor_id', $instrutor_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function excluir($id)
    {
        // garante int
        $id = (int) $id;
        if ($id <= 0) {
            return false;
        }

        $sql = "DELETE FROM avaliacoes_fisicas WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([':id' => $id]);

        // para debug, se der false retorne info do erro (temporário)
        if (!$ok) {
            // opcional: registrar erro em log
            $err = $stmt->errorInfo();
            // file_put_contents(__DIR__.'/../logs/sql_errors.log', json_encode($err).PHP_EOL, FILE_APPEND);
        }

        return $ok;
    }
    public function atualizar($id, $dados)
    {
        $sql = "UPDATE avaliacoes_fisicas SET
        estatura = :estatura,
        peso = :peso,
        imc = :imc,
        subescapular = :subescapular,
        triceps = :triceps,
        axilar_media = :axilar_media,
        toracica = :toracica,
        supra_iliaca = :supra_iliaca,
        abdominal = :abdominal,
        coxa = :coxa,
        percentual_gordura = :percentual_gordura,
        massa_magra = :massa_magra,
        massa_gorda = :massa_gorda,
        torax = :torax,
        cintura = :cintura,
        abdomen = :abdomen,
        quadril = :quadril,
        coxa_direita = :coxa_direita,
        coxa_esquerda = :coxa_esquerda,
        perna_direita = :perna_direita,
        perna_esquerda = :perna_esquerda,
        braco_direito = :braco_direito,
        braco_esquerdo = :braco_esquerdo,
        antebraco_direito = :antebraco_direito,
        antebraco_esquerdo = :antebraco_esquerdo,
        rcdq = :rcdq,
        nivel_atividade = :nivel_atividade,
        tmb = :tmb,
        necessidade_energetica = :necessidade_energetica,
        cirurgia = :cirurgia,
        patologia = :patologia,
        medicamento = :medicamento,
        fatores_risco = :fatores_risco,
        atividade_atual = :atividade_atual,
        rotina = :rotina,
        objetivo = :objetivo,
        observacoes = :observacoes
    WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $dados['id'] = $id;

        return $stmt->execute($dados);
    }

}
