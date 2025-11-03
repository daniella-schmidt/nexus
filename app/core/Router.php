<?php
// Encapsulamento: A propriedade $routes é privada, acessível apenas dentro da classe.
// Justificativa: Protege os dados internos da classe, permitindo acesso controlado através de métodos públicos.

// Visibilidade de Propriedades e Métodos: Propriedade privada e métodos públicos.
// Justificativa: Propriedades privadas garantem que os dados sejam manipulados apenas pelos métodos da própria classe, enquanto métodos públicos permitem interação externa controlada.

class Router {
    private $routes = [];

    // Visibilidade de Propriedades e Métodos: Método público para adicionar rotas.
    // Justificativa: Permite que classes externas configurem rotas sem acessar diretamente a propriedade privada.
    public function addRoute($method, $path, $controllerAction) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controllerAction' => $controllerAction
        ];
    }

    // Visibilidade de Propriedades e Métodos: Método público para despachar requisições.
    // Justificativa: Interface pública para processar rotas, mantendo a lógica interna encapsulada.
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remover base path caso a aplicação esteja em um subdiretório
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $baseDir = rtrim(dirname($scriptName), '\\/');
        if ($baseDir && $baseDir !== '.') {
            if (strpos($requestUri, $baseDir) === 0) {
                $requestUri = substr($requestUri, strlen($baseDir));
                if ($requestUri === '') $requestUri = '/';
            }
        }

        foreach ($this->routes as $route) {
            // Converter rota em padrão regex
            $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<\1>[^/]+)', $route['path']);
            $pattern = '@^' . $pattern . '$@D';

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                list($controller, $action) = explode('@', $route['controllerAction']);
                require_once __DIR__ . '/../controllers/' . $controller . '.php';
                $controllerInstance = new $controller();

                // Remove índices numéricos do array de matches
                $params = array_filter($matches, function($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);

                // Chama o método com os parâmetros capturados
                call_user_func_array([$controllerInstance, $action], array_values($params));
                return;
            }
        }

        // Rota não encontrada
        http_response_code(404);
        echo "Página não encontrada";
    }
}
?>
