<?php
// Herança: RouteController herda de BaseController, reutilizando funcionalidades comuns.
// Justificativa: Evita duplicação de código de autenticação e redirecionamento, focando em lógica específica de gerenciamento de rotas.

// Agregação: RouteController agrega Route para operações de banco de dados.
// Justificativa: Utiliza Route como parte de sua implementação para acesso a dados de rotas.

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Route.php';

class RouteController extends BaseController {
    private $route;

    public function __construct() {
        parent::__construct();
        $this->route = new Route();
    }

    public function index() {
        $this->requireAdmin();
        $routes = $this->route->getAll();
        require __DIR__ . '/../views/manager/routes/index.php';
    }

    public function create() {
        $this->requireAdmin();
        require __DIR__ . '/../views/manager/routes/create.php';
    }

    public function store() {
        $this->requireAdmin();
        
        // DEBUG: Log dos dados recebidos
        error_log("=== DADOS DO FORMULÁRIO ROTA ===");
        error_log(print_r($_POST, true));
        
        // Processar dias da semana
        $daysOfWeek = isset($_POST['days_of_week']) && is_array($_POST['days_of_week']) 
            ? implode(',', $_POST['days_of_week']) 
            : '';

        // Determinar status e active baseado no formulário
        $status = $_POST['status'] ?? 'Ativo';
        $active = ($status === 'Ativo') ? 1 : 0;
        
        // Mapear os dados do formulário para a estrutura correta da tabela
        $routeData = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'origin' => trim($_POST['departure_location'] ?? ''),
            'destination' => trim($_POST['arrival_location'] ?? ''),
            'route_date' => date('Y-m-d'), // Data atual como padrão
            'schedule_id' => 1, // Valor padrão
            'departure_time' => $_POST['departure_time'] ?? '',
            'arrival_time' => $_POST['arrival_time'] ?? '',
            'pickup_points' => 'Pontos de parada não informados',
            'max_capacity' => 40, // Capacidade padrão
            'current_occupancy' => 0,
            'status' => $status,
            'active' => $active,
            'days_of_week' => $daysOfWeek
        ];

        // DEBUG: Log dos dados processados
        error_log("=== DADOS PROCESSADOS ===");
        error_log(print_r($routeData, true));

        // Validação básica
        $errors = [];
        if (empty($routeData['name'])) $errors[] = "Nome é obrigatório";
        if (empty($routeData['origin'])) $errors[] = "Local de partida é obrigatório";
        if (empty($routeData['destination'])) $errors[] = "Local de chegada é obrigatório";
        if (empty($routeData['departure_time'])) $errors[] = "Horário de partida é obrigatório";
        if (empty($routeData['arrival_time'])) $errors[] = "Horário de chegada é obrigatório";
        if (empty($daysOfWeek)) $errors[] = "Pelo menos um dia da semana deve ser selecionado";

        if (!empty($errors)) {
            error_log("Erros de validação: " . implode(', ', $errors));
            $_SESSION['error'] = 'Erro de validação: ' . implode(', ', $errors);
            header('Location: /nexus/manager/routes/create');
            exit;
        }

        try {
            $result = $this->route->create($routeData);
            
            if ($result) {
                error_log("Rota criada com sucesso. ID: " . $result);
                $_SESSION['success'] = 'Rota criada com sucesso!';
                header('Location: /nexus/manager/routes');
                exit;
            } else {
                error_log("Falha ao criar rota - retorno falso");
                $_SESSION['error'] = 'Erro ao criar rota. Verifique os dados.';
                header('Location: /nexus/manager/routes/create');
                exit;
            }
        } catch (Exception $e) {
            error_log("EXCEÇÃO ao criar rota: " . $e->getMessage());
            $_SESSION['error'] = 'Erro no sistema: ' . $e->getMessage();
            header('Location: /nexus/manager/routes/create');
            exit;
        }
    }

    public function edit($id) {
        $this->requireAdmin();
        $route = $this->route->getById($id);
        if (!$route) {
            $_SESSION['error'] = 'Rota não encontrada.';
            header('Location: /nexus/manager/routes');
            exit;
        }
        require __DIR__ . '/../views/manager/routes/edit.php';
    }

    public function update($id) {
        $this->requireAdmin();
        
        // Processar dias da semana
        $daysOfWeek = isset($_POST['days_of_week']) && is_array($_POST['days_of_week']) 
            ? implode(',', $_POST['days_of_week']) 
            : '';

        // Determinar status e active
        $status = $_POST['status'] ?? 'Ativo';
        $active = ($status === 'Ativo') ? 1 : 0;
        
        $routeData = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'origin' => trim($_POST['departure_location'] ?? ''),
            'destination' => trim($_POST['arrival_location'] ?? ''),
            'route_date' => date('Y-m-d'),
            'schedule_id' => 1,
            'departure_time' => $_POST['departure_time'] ?? '',
            'arrival_time' => $_POST['arrival_time'] ?? '',
            'pickup_points' => 'Pontos de parada não informados',
            'max_capacity' => 40,
            'status' => $status,
            'active' => $active,
            'days_of_week' => $daysOfWeek
        ];

        // Validação
        $errors = [];
        if (empty($routeData['name'])) $errors[] = "Nome é obrigatório";
        if (empty($routeData['origin'])) $errors[] = "Local de partida é obrigatório";
        if (empty($routeData['destination'])) $errors[] = "Local de chegada é obrigatório";
        if (empty($routeData['departure_time'])) $errors[] = "Horário de partida é obrigatório";
        if (empty($routeData['arrival_time'])) $errors[] = "Horário de chegada é obrigatório";
        if (empty($daysOfWeek)) $errors[] = "Pelo menos um dia da semana deve ser selecionado";

        if (!empty($errors)) {
            $_SESSION['error'] = 'Erro de validação: ' . implode(', ', $errors);
            header('Location: /nexus/manager/routes/edit/' . $id);
            exit;
        }

        if ($this->route->update($id, $routeData)) {
            $_SESSION['success'] = 'Rota atualizada com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao atualizar rota.';
        }
        header('Location: /nexus/manager/routes');
        exit;
    }

    public function delete($id) {
        $this->requireAdmin();
        
        if ($this->route->delete($id)) {
            $_SESSION['success'] = 'Rota removida com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao remover rota.';
        }
        header('Location: /nexus/manager/routes');
        exit;
    }
}