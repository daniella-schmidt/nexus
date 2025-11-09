<?php
// File: app/controllers/NotificationStreamController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../repositories/NotificationRepository.php';

class NotificationStreamController extends BaseController {
    private $notificationRepo;

    public function __construct() {
        parent::__construct();
        $this->notificationRepo = new NotificationRepository();
    }

    public function stream() {
        // Configurar headers para SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');

        // Verificar autenticação
        $userId = $this->session->get('user_id');
        if (!$userId) {
            echo "data: " . json_encode(['error' => 'Não autenticado']) . "\n\n";
            flush();
            exit();
        }

        // Obter último ID de notificação conhecido
        $lastEventId = $_SERVER['HTTP_LAST_EVENT_ID'] ?? 0;
        
        // Loop para manter conexão aberta
        while (true) {
            // Verificar se conexão foi fechada
            if (connection_aborted()) {
                break;
            }

            // Buscar novas notificações
            $newNotifications = $this->getNewNotifications($userId, $lastEventId);
            
            if (!empty($newNotifications)) {
                foreach ($newNotifications as $notification) {
                    $lastEventId = max($lastEventId, $notification['id']);
                    
                    echo "event: new_notification\n";
                    echo "data: " . json_encode([
                        'id' => $notification['id'],
                        'title' => $notification['title'],
                        'message' => $notification['message'],
                        'type' => $notification['type'],
                        'created_at' => $notification['created_at'],
                        'unread_count' => $this->notificationRepo->getUnreadCount($userId)
                    ]) . "\n\n";
                    flush();
                }
            }

            // Aguardar antes da próxima verificação
            sleep(5); // Verificar a cada 5 segundos
        }
    }

    private function getNewNotifications($userId, $lastEventId) {
        $sql = "SELECT * FROM notifications 
                WHERE user_id = ? AND id > ? AND read_status = 0
                ORDER BY created_at DESC 
                LIMIT 10";
        
        try {
            $db = new Database();
            $stmt = $db->executeQuery($sql, [$userId, $lastEventId]);
            return $db->fetchAll($stmt);
        } catch (Exception $e) {
            error_log("Erro ao buscar notificações: " . $e->getMessage());
            return [];
        }
    }

    public function getUnreadCount() {
        $this->requireAuth();
        $userId = $this->session->get('user_id');
        $count = $this->notificationRepo->getUnreadCount($userId);
        
        $this->jsonResponse(['unread_count' => $count]);
    }
}
?>