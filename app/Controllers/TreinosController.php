<?php

require_once __DIR__ . '/../../core/conn.php';
require_once __DIR__ . '/../Models/TreinoModel.php';

class TreinosController
{
    private $conn;
    private $model;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Usa a conexão PDO já criada no seu core/conn.php
        $this->conn = $GLOBALS['conn'] ?? (require __DIR__ . '/../../core/conn.php');
        $this->model = new TreinoModel($this->conn);
    }

    private function verificarAutenticacao()
    {
        if (!isset($_SESSION['usuario']['id'])) {
            header('Location: /ACADEMY/public/login');
            exit;
        }
        return $_SESSION['usuario']['id'];
    }

    // public function realizados()
    // {
    //     $id_usuario = $this->verificarAutenticacao();

    //     try {
    //         $treinos = $this->model->buscarTreinosRealizados($id_usuario);
    //     } catch (Exception $e) {
    //         error_log($e->getMessage());
    //         $treinos = [];
    //         $erro = "Erro ao carregar treinos realizados.";
    //     }

    //     include __DIR__ . '/../views/treinos/realizados.php';
    // }

    public function em_andamento()
    {
        $usuarioId = $this->verificarAutenticacao();

        try {
            $treinos = $this->model->buscarTreinosEmAndamento($usuarioId);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $treinos = [];
            $erro = "Erro ao carregar treinos em andamento.";
        }

        include __DIR__ . '/../views/treinos/em_andamento.php';
    }

    public function iniciar()
    {
        $usuarioId = $this->verificarAutenticacao();

        if (!isset($_POST['exercicios']) || empty($_POST['exercicios'])) {
            echo "Nenhum exercício enviado.";
            return;
        }

        $exercicios = json_decode($_POST['exercicios'], true);

        if (!is_array($exercicios)) {
            echo "Erro ao processar os exercícios.";
            return;
        }

        $_SESSION['treino_em_andamento'] = [
            'id_usuario' => $usuarioId,
            'nome' => $_POST['nome'] ?? 'Treino sem nome',
            'descricao' => $_POST['descricao'] ?? '',
            'inicio' => date('Y-m-d H:i:s'),
            'exercicios' => $exercicios
        ];

        header('Location: /ACADEMY/public/treinos/em_andamento');
        exit;
    }

    public function finalizar()
    {
        $usuarioId = $this->verificarAutenticacao();

        if (!isset($_SESSION['treino_em_andamento'])) {
            echo "Nenhum treino em andamento.";
            return;
        }

        $treino = $_SESSION['treino_em_andamento'];
        $this->model->finalizarTreino($treino);

        unset($_SESSION['treino_em_andamento']);

        header('Location: /ACADEMY/public/treinos/realizados');
        exit;
    }

    public function graficos()
    {
        $usuarioId = $this->verificarAutenticacao();

        try {
            $dadosTreinos = $this->model->buscarTreinosPorUsuario($usuarioId);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $dadosTreinos = [];
            $erro = "Erro ao carregar dados para gráficos.";
        }

        include __DIR__ . '/../views/treinos/graficos.php';
    }
    public function realizados()
    {
        $usuarioId = $_SESSION['usuario']['id'] ?? null;

        if (!$usuarioId) {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        // Página atual (default = 1)
        $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;

        try {
            $treinos = $this->model->getTreinosRealizados($usuarioId, $pagina, 5);
            $totalTreinos = $this->model->contarTreinosRealizados($usuarioId);
            $totalPaginas = ceil($totalTreinos / 5);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $treinos = [];
            $totalPaginas = 1;
            $pagina = 1;
        }

        include __DIR__ . "/../views/treinos/realizados.php";
    }
}