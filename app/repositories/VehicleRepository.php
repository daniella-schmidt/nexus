<?php
// 4.2 Agregação: VehicleRepository agrega Database para operações de banco de dados.
// Justificativa: Utiliza Database como parte de sua implementação para acesso a dados de veículos.

// 4.1 Associação: VehicleRepository tem associação com RouteRepository para validações.
// Justificativa: Utiliza RouteRepository para verificar se veículo está em uso, mas pode existir independentemente.

// 3.1 Encapsulamento: Propriedades privadas protegem a instância de Database.
// Justificativa: Impede acesso direto à conexão de banco de dados.

// 2.3 Visibilidade de Propriedades e Métodos: Métodos públicos para operações externas.
// Justificativa: Controla o acesso às funcionalidades do repositório.

require_once __DIR__ . '/../models/User.php';

class VehicleRepository {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAllVehicles() {
        $sql = "SELECT v.*, u.name as driver_name, u.email as driver_email
                FROM vehicles v
                LEFT JOIN users u ON v.driver_id = u.id
                WHERE v.active = 1
                ORDER BY v.model";
        
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }
    
    public function getAvailableVehicles() {
        $sql = "SELECT v.*, u.name as driver_name 
                FROM vehicles v
                LEFT JOIN users u ON v.driver_id = u.id
                WHERE v.status = 'Ativo' AND v.active = 1
                ORDER BY v.model";
        
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }
    
    public function createVehicle($vehicleData) {
        $sql = "INSERT INTO vehicles (plate, model, capacity, driver_id) 
                VALUES (?, ?, ?, ?)";
        
        $params = [
            $vehicleData['plate'],
            $vehicleData['model'],
            $vehicleData['capacity'],
            $vehicleData['driver_id']
        ];
        
        $stmt = $this->db->executeQuery($sql, $params);
    }
    
    public function updateVehicle($vehicleId, $vehicleData) {
        $sql = "UPDATE vehicles SET plate = ?, model = ?, capacity = ?, driver_id = ? 
                WHERE id = ?";
        
        $params = [
            $vehicleData['plate'],
            $vehicleData['model'],
            $vehicleData['capacity'],
            $vehicleData['driver_id'],
            $vehicleId
        ];
        
        $stmt = $this->db->executeQuery($sql, $params);
    }
    
    public function deleteVehicle($vehicleId) {
        $sql = "UPDATE vehicles SET active = 0 WHERE id = ?";
        $stmt = $this->db->executeQuery($sql, [$vehicleId]);
    }
    
    public function getVehicleById($vehicleId) {
        $sql = "SELECT v.*, u.name as driver_name, u.email as driver_email
                FROM vehicles v
                LEFT JOIN users u ON v.driver_id = u.id
                WHERE v.id = ? AND v.active = 1";
        
        $stmt = $this->db->executeQuery($sql, [$vehicleId]);
        return $this->db->fetchOne($stmt);
    }
    
    public function assignDriver($vehicleId, $driverId) {
        $sql = "UPDATE vehicles SET driver_id = ? WHERE id = ?";
        $stmt = $this->db->executeQuery($sql, [$driverId, $vehicleId]);
    }
    
    public function getVehiclesWithoutDriver() {
        $sql = "SELECT v.* 
                FROM vehicles v
                WHERE v.active = 1 AND v.driver_id IS NULL
                ORDER BY v.model";
        
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }
}
?>