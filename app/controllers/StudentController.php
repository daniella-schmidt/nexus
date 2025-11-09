<?php
// Herança: StudentController herda de BaseController, reutilizando funcionalidades comuns.
// Justificativa: Evita duplicação de código de autenticação e redirecionamento, focando em lógica específica de estudantes.

// Agregação: StudentController agrega ReservationRepository e RouteRepository.
// Justificativa: Utiliza repositórios como parte de sua implementação para acesso a dados.

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../repositories/ReservationRepository.php';
require_once __DIR__ . '/../repositories/RouteRepository.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../repositories/NotificationRepository.php';

class StudentController extends BaseController {
    private $reservationRepo;
    private $routeRepo;
    private $userRepo;
    private $notificationRepo;

    public function __construct() {
        parent::__construct();
        $this->reservationRepo = new ReservationRepository();
        $this->routeRepo = new RouteRepository();
        $this->userRepo = new UserRepository();
        $this->notificationRepo = new NotificationRepository();
    }

    public function reservations() {
    $this->requireAuth();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
        $reservationId = $_POST['reservation_id'] ?? '';
        $userId = $this->session->get('user_id');

        if (!empty($reservationId)) {
            if ($this->reservationRepo->cancelReservation($reservationId, $userId)) {
                $this->session->set('success', 'Reserva cancelada com sucesso!');
            } else {
                $this->session->set('error', 'Erro ao cancelar reserva');
            }
        }
        
        $this->redirect('/student/definition/reservations');
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $faculdade = $_POST['faculdade'] ?? '';
        $startDate = $_POST['start_date'] ?? date('Y-m-d');
        $endDate = $_POST['end_date'] ?? date('Y-m-d');
        $pickupPoint = $_POST['pickup_point'] ?? '';
        $dropoffPoint = $_POST['dropoff_point'] ?? '';
        $daysOfWeek = $_POST['days_of_week'] ?? [];

        error_log("Dados recebidos - Faculdade: $faculdade, Start: $startDate, End: $endDate, Pickup: $pickupPoint, Dropoff: $dropoffPoint, Days: " . implode(',', $daysOfWeek));

        // Verificar se é antes das 16:35
        if (date('H:i') >= '16:35') {
            $this->session->set('error', 'Reservas só podem ser feitas até às 16:35');
            $this->redirect('/student/definition/reservations');
        }

        // Validações
        if (empty($faculdade)) {
            $this->session->set('error', 'Selecione uma faculdade');
            $this->redirect('/student/definition/reservations');
        }

        if (empty($pickupPoint) || empty($dropoffPoint)) {
            $this->session->set('error', 'Selecione os pontos de embarque e desembarque');
            $this->redirect('/student/definition/reservations');
        }

        if (empty($daysOfWeek)) {
            $this->session->set('error', 'Selecione pelo menos um dia da semana');
            $this->redirect('/student/definition/reservations');
        }

        // Validar datas
        if (strtotime($startDate) > strtotime($endDate)) {
            $this->session->set('error', 'Data final não pode ser anterior à data inicial');
            $this->redirect('/student/definition/reservations');
        }

        $userId = $this->session->get('user_id');
        $successfulReservations = 0;
        $failedReservations = 0;

        // Obter route_id baseado na faculdade
        $routeId = $this->getRouteIdByFaculdade($faculdade);

        // Mapeamento dos dias da semana (0=domingo, 1=segunda, etc)
        $dayMap = [
            'mon' => 1, // Monday
            'tue' => 2, // Tuesday
            'wed' => 3, // Wednesday
            'thu' => 4, // Thursday
            'fri' => 5, // Friday
            'sat' => 6, // Saturday
            'sun' => 0  // Sunday
        ];

        // Para cada dia selecionado, criar reservas para as próximas 4 semanas
        foreach ($daysOfWeek as $dayCode) {
            if (!isset($dayMap[$dayCode])) continue;

            $targetDayOfWeek = $dayMap[$dayCode];
            $currentDate = strtotime($startDate);

            // Encontrar a primeira ocorrência do dia após a data inicial
            $currentDayOfWeek = date('w', $currentDate);
            $daysToAdd = ($targetDayOfWeek - $currentDayOfWeek + 7) % 7;

            $firstOccurrence = strtotime("+$daysToAdd days", $currentDate);

            // Criar reservas para cada semana até a data final ou 4 semanas
            $week = 0;
            while ($week < 4) {
                $dateToReserve = date('Y-m-d', strtotime("+$week weeks", $firstOccurrence));

                // Parar se passou da data final
                if (strtotime($dateToReserve) > strtotime($endDate)) {
                    break;
                }

                // Verificar se já tem reserva para este dia
                $existingReservation = $this->reservationRepo->getUserReservationForDate($userId, $dateToReserve);
                if ($existingReservation) {
                    error_log("Reserva já existe para $dateToReserve");
                    $failedReservations++;
                    $week++;
                    continue;
                }

                // Criar reserva
                if ($this->reservationRepo->createReservation(
                    $userId,
                    $routeId,
                    $dateToReserve,
                    $pickupPoint,
                    $dropoffPoint,
                    $startDate,
                    $endDate,
                    implode(',', $daysOfWeek),
                    $faculdade
                )) {
                    error_log("Reserva criada com sucesso para $dateToReserve");
                    $successfulReservations++;
                } else {
                    error_log("Falha ao criar reserva para $dateToReserve");
                    $failedReservations++;
                }

                $week++;
            }
        }

        if ($successfulReservations > 0) {
            $message = "Reserva(s) criada(s) com sucesso! Total: $successfulReservations";
            if ($failedReservations > 0) {
                $message .= " (Falhas: $failedReservations - reservas já existentes ou vagas esgotadas)";
            }
            $this->session->set('success', $message);
        } else {
            $this->session->set('error', 'Erro ao criar reservas. Verifique se há vagas disponíveis ou conflitos existentes.');
        }

        $this->redirect('/student/definition/reservations');
    }

