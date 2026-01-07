<?php
class AvaliacaoModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // inserir nova avaliação
   public function salvar(array $dados)
{
    $sql = "INSERT INTO avaliacoes_fisicas (
        usuario_id,
        instrutor_id,
        data_avaliacao,
        estatura,
        peso,
        imc,
        subescapular,
        triceps,
        axilar_media,
        toracica,
        supra_iliaca,
        abdominal,
        coxa,
        percentual_gordura,
        massa_magra,
        massa_gorda,
        torax,
        cintura,
        abdomen_med,
        quadril,
        coxa_direita,
        coxa_esquerda,
        perna_direita,
        perna_esquerda,
        braco_direito,
        braco_esquerdo,
        antebraco_direito,
        antebraco_esquerdo,
        rcq,
        nivel_atividade,
        tmb,
        necessidade_energetica,
        cirurgia,
        patologia,
        medicamento,
        fatores_risco,
        atividade_atual,
        rotina,
        objetivo,
        observacoes,
        avaliador
    ) VALUES (
        :usuario_id,
        :instrutor_id,
        :data_avaliacao,
        :estatura,
        :peso,
        :imc,
        :subescapular,
        :triceps,
        :axilar_media,
        :toracica,
        :supra_iliaca,
        :abdominal,
        :coxa,
        :percentual_gordura,
        :massa_magra,
        :massa_gorda,
        :torax,
        :cintura,
        :abdomen_med,
        :quadril,
        :coxa_direita,
        :coxa_esquerda,
        :perna_direita,
        :perna_esquerda,
        :braco_direito,
        :braco_esquerdo,
        :antebraco_direito,
        :antebraco_esquerdo,
        :rcq,
        :nivel_atividade,
        :tmb,
        :necessidade_energetica,
        :cirurgia,
        :patologia,
        :medicamento,
        :fatores_risco,
        :atividade_atual,
        :rotina,
        :objetivo,
        :observacoes,
        :avaliador
    )";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        ':usuario_id'             => $dados['usuario_id'],
        ':instrutor_id'           => $dados['instrutor_id'],
        ':data_avaliacao'         => $dados['data_avaliacao'],
        ':estatura'               => $dados['estatura'] ?? null,
        ':peso'                   => $dados['peso'] ?? null,
        ':imc'                    => $dados['imc'] ?? null,
        ':subescapular'           => $dados['subescapular'] ?? null,
        ':triceps'                => $dados['triceps'] ?? null,
        ':axilar_media'           => $dados['axilar_media'] ?? null,
        ':toracica'               => $dados['toracica'] ?? null,
        ':supra_iliaca'           => $dados['supra_iliaca'] ?? null,
        ':abdominal'              => $dados['abdominal'] ?? null,
        ':coxa'                   => $dados['coxa'] ?? null,
        ':percentual_gordura'     => $dados['percentual_gordura'] ?? null,
        ':massa_magra'            => $dados['massa_magra'] ?? null,
        ':massa_gorda'            => $dados['massa_gorda'] ?? null,
        ':torax'                  => $dados['torax'] ?? null,
        ':cintura'                => $dados['cintura'] ?? null,
        ':abdomen_med'            => $dados['abdomen_med'] ?? null,
        ':quadril'                => $dados['quadril'] ?? null,
        ':coxa_direita'           => $dados['coxa_direita'] ?? null,
        ':coxa_esquerda'          => $dados['coxa_esquerda'] ?? null,
        ':perna_direita'          => $dados['perna_direita'] ?? null,
        ':perna_esquerda'         => $dados['perna_esquerda'] ?? null,
        ':braco_direito'          => $dados['braco_direito'] ?? null,
        ':braco_esquerdo'         => $dados['braco_esquerdo'] ?? null,
        ':antebraco_direito'      => $dados['antebraco_direito'] ?? null,
        ':antebraco_esquerdo'     => $dados['antebraco_esquerdo'] ?? null,
        ':rcq'                    => $dados['rcq'] ?? null,
        ':nivel_atividade'        => $dados['nivel_atividade'] ?? null,
        ':tmb'                    => $dados['tmb'] ?? null,
        ':necessidade_energetica' => $dados['necessidade_energetica'] ?? null,
        ':cirurgia'               => $dados['cirurgia'] ?? null,
        ':patologia'              => $dados['patologia'] ?? null,
        ':medicamento'            => $dados['medicamento'] ?? null,
        ':fatores_risco'          => $dados['fatores_risco'] ?? null,
        ':atividade_atual'        => $dados['atividade_atual'] ?? null,
        ':rotina'                 => $dados['rotina'] ?? null,
        ':objetivo'               => $dados['objetivo'] ?? null,
        ':observacoes'            => $dados['observacoes'] ?? null,
        ':avaliador'              => $dados['avaliador'] ?? null,
    ]);

    return true;
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
      abdomen_med = :abdomen_med,
        coxa = :coxa,
        percentual_gordura = :percentual_gordura,
        massa_magra = :massa_magra,
        massa_gorda = :massa_gorda,
        torax = :torax,
        cintura = :cintura,
       
        quadril = :quadril,
        coxa_direita = :coxa_direita,
        coxa_esquerda = :coxa_esquerda,
        perna_direita = :perna_direita,
        perna_esquerda = :perna_esquerda,
        braco_direito = :braco_direito,
        braco_esquerdo = :braco_esquerdo,
        antebraco_direito = :antebraco_direito,
        antebraco_esquerdo = :antebraco_esquerdo,
        rcq = :rcq,
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

       return $stmt->execute([
    ':id' => $id,
    ':estatura' => $dados['estatura'] ?? null,
    ':peso' => $dados['peso'] ?? null,
    ':imc' => $dados['imc'] ?? null,
    ':subescapular' => $dados['subescapular'] ?? null,
    ':triceps' => $dados['triceps'] ?? null,
    ':axilar_media' => $dados['axilar_media'] ?? null,
    ':toracica' => $dados['toracica'] ?? null,
    ':supra_iliaca' => $dados['supra_iliaca'] ?? null,
    ':abdomen_med' => $dados['abdomen_med'] ?? null,
    ':coxa' => $dados['coxa'] ?? null,
    ':percentual_gordura' => $dados['percentual_gordura'] ?? null,
    ':massa_magra' => $dados['massa_magra'] ?? null,
    ':massa_gorda' => $dados['massa_gorda'] ?? null,
    ':torax' => $dados['torax'] ?? null,
    ':cintura' => $dados['cintura'] ?? null,
    ':quadril' => $dados['quadril'] ?? null,
    ':coxa_direita' => $dados['coxa_direita'] ?? null,
    ':coxa_esquerda' => $dados['coxa_esquerda'] ?? null,
    ':perna_direita' => $dados['perna_direita'] ?? null,
    ':perna_esquerda' => $dados['perna_esquerda'] ?? null,
    ':braco_direito' => $dados['braco_direito'] ?? null,
    ':braco_esquerdo' => $dados['braco_esquerdo'] ?? null,
    ':antebraco_direito' => $dados['antebraco_direito'] ?? null,
    ':antebraco_esquerdo' => $dados['antebraco_esquerdo'] ?? null,
    ':rcq' => $dados['rcq'] ?? null,
    ':nivel_atividade' => $dados['nivel_atividade'] ?? null,
    ':tmb' => $dados['tmb'] ?? null,
    ':necessidade_energetica' => $dados['necessidade_energetica'] ?? null,
    ':cirurgia' => $dados['cirurgia'] ?? null,
    ':patologia' => $dados['patologia'] ?? null,
    ':medicamento' => $dados['medicamento'] ?? null,
    ':fatores_risco' => $dados['fatores_risco'] ?? null,
    ':atividade_atual' => $dados['atividade_atual'] ?? null,
    ':rotina' => $dados['rotina'] ?? null,
    ':objetivo' => $dados['objetivo'] ?? null,
    ':observacoes' => $dados['observacoes'] ?? null,
]);

    }
public function getLastId()
{
    return $this->conn->lastInsertId();
}

}
