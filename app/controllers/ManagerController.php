<?php
// Herança: ManagerController herda de BaseController, reutilizando funcionalidades comuns.
// Justificativa: Evita duplicação de código de autenticação e redirecionamento, focando em lógica específica de gerenciamento.

// Agregação: ManagerController agrega múltiplos repositórios e AdminController.
// Justificativa: Utiliza RouteRepository, VehicleRepository e ReportRepository para operações de dados, e AdminController para funcionalidades de administração de usuários.

require_once 'BaseController.php';
require_once '../repositories/RouteRepository.php';
require_once '../repositories/VehicleRepository.php';
require_once '../repositories/ReportRepository.php';
require_once 'AdminController.php';

class ManagerController extends BaseController {
    private $routeRepo;
    private $vehicleRepo;
    private $reportRepo;
    private $adminController;
    
    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
        $this->routeRepo = new RouteRepository();
        $this->vehicleRepo = new VehicleRepository();
        $this->reportRepo = new ReportRepository();
        $this->adminController = new AdminController();
    }
    
    public function vehicles() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vehicleData = [
                'plate' => $_POST['plate'],
                'model' => $_POST['model'],
                'capacity' => $_POST['capacity'],
                'driver_id' => $_POST['driver_id'] ?? null
            ];
            
            if ($this->vehicleRepo->createVehicle($vehicleData)) {
                $this->session->set('success', 'Veículo cadastrado com sucesso!');
            } else {
                $this->session->set('error', 'Erro ao cadastrar veículo');
            }
        }
        
        $vehicles = $this->vehicleRepo->getAllVehicles();
        
        $this->renderView('manager/vehicles', [
            'vehicles' => $vehicles
        ]);
    }
    
    public function routes() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $routeData = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'schedule' => $_POST['schedule'],
                'vehicle_id' => $_POST['vehicle_id'],
                'max_capacity' => $_POST['max_capacity']
            ];
            
            if ($this->routeRepo->createRoute($routeData)) {
                $this->session->set('success', 'Rota cadastrada com sucesso!');
            } else {
                $this->session->set('error', 'Erro ao cadastrar rota');
            }
        }
        
        $routes = $this->routeRepo->getAllRoutes();
        $vehicles = $this->vehicleRepo->getAvailableVehicles();
        
        $this->renderView('manager/routes', [
            'routes' => $routes,
            'vehicles' => $vehicles
        ]);
    }
    
    public function reports() {
        $reportType = $_GET['type'] ?? 'general';
        
        $reportData = $this->reportRepo->generateReport($reportType);
        
        if (isset($_GET['export'])) {
            $this->exportReport($reportData, $reportType);
        }
        
        $this->renderView('manager/reports', [
            'reportData' => $reportData,
            'reportType' => $reportType
        ]);
    }
    
    private function exportReport($data, $type) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="relatorio_' . $type . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit();
    }
    
    private function renderView($view, $data = []) {
        extract($data);
        require_once "../views/$view.php";
    }

    public function users() {
        $this->adminController->users();
    }
    
    public function createUser() {
        $this->adminController->create();
    }
    
    public function editUser($userId) {
        $this->adminController->edit($userId);
    }
    
    public function updateUser($userId) {
        $this->adminController->edit($userId); // O método edit já trata POST
    }
    
    public function deleteUser($userId) {
        $this->adminController->delete($userId);
    }
    
    public function toggleUserStatus($userId) {
        $this->adminController->toggleStatus($userId);
    }
}
?>