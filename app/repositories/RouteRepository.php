<?php
// Agregação: RouteRepository agrega Route para operações de banco de dados.
// Justificativa: Utiliza Route como parte de sua implementação para acesso a dados de rotas.

// Associação: RouteRepository tem associação com VehicleRepository para validações.
// Justificativa: Utiliza VehicleRepository para verificar disponibilidade de veículos, mas pode existir independentemente.

// Encapsulamento: Propriedades privadas protegem a instância de Route.
// Justificativa: Impede acesso direto ao modelo de rotas.

// Visibilidade de Propriedades e Métodos: Métodos públicos para operações externas.
// Justificativa: Controla o acesso às funcionalidades do repositório.

require_once __DIR__ . '/../models/Route.php';

class RouteRepository {
    private $route;

    public function __construct() {
        $this->route = new Route();
    }

    public function getAvailableRoutes() {
        $sql = "SELECT * FROM routes WHERE status = 'scheduled' AND active = 1 ORDER BY departure_time ASC";
        try {
            $stmt = $this->route->executeQuery($sql);
            return $this->route->fetchAll($stmt);
        } catch (PDOException $e) {
            error_log("Erro ao buscar rotas disponíveis: " . $e->getMessage());
            return [];
        }
    }

    public function getRoutesByDay($day) {
        $sql = "SELECT * FROM routes WHERE status = 'Ativo' AND days_of_week LIKE ? ORDER BY departure_time ASC";
        try {
            $stmt = $this->route->executeQuery($sql, ["%$day%"]);
            return $this->route->fetchAll($stmt);
        } catch (PDOException $e) {
            error_log("Erro ao buscar rotas por dia: " . $e->getMessage());
            return [];
        }
    }

    public function getRouteById($id) {
        return $this->route->getById($id);
    }

    public function getAllRoutes() {
        return $this->route->getAll();
    }

    public function createRoute($data) {
        return $this->route->create($data);
    }

    public function updateRoute($id, $data) {
        return $this->route->update($id, $data);
    }

    public function deleteRoute($id) {
        return $this->route->delete($id);
    }

    public function getDriverVehicles($driverId) {
        // Implementar lógica para buscar veículos do motorista
        $sql = "SELECT v.* FROM vehicles v
                WHERE v.driver_id = ? AND v.status = 'Ativo'
                ORDER BY v.plate";
        try {
            $stmt = $this->route->executeQuery($sql, [$driverId]);
            return $this->route->fetchAll($stmt);
        } catch (PDOException $e) {
            error_log("Erro ao buscar veículos do motorista: " . $e->getMessage());
            return [];
        }
    }

    public function getRoutesByDriver($driverId) {
        $sql = "SELECT r.*
                FROM routes r
                WHERE r.driver_id = ? AND r.status = 'scheduled' AND r.active = 1
                ORDER BY r.departure_time";

        try {
            $stmt = $this->route->executeQuery($sql, [$driverId]);
            return $this->route->fetchAll($stmt);
        } catch (PDOException $e) {
            error_log("Erro ao buscar rotas do motorista: " . $e->getMessage());
            return [];
        }
    }
}
?>