<?php
// Encapsulamento: Classe Session encapsula operações de sessão, controlando acesso aos dados de sessão.
// Justificativa: Impede manipulação direta de $_SESSION, fornecendo interface controlada.

// Visibilidade de Propriedades e Métodos: Todos os métodos são públicos para permitir acesso externo.
// Justificativa: Classe utilitária que precisa ser acessível de qualquer parte da aplicação.

class Session {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public function get($key) {
        return $_SESSION[$key] ?? null;
    }
    
    public function remove($key) {
        unset($_SESSION[$key]);
    }
    
    public function destroy() {
        session_destroy();
    }
    
    public function setFlash($key, $value) {
        $_SESSION['flash'][$key] = $value;
    }
    
    public function getFlash($key) {
        $value = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $value;
    }
}
?>