<?php
class AvaliacaoModel
{
    private $conn;
    private $table = 'avaliacoes_fisicas';

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function salvarAvaliacao($data)
    {
        $sql = "INSERT INTO {$this->table} (
            usuario_id, avaliador_id, data_avaliacao,
            estatura, peso, imc,
            subescapular, triceps, axilar_media, toracica, supra_iliaca, abdominal, coxa,
            percentual_gordura, massa_magra, massa_gorda,
            torax, cintura, abdomen, quadril, coxa_direita, coxa_esquerda,
            perna_direita, perna_esquerda, braco_direito, braco_esquerdo,
            antebraco_direito, antebraco_esquerdo,
            rcq, nivel_atividade, tmb, necessidade_energetica,
            cirurgia, patologia, medicamento, fatores_risco,
            atividade_atual, rotina, objetivo, observacoes
        ) VALUES (
            :usuario_id, :avaliador_id, :data_avaliacao,
            :estatura, :peso, :imc,
            :subescapular, :triceps, :axilar_media, :toracica, :supra_iliaca, :abdominal, :coxa,
            :percentual_gordura, :massa_magra, :massa_gorda,
            :torax, :cintura, :abdomen, :quadril, :coxa_direita, :coxa_esquerda,
            :perna_direita, :perna_esquerda, :braco_direito, :braco_esquerdo,
            :antebraco_direito, :antebraco_esquerdo,
            :rcq, :nivel_atividade, :tmb, :necessidade_energetica,
            :cirurgia, :patologia, :medicamento, :fatores_risco,
            :atividade_atual, :rotina, :objetivo, :observacoes
        )";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
}
