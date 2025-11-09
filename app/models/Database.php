<?php
// PDO: Database utiliza PDO para conexões seguras com o banco de dados.
// Justificativa: PDO fornece interface consistente e segura para diferentes bancos de dados, com prepared statements para prevenir SQL injection.

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
        try {
            $stmt = $this->conn->prepare($sql);
            
            // Usar named parameters
            foreach ($params as $key => $value) {
                $paramType = $this->getParamType($value);
                
                // Se a chave for numérica, usar placeholder ?
                if (is_int($key)) {
                    $stmt->bindValue($key + 1, $value, $paramType);
                } else {
                    // Se for string, usar named parameter
                    $stmt->bindValue(':' . $key, $value, $paramType);
                }
            }
            
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Erro na query: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            throw $e;
        }
    }

    private function getParamType($value) {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            return PDO::PARAM_BOOL;
        } elseif (is_null($value)) {
            return PDO::PARAM_NULL;
        } else {
            return PDO::PARAM_STR;
        }
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
