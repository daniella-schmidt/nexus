<?php
require_once __DIR__ . '/../models/Database.php';

// Encapsulamento: Propriedade privada $db para proteger acesso ao banco de dados.
// Justificativa: Evita exposição direta da conexão, centralizando operações através de métodos públicos.
class NotificationRepository {
    private $db;

    // Agregação: Instancia Database para operações de banco.
    // Justificativa: Permite reutilização da classe Database sem herança, facilitando manutenção.
    public function __construct() {
        $this->db = new Database();
    }

    public function createNotification($data) {
        $sql = "INSERT INTO notifications (user_id, title, message, type, priority, action_url, expires_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $data['user_id'],
            $data['title'],
            $data['message'],
            $data['type'],
            $data['priority'] ?? 'low',
            $data['action_url'] ?? null,
            $data['expires_at'] ?? null
        ];
        $stmt = $this->db->executeQuery($sql, $params);
        return $stmt->rowCount() > 0;
    }

    public function createDriverMessageNotification($driverId, $message, $priority = 'normal') {
        try {
            // Buscar todos os estudantes ativos (independente de reservas)
            $sql = "SELECT id, name
                    FROM users
                    WHERE user_type = 'student'
                    AND active = 1";

            $stmt = $this->db->executeQuery($sql);
            $students = $this->db->fetchAll($stmt);

            error_log("Motorista $driverId enviando mensagem para " . count($students) . " estudantes ativos");

            $notificationsCreated = 0;
            foreach ($students as $student) {
                $notificationData = [
                    'user_id' => $student['id'],
                    'title' => 'Mensagem do Motorista',
                    'message' => $message,
                    'type' => 'info',
                    'priority' => $priority,
                    'action_url' => '/student/notifications'
                ];

                if ($this->createNotification($notificationData)) {
                    $notificationsCreated++;
                    error_log("Notificação criada para estudante: " . $student['id'] . " - " . $student['name']);
                } else {
                    error_log("Falha ao criar notificação para estudante: " . $student['id']);
                }
            }

            error_log("Total de notificações criadas: $notificationsCreated");
            return $notificationsCreated;

        } catch (Exception $e) {
            error_log("Erro ao criar notificação do motorista: " . $e->getMessage());
            return 0;
        }
    }

    public function getUserNotifications($userId, $limit = 50) {
        $sql = "SELECT * FROM notifications
                WHERE user_id = ?
                ORDER BY created_at DESC
                LIMIT ?";
        $stmt = $this->db->executeQuery($sql, [$userId, $limit]);
        return $this->db->fetchAll($stmt);
    }

    public function getUnreadCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM notifications
                WHERE user_id = ? AND read_status = 0";
        $stmt = $this->db->executeQuery($sql, [$userId]);
        $result = $this->db->fetchOne($stmt);
        return $result['count'] ?? 0;
    }

    public function markAsRead($notificationId, $userId) {
        $sql = "UPDATE notifications
                SET read_status = 1, read_at = NOW()
                WHERE id = ? AND user_id = ?";
        $stmt = $this->db->executeQuery($sql, [$notificationId, $userId]);
        return $stmt->rowCount() > 0;
    }

    public function markAllAsRead($userId) {
        $sql = "UPDATE notifications
                SET read_status = 1, read_at = NOW()
                WHERE user_id = ? AND read_status = 0";
        $stmt = $this->db->executeQuery($sql, [$userId]);
        return $stmt->rowCount() > 0;
    }

    public function deleteNotification($notificationId, $userId) {
        $sql = "DELETE FROM notifications WHERE id = ? AND user_id = ?";
        $stmt = $this->db->executeQuery($sql, [$notificationId, $userId]);
        return $stmt->rowCount() > 0;
    }

    public function getRecentNotifications($userId, $days = 7) {
        $sql = "SELECT * FROM notifications
                WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                ORDER BY created_at DESC";
        $stmt = $this->db->executeQuery($sql, [$userId, $days]);
        return $this->db->fetchAll($stmt);
    }
}
?>
