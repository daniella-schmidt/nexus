<?php
// Herança: HomeController herda de BaseController, reutilizando funcionalidades comuns.
// Justificativa: Evita duplicação de código de autenticação e redirecionamento, focando em lógica específica de páginas públicas.

require_once __DIR__ . '/BaseController.php';

class HomeController extends BaseController {
    // Agregação: Não há propriedades privadas para repositórios neste controlador.
    // Justificativa: Este controlador não utiliza repositórios externos, focando apenas em renderização de views públicas.
    public function about() {
        $aboutFilePath = __DIR__ . '/../views/home/about.html';
        if (file_exists($aboutFilePath)) {
            readfile($aboutFilePath);
        } else {
            http_response_code(404);
            echo "Página About não encontrada.";
        }
    }
}
?>