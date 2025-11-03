<?php
// Herança: DashboardController herda de BaseController, reutilizando funcionalidades comuns.
// Justificativa: Evita duplicação de código de autenticação e redirecionamento, focando em lógica específica de dashboard.

// Agregação: DashboardController agrega repositórios para operações de dados.
// Justificativa: Utiliza ReservationRepository, RouteRepository e VehicleRepository como parte de sua implementação para exibir dados no dashboard.

require_once __DIR__ . '/BaseController.php';

class DashboardController extends BaseController {
    // Agregação: Propriedades privadas para repositórios, acessíveis apenas dentro da classe.
    // Justificativa: Permite composição de objetos, onde os repositórios são componentes usados para operações de dados.

    public function index() {
        $this->requireAuth();
        
        $userData = [
            'name' => $this->session->get('user_name'),
            'email' => $this->session->get('user_email'),
            'type' => $this->session->get('user_type'),
            'user_id' => $this->session->get('user_id'),
            'matricula' => $this->session->get('user_matricula'),
            'curso' => $this->session->get('user_curso')
        ];

        // Se for motorista, carregar dados específicos
        if ($userData['type'] === 'driver') {
            $driverId = $userData['user_id'];
            
            // Carregar repositórios
            $reservationRepo = new ReservationRepository();
            $routeRepo = new RouteRepository();
            $vehicleRepo = new VehicleRepository();
            
            // Buscar dados reais
            $reservations = $reservationRepo->getTodayReservations();
            $vehicles = $vehicleRepo->getAvailableVehicles();
            $routes = $routeRepo->getRoutesByDriver($driverId);
            
            $userData['reservations'] = $reservations ?? [];
            $userData['vehicles'] = $vehicles ?? [];
            $userData['routes'] = $routes ?? [];
        }
        
        $this->renderView('dashboard/index', $userData);
    }
    
    private function renderView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>