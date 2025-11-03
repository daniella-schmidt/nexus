<?php
// Herança: Classe Vehicle herda de Database, reutilizando funcionalidades de conexão.
// Justificativa: Permite que Vehicle foque em lógica específica de veículos sem duplicar código de banco de dados.

// Agregação: Vehicle agrega funcionalidades de Database através de herança.
// Justificativa: Utiliza Database como parte de sua implementação para operações de banco de dados.

// Encapsulamento: Propriedades privadas protegem dados do veículo.
// Justificativa: Impede acesso direto aos dados, forçando uso de getters e setters.

// Visibilidade de Propriedades e Métodos: Métodos públicos para operações externas, privados para internas.
// Justificativa: Controla o acesso às funcionalidades da classe veículo.

require_once 'Database.php';

class Vehicle extends Database {
    private $id;
    private $type;
    private $plate;
    private $brand;
    private $model;
    private $year;
    private $capacity;
    private $mileage;
    private $fuelType;
    private $lastMaintenance;
    private $nextMaintenance;
    private $chassisNumber;
    private $status;
    private $notes;
    private $createdAt;
    private $updatedAt;

    public function __construct($data = []) {
        parent::__construct();
        
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->type = $data['type'] ?? '';
            $this->plate = $data['plate'] ?? '';
            $this->brand = $data['brand'] ?? '';
            $this->model = $data['model'] ?? '';
            $this->year = $data['year'] ?? null;
            $this->capacity = $data['capacity'] ?? null;
            $this->mileage = $data['mileage'] ?? 0;
            $this->fuelType = $data['fuel_type'] ?? '';
            $this->lastMaintenance = $data['last_maintenance'] ?? null;
            $this->nextMaintenance = $data['next_maintenance'] ?? null;
            $this->chassisNumber = $data['chassis_number'] ?? '';
            $this->status = $data['status'] ?? 'Ativo';
            $this->notes = $data['notes'] ?? null;
            $this->createdAt = $data['created_at'] ?? null;
            $this->updatedAt = $data['updated_at'] ?? null;
        }
    }

    // Getters
    public function getId() { return $this->id; }
    public function getType() { return $this->type; }
    public function getPlate() { return $this->plate; }
    public function getBrand() { return $this->brand; }

    public function getById($id) {
        $sql = "SELECT * FROM vehicles WHERE id = ?";
        try {
            $stmt = $this->executeQuery($sql, [$id]);
            return $this->fetchOne($stmt);
        } catch (PDOException $e) {
            error_log("Erro ao buscar veículo: " . $e->getMessage());
            return false;
        }
    }

    public function getAll() {
        $sql = "SELECT * FROM vehicles ORDER BY created_at DESC";
        try {
            $stmt = $this->executeQuery($sql);
            return $this->fetchAll($stmt);
        } catch (PDOException $e) {
            error_log("Erro ao listar veículos: " . $e->getMessage());
            return [];
        }
    }
    public function getModel() { return $this->model; }
    public function getYear() { return $this->year; }
    public function getCapacity() { return $this->capacity; }
    public function getMileage() { return $this->mileage; }
    public function getFuelType() { return $this->fuelType; }
    public function getLastMaintenance() { return $this->lastMaintenance; }
    public function getNextMaintenance() { return $this->nextMaintenance; }
    public function getChassisNumber() { return $this->chassisNumber; }
    public function getStatus() { return $this->status; }
    public function getNotes() { return $this->notes; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }

    // Setters
    public function setType($type) { $this->type = $type; }
    public function setPlate($plate) { $this->plate = $plate; }
    public function setBrand($brand) { $this->brand = $brand; }
    public function setYear($year) { $this->year = $year; }
    public function setCapacity($capacity) { $this->capacity = $capacity; }
    public function setMileage($mileage) { $this->mileage = $mileage; }
    public function setFuelType($fuelType) { $this->fuelType = $fuelType; }
    public function setLastMaintenance($date) { $this->lastMaintenance = $date; }
    public function setNextMaintenance($date) { $this->nextMaintenance = $date; }
    public function setChassisNumber($number) { $this->chassisNumber = $number; }
    public function setStatus($status) { $this->status = $status; }
    public function setNotes($notes) { $this->notes = $notes; }
    public function setModel($model) { $this->model = $model; }

    public function getByStatus($status) {
        $sql = "SELECT * FROM vehicles WHERE status = ? ORDER BY created_at DESC";
        try {
            $stmt = $this->executeQuery($sql, [$status]);
            return $this->fetchAll($stmt);
        } catch (PDOException $e) {
            error_log("Erro ao buscar veículos por status: " . $e->getMessage());
            return [];
        }
    }
    
    // CRUD Operationss

    public function find($id) {
        $sql = "SELECT * FROM vehicles WHERE id = ?";
        $stmt = $this->executeQuery($sql, [$id]);
        return $this->fetchOne($stmt);
    }

    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }

    public function create($data) {
        $sql = "INSERT INTO vehicles (
            type, plate, brand, model, year, capacity, mileage,
            fuel_type, last_maintenance, next_maintenance,
            chassis_number, status, notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['type'],
            $data['plate'],
            $data['brand'],
            $data['model'],
            $data['year'],
            $data['capacity'],
            $data['mileage'],
            $data['fuel_type'],
            $data['last_maintenance'],
            $data['next_maintenance'],
            $data['chassis_number'],
            $data['status'],
            $data['notes'] ?? null
        ];

        try {
            $this->executeQuery($sql, $params);
            $id = $this->getLastInsertId();
            return $id;
        } catch (PDOException $e) {
            error_log("Erro ao criar veículo: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE vehicles 
                SET type = ?, plate = ?, brand = ?, model = ?, 
                    year = ?, capacity = ?, mileage = ?, fuel_type = ?,
                    last_maintenance = ?, next_maintenance = ?, chassis_number = ?,
                    status = ?, notes = ?
                WHERE id = ?";
        
        $params = [
            $data['type'],
            $data['plate'],
            $data['brand'],
            $data['model'],
            $data['year'],
            $data['capacity'],
            $data['mileage'],
            $data['fuel_type'],
            $data['last_maintenance'],
            $data['next_maintenance'],
            $data['chassis_number'],
            $data['status'],
            $data['notes'] ?? null,
            $id
        ];

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar veículo: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        // Primeiro verifica se o veículo está em uso em alguma rota
        $sql = "SELECT COUNT(*) as count FROM routes WHERE vehicle_id = ?";
        $stmt = $this->executeQuery($sql, [$id]);
        $result = $this->fetchOne($stmt);
        
        if ($result['count'] > 0) {
            error_log("Veículo em uso em rotas ativas");
            return false;
        }

        // Se não estiver em uso, pode remover
        $sql = "DELETE FROM vehicles WHERE id = ?";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao deletar veículo: " . $e->getMessage());
            return false;
        }
    }
}