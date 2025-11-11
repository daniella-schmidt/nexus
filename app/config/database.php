<?php
// Encapsulamento: Propriedades privadas estáticas protegem dados de configuração.
// Justificativa: Impede acesso direto às credenciais de banco de dados.

// Visibilidade de Propriedades e Métodos: Propriedades privadas e método público.
// Justificativa: Propriedades privadas garantem que as configurações sejam manipuladas apenas pelos métodos da própria classe.

class DatabaseConfig {
    private static $host = 'localhost';
    private static $dbname = 'nexus';
    private static $username = 'root';
    private static $password = '';
    
    public static function getConnection() {
        try {
            $conn = new PDO(
                "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8",
                self::$username,
                self::$password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            return $conn;
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }
}
?>