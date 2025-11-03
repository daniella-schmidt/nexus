<?php
// Encapsulamento: Propriedade $conn é protegida, acessível apenas pela classe e subclasses.
// Justificativa: Protege a conexão com o banco de dados, permitindo acesso controlado através de métodos públicos.

// Composição: Database compõe DatabaseConfig para obter conexão.
// Justificativa: Database utiliza DatabaseConfig como parte de sua implementação, mas DatabaseConfig pode existir independentemente.

// Visibilidade de Propriedades e Métodos: Métodos públicos para operações externas, protegidos para subclasses.
// Justificativa: Controla o acesso às funcionalidades de banco de dados.

require_once __DIR__ . '/../config/database.php';

class Database {
    protected $conn;

    // Visibilidade de Propriedades e Métodos: Construtor público para inicialização.
    // Justificativa: Permite instanciação controlada das subclasses.
    public function __construct() {
        $this->conn = DatabaseConfig::getConnection();
    }

    // Visibilidade de Propriedades e Métodos: Métodos públicos para operações de banco de dados.
    // Justificativa: Interface pública para executar queries, mantendo encapsulamento da conexão.
    public function executeQuery($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll($stmt) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne($stmt) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }

    public function __destruct() {
        $this->conn = null;
    }
}
?>