    $routes = $this->routeRepo->getAvailableRoutes();
    $userReservations = $this->reservationRepo->getUserReservations($this->session->get('user_id'));

    $this->renderView('student/definition/reservations', [
        'routes' => $routes,
        'reservations' => $userReservations
    ]);
}

    /**
     * Método auxiliar para obter route_id baseado na faculdade
     */
    private function getRouteIdByFaculdade($faculdade) {
        // Mapeamento baseado nas rotas existentes no banco
        $faculdadeRoutes = [
            'UNOESC' => 4, // Rota 'danoca'
            'UNIVERSIDADE_X' => 5, // Rota 'Biologia'
            'FACULDADE_Y' => 4 // Default para 'danoca'
        ];

        return $faculdadeRoutes[$faculdade] ?? 4; // Default para ID 4 se não encontrado
    }

    public function notifications() {
        $this->requireAuth();

        $userId = $this->session->get('user_id');

        // Buscar notificações do usuário
        $notifications = $this->notificationRepo->getUserNotifications($userId);

        // Contar notificações não lidas
        $unreadCount = $this->notificationRepo->getUnreadCount($userId);

        $this->renderView('student/notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function markNotificationAsRead() {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notificationId = $_POST['notification_id'] ?? '';
            $userId = $this->session->get('user_id');

            if ($this->notificationRepo->markAsRead($notificationId, $userId)) {
                $this->session->set('success', 'Notificação marcada como lida');
            } else {
                $this->session->set('error', 'Erro ao marcar notificação como lida');
            }
        }

        $this->redirect('/student/notifications');
    }

    public function deleteNotification() {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notificationId = $_POST['notification_id'] ?? '';
            $userId = $this->session->get('user_id');

            if ($this->notificationRepo->deleteNotification($notificationId, $userId)) {
                $this->session->set('success', 'Notificação excluída com sucesso');
            } else {
                $this->session->set('error', 'Erro ao excluir notificação');
            }
        }

        $this->redirect('/student/notifications');
    }

    public function markAllNotificationsAsRead() {
        $this->requireAuth();

        $userId = $this->session->get('user_id');

        if ($this->notificationRepo->markAllAsRead($userId)) {
            $this->session->set('success', 'Todas as notificações foram marcadas como lidas');
        } else {
            $this->session->set('error', 'Erro ao marcar notificações como lidas');
        }

        $this->redirect('/student/notifications');
    }

    public function profile() {
        $this->requireAuth();

        // SEMPRE carregar do banco para ter dados atualizados
        $userId = $this->session->get('user_id');
        $user = $this->userRepo->findById($userId);

        if (!$user) {
            $this->session->set('error', 'Usuário não encontrado');
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'matricula' => trim($_POST['matricula']),
                'curso' => trim($_POST['curso']),
                'phone' => trim($_POST['phone'] ?? ''),
                'emergency_contact' => trim($_POST['emergency_contact'] ?? '')
            ];

            // Processar upload de foto
            if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handlePhotoUpload($_FILES['profile_photo'], $user);

                if ($uploadResult['success']) {
                    $userData['profile_photo'] = $uploadResult['path'];
                } else {
                    $this->session->set('error', $uploadResult['error']);
                    $this->redirect('/student/definition/profile');
                }
            }

            // Atualizar no banco de dados
            if ($this->userRepo->updateUser($userId, $userData)) {
                // CRÍTICO: Recarregar usuário do banco após atualização
                $user = $this->userRepo->findById($userId);

                // Atualizar TODA a sessão com dados do banco
                $this->updateSessionData($user);

                $this->session->set('success', 'Dados atualizados com sucesso!');
            } else {
                $this->session->set('error', 'Erro ao atualizar dados no banco.');
            }

            $this->redirect('/student/definition/profile');
        }

        // Passar dados do usuário para a view
        $userData = $this->getUserDataArray($user);

        $this->renderView('student/definition/profile', $userData);
    }

    public function digitalCard() {
        $this->requireAuth();

        // SEMPRE carregar do banco para ter dados atualizados
        $userId = $this->session->get('user_id');
        $user = $this->userRepo->findById($userId);

        if (!$user) {
            $this->session->set('error', 'Usuário não encontrado');
            $this->redirect('/dashboard');
        }

        // Atualizar sessão com dados mais recentes
        $this->updateSessionData($user);

        // Preparar dados para a view
        $userData = $this->getUserDataArray($user);

        $this->renderView('student/card/digital_card', $userData);
    }

    /**
     * Processa upload de foto de perfil
     */
    private function handlePhotoUpload($file, $currentUser) {
        $uploadDir = __DIR__ . '/../../public/uploads/profile_photos/';

        // Criar diretório se não existir
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validar tipo de arquivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $file['type'];

        if (!in_array($fileType, $allowedTypes)) {
            return [
                'success' => false,
                'error' => 'Tipo de arquivo não permitido. Use JPEG, PNG ou GIF.'
            ];
        }

        // Validar tamanho (máximo 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            return [
                'success' => false,
                'error' => 'Arquivo muito grande. Tamanho máximo: 2MB.'
            ];
        }

        // Gerar nome único
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = uniqid('pf_') . '.' . $ext;
        $dest = $uploadDir . $newName;

        // Mover arquivo
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            $profilePath = '/uploads/profile_photos/' . $newName;

            // Deletar foto antiga se existir
            if ($currentUser && $currentUser->getProfilePhoto()) {
                $oldPhotoPath = __DIR__ . '/../../public' . $currentUser->getProfilePhoto();
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            return [
                'success' => true,
                'path' => $profilePath
            ];
        }

        return [
            'success' => false,
            'error' => 'Erro ao fazer upload da foto.'
        ];
    }

    /**
     * Atualiza dados da sessão com informações do usuário
     */
    private function updateSessionData($user) {
        $this->session->set('user_name', $user->getName());
        $this->session->set('user_email', $user->getEmail());
        $this->session->set('user_matricula', $user->getMatricula());
        $this->session->set('user_curso', $user->getCurso());
        $this->session->set('user_phone', $user->getPhone());
        $this->session->set('user_emergency_contact', $user->getEmergencyContact());
        $this->session->set('user_profile_photo', $user->getProfilePhoto());
    }

    /**
     * Converte objeto User em array para a view
     */
    private function getUserDataArray($user) {
        return [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'matricula' => $user->getMatricula(),
            'curso' => $user->getCurso(),
            'phone' => $user->getPhone(),
            'emergency_contact' => $user->getEmergencyContact(),
            'profile_photo' => $user->getProfilePhoto()
        ];
    }

    private function renderView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>
