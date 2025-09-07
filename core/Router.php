<?php
class Router
{
    private $routes = [];

    public function addRoute($path, $controllerAction)
    {
        // Transforma {id} em regex para capturar valores
        $pathRegex = preg_replace('/\{[a-zA-Z_]+\}/', '([0-9]+)', $path);
        $this->routes[$pathRegex] = $controllerAction;
    }

    public function dispatch($url)
    {
        foreach ($this->routes as $route => $controllerAction) {
            if (preg_match("#^$route$#", $url, $matches)) {
                list($controller, $action) = explode('@', $controllerAction);

                $controllerFile = __DIR__ . '/../app/Controllers/' . $controller . '.php';
                if (!file_exists($controllerFile)) {
                    die("Controller $controller não encontrado.");
                }
                require_once $controllerFile;

                if (!class_exists($controller)) {
                    die("Classe $controller não encontrada.");
                }
                $controllerInstance = new $controller();

                if (!method_exists($controllerInstance, $action)) {
                    die("Método $action não encontrado em $controller.");
                }

                // Remove o primeiro match (a rota inteira)
                array_shift($matches);

                // Chama o método com parâmetros (se existirem)
                return call_user_func_array([$controllerInstance, $action], $matches);
            }
        }

        http_response_code(404);
        echo "Rota $url não encontrada.";
    }
}
