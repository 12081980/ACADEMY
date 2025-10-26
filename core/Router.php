<?php

class Router
{
    private $routes = [];
    private $db;

    /**
     * Construtor — recebe a conexão com o banco (PDO)
     */
    public function __construct($db = null)
    {
        $this->db = $db;
    }

    /**
     * Adiciona uma rota ao roteador
     */
    public function addRoute($route, $action, $methods = ['GET'])
    {
        $this->routes[$route] = [
            'action' => $action,
            'methods' => $methods
        ];
    }

    /**
     * Faz o despacho da rota (executa o controller e método)
     */
    public function dispatch($url)
    {
        // Remove eventuais barras duplas e normaliza a URL
        $url = rtrim($url, '/');
        if ($url === '')
            $url = '/';

        foreach ($this->routes as $route => $config) {
            $routePattern = '#^' . preg_replace('/\{[a-zA-Z_]+\}/', '([a-zA-Z0-9_-]+)', $route) . '$#';

            if (preg_match($routePattern, $url, $matches)) {
                list($controllerName, $methodName) = explode('@', $config['action']);

                $controllerPath = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';

                if (!file_exists($controllerPath)) {
                    http_response_code(500);
                    echo "Erro interno: Controller <b>{$controllerName}</b> não encontrado.";
                    return;
                }

                require_once $controllerPath;

                // Cria o controller com ou sem conexão, conforme necessário
                if ($this->db) {
                    $controller = new $controllerName($this->db);
                } else {
                    $controller = new $controllerName();
                }

                // Remove o primeiro item (rota completa)
                array_shift($matches);

                if (!method_exists($controller, $methodName)) {
                    http_response_code(500);
                    echo "Erro: Método <b>{$methodName}</b> não existe em <b>{$controllerName}</b>.";
                    return;
                }

                // Verifica método HTTP permitido
                if (!in_array($_SERVER['REQUEST_METHOD'], $config['methods'])) {
                    http_response_code(405);
                    echo "Método não permitido.";
                    return;
                }

                // Executa o método da rota
                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        // Se não encontrar rota
        http_response_code(404);
        echo "Página não encontrada!";
    }
}
