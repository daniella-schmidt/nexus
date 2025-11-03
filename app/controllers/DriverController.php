<?php
// Herança: DriverController herda de BaseController, reutilizando funcionalidades comuns.
// Justificativa: Evita duplicação de código de autenticação e redirecionamento, focando em lógica específica de motoristas.

// Agregação: DriverController agrega múltiplos repositórios para acesso a dados.
// Justificativa: Utiliza ReservationRepository, RouteRepository, VehicleRepository e UserRepository como parte de sua implementação para operações de motorista.

require_once 'BaseController.php';
require_once __DIR__ . '/../repositories/ReservationRepository.php';
require_once __DIR__ . '/../repositories/RouteRepository.php';
require_once __DIR__ . '/../repositories/VehicleRepository.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

class DriverController extends BaseController {
    private $reservationRepo;
    private $routeRepo;
    private $vehicleRepo;
    private $userRepo;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->reservationRepo = new ReservationRepository();
        $this->routeRepo = new RouteRepository();
        $this->vehicleRepo = new VehicleRepository();
        $this->userRepo = new UserRepository();
    }
    
    public function dashboard() {
        $driverId = $this->session->get('user_id');

        // Buscar reservas do dia atual
        $todayReservations = $this->reservationRepo->getTodayReservations();

        // Buscar veículo do motorista
        $driverVehicles = $this->routeRepo->getDriverVehicles($driverId);

        // Buscar rotas ativas do motorista
        $activeRoutes = $this->routeRepo->getRoutesByDriver($driverId);

        $this->renderView('driver/dashboard', [
            'reservations' => $todayReservations,
            'vehicles' => $driverVehicles,
            'routes' => $activeRoutes
        ]);
    }

    public function checkin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $qrCodeData = $_POST['qr_data'] ?? '';

            // Processar QR Code e verificar reserva
            $result = $this->processQRCode($qrCodeData);

            if ($result['success']) {
                $this->session->set('success', 'Check-in realizado: ' . $result['message']);
            } else {
                $this->session->set('error', 'Erro no check-in: ' . $result['message']);
            }
        }

        $todayReservations = $this->reservationRepo->getTodayReservations();

        $this->renderView('driver/scan/checkin', [
            'reservations' => $todayReservations
        ]);
    }
    
    private function processQRCode($qrData) {
        // Implementar lógica de validação do QR Code
        // Verificar se o estudante tem reserva para o dia
        return ['success' => true, 'message' => 'Reserva válida'];
    }
    
    public function importReservations() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
            $file = $_FILES['csv_file'];

            if ($file['type'] == 'text/csv') {
                $result = $this->processCSV($file['tmp_name']);
                $this->session->set('success', "CSV processado: {$result['processed']} reservas");
            } else {
                $this->session->set('error', 'Por favor, envie um arquivo CSV válido');
            }
        }

        $this->renderView('driver/import');
    }

    public function selectVehicle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vehicleId = $_POST['vehicle_id'] ?? '';

            if (!empty($vehicleId)) {
                // Salvar veículo selecionado para o dia
                $this->session->set('selected_vehicle', $vehicleId);
                $this->session->set('success', 'Veículo selecionado para o dia!');
                $this->redirect('/driver/dashboard');
            } else {
                $this->session->set('error', 'Selecione um veículo válido');
            }
        }

        $driverId = $this->session->get('user_id');
        $vehicles = $this->routeRepo->getDriverVehicles($driverId);

        $this->renderView('driver/definition/select_vehicle', [
            'vehicles' => $vehicles
        ]);
    }

    public function passengersByRoute() {
        $routeId = $_GET['route_id'] ?? null;
        $date = $_GET['date'] ?? date('Y-m-d');

        if ($routeId) {
            $passengers = $this->reservationRepo->getPassengersByRoute($routeId, $date);
        } else {
            $passengers = [];
        }

        $routes = $this->routeRepo->getAllRoutes();

        $this->renderView('driver/passengers', [
            'passengers' => $passengers,
            'routes' => $routes,
            'selectedRoute' => $routeId,
            'selectedDate' => $date
        ]);
    }

    public function manageRoutes() {
        $driverId = $this->session->get('user_id');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'create') {
                $routeData = [
                    'name' => $_POST['name'] ?? '',
                    'origin' => $_POST['origin'] ?? '',
                    'destination' => $_POST['destination'] ?? '',
                    'departure_time' => $_POST['departure_time'] ?? '',
                    'max_capacity' => $_POST['max_capacity'] ?? 40,
                    'vehicle_id' => $_POST['vehicle_id'] ?? null,
                    'status' => 'Ativo'
                ];

                if ($this->routeRepo->createRoute($routeData)) {
                    $this->session->set('success', 'Rota criada com sucesso!');
                } else {
                    $this->session->set('error', 'Erro ao criar rota');
                }
            } elseif ($action === 'update') {
                $routeId = $_POST['route_id'] ?? '';
                $routeData = [
                    'name' => $_POST['name'] ?? '',
                    'origin' => $_POST['origin'] ?? '',
                    'destination' => $_POST['destination'] ?? '',
                    'departure_time' => $_POST['departure_time'] ?? '',
                    'max_capacity' => $_POST['max_capacity'] ?? 40,
                    'vehicle_id' => $_POST['vehicle_id'] ?? null
                ];

                if ($this->routeRepo->updateRoute($routeId, $routeData)) {
                    $this->session->set('success', 'Rota atualizada com sucesso!');
                } else {
                    $this->session->set('error', 'Erro ao atualizar rota');
                }
            }
        }

        $routes = $this->routeRepo->getRoutesByDriver($driverId);
        $vehicles = $this->routeRepo->getDriverVehicles($driverId);

        $this->renderView('driver/routes', [
            'routes' => $routes,
            'vehicles' => $vehicles
        ]);
    }

    public function attendanceReport() {
        $date = $_GET['date'] ?? date('Y-m-d');
        $routeId = $_GET['route_id'] ?? null;

        $reportData = $this->reservationRepo->getAttendanceReport($date, $routeId);

        $routes = $this->routeRepo->getAllRoutes();

        $this->renderView('driver/attendance_report', [
            'reportData' => $reportData,
            'routes' => $routes,
            'selectedDate' => $date,
            'selectedRoute' => $routeId
        ]);
    }

    public function communication() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $_POST['message'] ?? '';
            $priority = $_POST['priority'] ?? 'normal';

            if (!empty($message)) {
                // Aqui seria implementada a lógica para enviar mensagem para central
                $this->session->set('success', 'Mensagem enviada para central de operações!');
                $this->redirect('/driver/communication');
            } else {
                $this->session->set('error', 'Digite uma mensagem válida');
            }
        }
    }

    public function profile() {
        $userId = $this->session->get('user_id');
        $user = $this->userRepo->findById($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'emergency_contact' => $_POST['emergency_contact'] ?? ''
            ];

            // Verificar se senha foi fornecida para alteração
            if (!empty($_POST['password'])) {
                $userData['password'] = $_POST['password'];
            }

            if ($this->userRepo->updateUser($userId, $userData)) {
                $this->session->set('success', 'Perfil atualizado com sucesso!');
                $this->redirect('/driver/profile');
            } else {
                $this->session->set('error', 'Erro ao atualizar perfil');
            }
        }

        $this->renderView('driver/profile', [
            'user' => $user
        ]);
    }

    public function requests() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestType = $_POST['request_type'] ?? '';
            $description = $_POST['description'] ?? '';

            if (!empty($requestType) && !empty($description)) {
                // Aqui seria implementada a lógica para salvar solicitação
                $this->session->set('success', 'Solicitação enviada com sucesso!');
                $this->redirect('/driver/requests');
            } else {
                $this->session->set('error', 'Preencha todos os campos obrigatórios');
            }
        }
    }
    
    private function processCSV($filePath) {
        // Implementar processamento de CSV
        return ['processed' => 0, 'errors' => []];
    }
    
    public function setReturnRoute() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vehicleId = $_POST['vehicle_id'] ?? '';
            $routeDetails = $_POST['route_details'] ?? '';
            
            // Salvar rota de retorno
            $this->session->set('success', 'Rota de retorno definida com sucesso!');
            $this->redirect('/driver/return-routes');
        }
        
        $vehicles = $this->routeRepo->getDriverVehicles($this->session->get('user_id'));
        
        $this->renderView('driver/return_routes', [
            'vehicles' => $vehicles
        ]);
    }
    
    public function exportReports() {
        $reservations = $this->reservationRepo->getDriverReservations($this->session->get('user_id'));
        
        // Gerar CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="relatorio_reservas.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Data', 'Estudante', 'Matrícula', 'Status']);
        
        foreach ($reservations as $reservation) {
            fputcsv($output, [
                $reservation['date'],
                $reservation['student_name'],
                $reservation['matricula'],
                $reservation['status']
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    private function renderView($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../views/$view.php";
    }
}
?>