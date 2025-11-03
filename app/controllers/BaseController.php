<?php
// Herança: BaseController serve como classe base para outros controllers.
// Justificativa: Permite reutilização de funcionalidades comuns como autenticação e redirecionamento.

// Agregação: BaseController agrega Session para gerenciamento de sessão.
// Justificativa: Utiliza Session como parte de sua implementação para controle de autenticação.

require_once __DIR__ . '/../util/Session.php';

class BaseController {
    // Encapsulamento: Propriedade $session é protegida, acessível apenas pela classe e subclasses.
    // Justificativa: Protege o objeto de sessão, permitindo acesso controlado através de métodos.
    protected $session;

    // Visibilidade de Propriedades e Métodos: Construtor público para inicialização.
    // Justificativa: Permite instanciação controlada das subclasses.
    public function __construct() {
        $this->session = new Session();
    }

    // Visibilidade de Propriedades e Métodos: Métodos protegidos para funcionalidades comuns.
    // Justificativa: Métodos protegidos permitem que subclasses utilizem funcionalidades sem expor externamente.
    protected function redirect($url) {
        // Remove o prefixo /nexus se já estiver presente
        $url = str_replace('/nexus/', '/', $url);
        $url = str_replace('/nexus', '/', $url);

        // Garante que todos os redirecionamentos usem o base path correto
        $basePath = '/nexus';
        $fullUrl = $basePath . $url;

        header("Location: $fullUrl");
        exit();
    }

    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function requireAuth() {
        if (!$this->session->get('user_id')) {
            $this->redirect('/login');
        }
    }

    protected function requireAdmin() {
        $this->requireAuth();
        if ($this->session->get('user_type') !== 'admin') {
            $this->redirect('/dashboard');
        }
    }
}
?>
