<?php
// Herança: Classe Route herda de Database, reutilizando funcionalidades de conexão.
// Justificativa: Permite que Route foque em lógica específica de rotas sem duplicar código de banco de dados.

// Agregação: Route agrega funcionalidades de Database através de herança.
// Justificativa: Utiliza Database como parte de sua implementação para operações de banco de dados.

// Encapsulamento: Propriedades privadas protegem dados da rota.
// Justificativa: Impede acesso direto aos dados, forçando uso de getters e setters.

// Visibilidade de Propriedades e Métodos: Métodos públicos para operações externas, privados para internas.
// Justificativa: Controla o acesso às funcionalidades da classe rota.

require_once 'Database.php';

class Route extends Database {
    
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $sql = "SELECT * FROM routes ORDER BY created_at DESC";
        try {
            $stmt = $this->executeQuery($sql);
            return $this->fetchAll($stmt);
        } catch (PDOException $e) {
            error_log("Erro ao listar rotas: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        $sql = "SELECT * FROM routes WHERE id = ?";
        try {
            $stmt = $this->executeQuery($sql, [$id]);
            return $this->fetchOne($stmt);
        } catch (PDOException $e) {
            error_log("Erro ao buscar rota: " . $e->getMessage());
            return false;
        }
    }

    public function create($data) {
        error_log("=== INICIANDO CRIAÇÃO DE ROTA NO MODEL ===");
        error_log("Dados recebidos no model: " . print_r($data, true));

        // SQL com TODOS os campos obrigatórios da tabela
        $sql = "INSERT INTO routes (
            name, 
            origin, 
            destination, 
            route_date, 
            schedule_id, 
            departure_time, 
            pickup_points, 
            max_capacity, 
            current_occupancy,
            status,
            active,
            arrival_time,
            description,
            days_of_week
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['name'] ?? '',
            $data['origin'] ?? '',
            $data['destination'] ?? '',
            $data['route_date'] ?? date('Y-m-d'),
            $data['schedule_id'] ?? 1,
            $data['departure_time'] ?? '',
            $data['pickup_points'] ?? 'A definir',
            $data['max_capacity'] ?? 40,
            $data['current_occupancy'] ?? 0,
            $data['status'] ?? 'scheduled',
            isset($data['active']) ? (int)$data['active'] : 1,
            $data['arrival_time'] ?? null,
            $data['description'] ?? null,
            $data['days_of_week'] ?? null
        ];

        error_log("SQL: " . $sql);
        error_log("Params: " . print_r($params, true));

        try {
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                $lastId = $this->conn->lastInsertId();
                error_log("Rota inserida com sucesso. ID: " . $lastId);
                return $lastId;
            } else {
                error_log("Falha na execução do SQL");
                $errorInfo = $stmt->errorInfo();
                error_log("Erro PDO: " . print_r($errorInfo, true));
                return false;
            }
        } catch (PDOException $e) {
            error_log("ERRO PDO ao criar rota: " . $e->getMessage());
            error_log("Código do erro: " . $e->getCode());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE routes 
                SET name = ?, 
                    origin = ?, 
                    destination = ?,
                    route_date = ?,
                    schedule_id = ?,
                    departure_time = ?, 
                    pickup_points = ?,
                    max_capacity = ?,
                    current_occupancy = ?,
                    status = ?, 
                    active = ?,
                    arrival_time = ?,
                    description = ?,
                    days_of_week = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = ?";
        
        $params = [
            $data['name'] ?? '',
            $data['origin'] ?? '',
            $data['destination'] ?? '',
            $data['route_date'] ?? date('Y-m-d'),
            $data['schedule_id'] ?? 1,
            $data['departure_time'] ?? '',
            $data['pickup_points'] ?? 'A definir',
            $data['max_capacity'] ?? 40,
            $data['current_occupancy'] ?? 0,
            $data['status'] ?? 'scheduled',
            isset($data['active']) ? (int)$data['active'] : 1,
            $data['arrival_time'] ?? null,
            $data['description'] ?? null,
            $data['days_of_week'] ?? null,
            $id
        ];

        error_log("SQL UPDATE: " . $sql);
        error_log("Params UPDATE: " . print_r($params, true));

        try {
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute($params);
            error_log("Resultado UPDATE: " . ($result ? 'SUCESSO' : 'FALHA'));
            error_log("Linhas afetadas: " . $stmt->rowCount());
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar rota: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM routes WHERE id = ?";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao deletar rota: " . $e->getMessage());
            return false;
        }
    }

    // Método auxiliar para executar queries
    public function executeQuery($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Método auxiliar para fetchAll
    public function fetchAll($stmt) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método auxiliar para fetchOne
    public function fetchOne($stmt) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>