<?php
// Herança: VehicleController herda de BaseController, reutilizando funcionalidades comuns.
// Justificativa: Evita duplicação de código de autenticação e redirecionamento, focando em lógica específica de gerenciamento de veículos.

// Agregação: VehicleController agrega Vehicle para operações de banco de dados.
// Justificativa: Utiliza Vehicle como parte de sua implementação para acesso a dados de veículos.

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Vehicle.php';

class VehicleController extends BaseController {
    private $vehicle;

    public function __construct() {
        parent::__construct();
        $this->vehicle = new Vehicle();
    }

    // Lista todos os veículos
    public function index() {
        $this->requireAdmin();
        $vehicles = $this->vehicle->getAll();
        require __DIR__ . '/../views/manager/vehicles/index.php';
    }

    // Mostra formulário de criação
    public function create() {
        $this->requireAdmin();
        require __DIR__ . '/../views/manager/vehicles/create.php';
    }

    // Processa a criação do veículo
    public function store() {
        $this->requireAdmin();
        
        $vehicleData = [
            'type' => $_POST['type'],  
            'plate' => $_POST['plate'],
            'brand' => $_POST['brand'],
            'model' => $_POST['model'],
            'year' => $_POST['year'],
            'capacity' => $_POST['capacity'],
            'mileage' => $_POST['mileage'] ?? 0,  
            'fuel_type' => $_POST['fuel_type'],  
            'last_maintenance' => $_POST['last_maintenance'],
            'next_maintenance' => $_POST['next_maintenance'],  
            'chassis_number' => $_POST['chassis_number'],  
            'status' => $_POST['status'],
            'notes' => $_POST['notes'] ?? null
        ];

        if ($this->vehicle->create($vehicleData)) {
            $this->session->set('success', 'Veículo cadastrado com sucesso!');
            $this->redirect('/nexus/manager/vehicles');
        } else {
            $this->session->set('error', 'Erro ao cadastrar veículo.');
            $this->redirect('/nexus/manager/vehicles/create');
        }
    }

    // Mostra formulário de edição
    public function edit($id) {
        $this->requireAdmin();
        $vehicle = $this->vehicle->getById($id);
        if (!$vehicle) {
            $this->session->set('error', 'Veículo não encontrado.');
            $this->redirect('/nexus/manager/vehicles');
        }
        
        // Converta o array em objeto Vehicle se necessário
        $vehicleObj = new Vehicle($vehicle);
        require __DIR__ . '/../views/manager/vehicles/edit.php';
    }

    // Processa a atualização do veículo
    public function update($id) {
        $this->requireAdmin();
        
        $vehicleData = [
            'type' => $_POST['type'],
            'plate' => $_POST['plate'],
            'brand' => $_POST['brand'],
            'model' => $_POST['model'],
            'year' => (int)$_POST['year'],
            'capacity' => (int)$_POST['capacity'],
            'mileage' => (int)$_POST['mileage'],
            'fuel_type' => $_POST['fuel_type'],
            'last_maintenance' => $_POST['last_maintenance'],
            'next_maintenance' => $_POST['next_maintenance'],
            'chassis_number' => $_POST['chassis_number'],
            'status' => $_POST['status'],
            'notes' => $_POST['notes'] ?? null
        ];

        if ($this->vehicle->update($id, $vehicleData)) {
            $this->session->set('success', 'Veículo atualizado com sucesso!');
        } else {
            $this->session->set('error', 'Erro ao atualizar veículo.');
        }
        $this->redirect('/nexus/manager/vehicles');
    }

    // Remove um veículo
    public function delete($id) {
        $this->requireAdmin();
        
        if ($this->vehicle->delete($id)) {
            $this->session->set('success', 'Veículo removido com sucesso!');
        } else {
            $this->session->set('error', 'Erro ao remover veículo.');
        }
        $this->redirect('/nexus/manager/vehicles');
    }
}