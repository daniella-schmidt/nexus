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
require_once __DIR__ . '/../repositories/NotificationRepository.php';

class DriverController extends BaseController {
    private $reservationRepo;
    private $routeRepo;
    private $vehicleRepo;
    private $userRepo;
    private $notificationRepo;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->reservationRepo = new ReservationRepository();
        $this->routeRepo = new RouteRepository();
        $this->vehicleRepo = new VehicleRepository();
        $this->userRepo = new UserRepository();
        $this->notificationRepo = new NotificationRepository();
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
        $vehicles = $this->vehicleRepo->getAllVehicles();

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
        $this->requireAuth();
        
        // Verificar se o usuário é realmente um motorista
        if ($this->session->get('user_type') !== 'driver') {
            $this->session->set('error', 'Acesso permitido apenas para motoristas');
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = trim($_POST['message'] ?? '');
            $priority = $_POST['priority'] ?? 'normal';

            if (!empty($message)) {
                // Enviar notificação para todos os estudantes do motorista
                $driverId = $this->session->get('user_id');
                
                error_log("Motorista $driverId tentando enviar mensagem: $message");
                
                $notificationsSent = $this->notificationRepo->createDriverMessageNotification(
                    $driverId, 
                    $message, 
                    $priority
                );

                if ($notificationsSent > 0) {
                    $this->session->set('success', "Mensagem enviada para $notificationsSent estudantes!");
                    error_log("Mensagem enviada com sucesso para $notificationsSent estudantes");
                } else {
                    $this->session->set('error', 'Nenhum estudante encontrado para receber a mensagem. Verifique se há reservas ativas.');
                    error_log("Nenhuma notificação foi enviada - sem estudantes encontrados");
                }
                
                $this->redirect('/driver/definition/communication');
            } else {
                $this->session->set('error', 'Digite uma mensagem válida');
            }
        }

        $this->renderView('driver/definition/communication');
    }

    private function updateSessionData($user) {
        $this->session->set('user_name', $user->getName());
        $this->session->set('user_email', $user->getEmail());
        $this->session->set('user_phone', $user->getPhone());
        $this->session->set('user_address', $user->getAddress());
        $this->session->set('user_emergency_contact', $user->getEmergencyContact());
        $this->session->set('user_profile_photo', $user->getProfilePhoto());
    }

    // E modifique a parte final do método profile():
    public function profile() {
        $userId = $this->session->get('user_id');
        $user = $this->userRepo->findById($userId);

        if (!$user) {
            $this->session->set('error', 'Usuário não encontrado');
            $this->redirect('/driver/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'emergency_contact' => $_POST['emergency_contact'] ?? ''
            ];

            // Handle profile photo upload
            if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_photo'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxSize = 2 * 1024 * 1024; // 2MB

                if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize) {
                    $uploadDir = __DIR__ . '/../../public/uploads/profile_photos/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $fileName = 'pf_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filePath = $uploadDir . $fileName;

                    if (move_uploaded_file($file['tmp_name'], $filePath)) {
                        $userData['profile_photo'] = '/uploads/profile_photos/' . $fileName;
                    } else {
                        $this->session->set('error', 'Erro ao salvar foto de perfil');
                        $this->redirect('/driver/definition/profile');
                        return;
                    }
                } else {
                    $this->session->set('error', 'Tipo de arquivo inválido ou tamanho excedido (máx. 2MB)');
                    $this->redirect('/driver/definition/profile');
                    return;
                }
            }

            // Verificar se senha foi fornecida para alteração
            if (!empty($_POST['password'])) {
                $userData['password'] = $_POST['password'];
            }

            if ($this->userRepo->updateUser($userId, $userData)) {
                // Update session data with new user information
                $updatedUser = $this->userRepo->findById($userId);
                if ($updatedUser) {
                    $this->updateSessionData($updatedUser);
                }
                $this->session->set('success', 'Perfil atualizado com sucesso!');
                $this->redirect('/driver/definition/profile');
            } else {
                $this->session->set('error', 'Erro ao atualizar perfil');
            }
        }

        // Convert User object to array for view
        $userData = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'address' => $user->getAddress(),
            'emergency_contact' => $user->getEmergencyContact(),
            'profile_photo' => $user->getProfilePhoto(),
            'user_type' => $user->getUserType(),
            'routes_count' => 0, // These would need to be calculated from database
            'passengers_count' => 0,
            'hours_worked' => 0,
            'punctuality' => 100
        ];

        $this->renderView('driver/definition/profile', [
            'user' => $userData
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