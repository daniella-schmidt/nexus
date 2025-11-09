<?php
require_once __DIR__ . '/../models/Database.php';

class ReservationRepository {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function createReservation($userId, $routeId, $date, $pickupPoint = null, $dropoffPoint = null, $startDate = null, $endDate = null, $daysOfWeek = null, $faculdade = null) {
        try {
            // Verificar capacidade
            $capacitySql = "SELECT COUNT(*) as current, r.max_capacity
                        FROM reservations res
                        JOIN routes r ON res.route_id = r.id
                        WHERE res.route_id = ? AND res.reservation_date = ? AND res.status = 'active'";
            $capacityStmt = $this->db->executeQuery($capacitySql, [$routeId, $date]);
            $capacity = $this->db->fetchOne($capacityStmt);

            if ($capacity && $capacity['current'] >= $capacity['max_capacity']) {
                error_log("Capacidade esgotada para rota $routeId na data $date");
                return false;
            }

            // Gerar código de confirmação único
            $confirmationCode = strtoupper(substr(md5(uniqid()), 0, 8));

            $sql = "INSERT INTO reservations (user_id, route_id, seat_id, reservation_date, status, qr_code, created_at, confirmation_code, confirmed_at, cancellation_reason, check_in_time, updated_at, pickup_point, dropoff_point, start_date, end_date, days_of_week, collage)
                VALUES (?, ?, NULL, ?, 'active', NULL, NOW(), ?, NULL, NULL, NULL, NOW(), ?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->executeQuery($sql, [
                $userId,
                $routeId,
                $date,
                $confirmationCode,
                $pickupPoint,
                $dropoffPoint,
                $startDate,
                $endDate,
                $daysOfWeek,
                $faculdade
            ]);
            
            return $stmt->rowCount() > 0;
            
        } catch (Exception $e) {
            error_log("Erro ao criar reserva: " . $e->getMessage());
            return false;
        }
    }

    public function getUserReservationForDate($userId, $date) {
        $sql = "SELECT * FROM reservations 
                WHERE user_id = ? AND reservation_date = ? AND status = 'active'";
        
        $stmt = $this->db->executeQuery($sql, [$userId, $date]);
        return $this->db->fetchOne($stmt);
    }
    
    public function cancelReservation($reservationId, $userId) {
        try {
            $sql = "UPDATE reservations SET status = 'cancelled', updated_at = NOW() 
                    WHERE id = ? AND user_id = ? AND status = 'active'";
            
            $stmt = $this->db->executeQuery($sql, [$reservationId, $userId]);
            $success = $stmt->rowCount() > 0;
            
            error_log("Cancelamento no BD - Reserva: $reservationId, User: $userId, Sucesso: " . ($success ? 'Sim' : 'Não'));
            
            return $success;
        } catch (Exception $e) {
            error_log("Erro ao cancelar reserva: " . $e->getMessage());
            return false;
        }
    }
    public function getDriverReservations($driverId) {
        $sql = "SELECT r.*, u.name as student_name, u.matricula, rt.name as route_name, rt.departure_time
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN routes rt ON r.route_id = rt.id
                WHERE rt.driver_id = ?
                ORDER BY r.reservation_date DESC";

        $stmt = $this->db->executeQuery($sql, [$driverId]);
        return $this->db->fetchAll($stmt);
    }
    
    public function getUserReservations($userId) {
        $sql = "SELECT r.*, rt.name as route_name, rt.departure_time
                FROM reservations r
                JOIN routes rt ON r.route_id = rt.id
                WHERE r.user_id = ?
                ORDER BY r.reservation_date DESC";

        $stmt = $this->db->executeQuery($sql, [$userId]);
        return $this->db->fetchAll($stmt);
    }
    
    public function getTodayReservations() {
        $sql = "SELECT r.*, u.name as student_name, u.matricula, rt.name as route_name
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN routes rt ON r.route_id = rt.id
                WHERE DATE(r.created_at) = CURDATE() AND r.status = 'active'
                ORDER BY rt.departure_time, u.name";
        
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }

    public function getPassengersByRoute($routeId, $date) {
        $sql = "SELECT u.name as student_name, u.matricula, u.curso, r.status, r.created_at
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                WHERE r.route_id = ? AND r.reservation_date = ? AND r.status = 'active'
                ORDER BY u.name";

        $stmt = $this->db->executeQuery($sql, [$routeId, $date]);
        return $this->db->fetchAll($stmt);
    }

    public function getAttendanceReport($date, $routeId = null) {
        $params = [$date];
        $routeCondition = "";

        if ($routeId) {
            $routeCondition = " AND r.route_id = ?";
            $params[] = $routeId;
        }

        $sql = "SELECT
                    rt.name as route_name,
                    rt.departure_time,
                    COUNT(CASE WHEN r.status = 'active' THEN 1 END) as present_count,
                    COUNT(CASE WHEN r.status = 'cancelled' THEN 1 END) as absent_count,
                    COUNT(r.id) as total_reservations,
                    ROUND(
                        (COUNT(CASE WHEN r.status = 'active' THEN 1 END) * 100.0) / NULLIF(COUNT(r.id), 0),
                        2
                    ) as attendance_rate
                FROM reservations r
                JOIN routes rt ON r.route_id = rt.id
                WHERE r.reservation_date = ? {$routeCondition}
                GROUP BY rt.id, rt.name, rt.departure_time
                ORDER BY rt.departure_time";

        $stmt = $this->db->executeQuery($sql, $params);
        return $this->db->fetchAll($stmt);
    }
}
?>