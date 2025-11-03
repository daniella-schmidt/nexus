<?php
require_once '../models/Database.php';

class ReportRepository {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function generateReport($type) {
        switch ($type) {
            case 'reservations':
                return $this->getReservationsReport();
            case 'vehicles':
                return $this->getVehiclesReport();
            case 'routes':
                return $this->getRoutesReport();
            case 'students':
                return $this->getStudentsReport();
            default:
                return $this->getGeneralReport();
        }
    }
    
    private function getGeneralReport() {
        $sql = "SELECT 
                    COUNT(*) as total_reservations,
                    COUNT(DISTINCT user_id) as unique_students,
                    COUNT(DISTINCT route_id) as active_routes,
                    AVG(used_capacity) as avg_occupancy
                FROM (
                    SELECT 
                        r.route_id,
                        r.user_id,
                        (SELECT COUNT(*) FROM reservations res2 
                         WHERE res2.route_id = r.route_id 
                         AND res2.date = r.date 
                         AND res2.status = 'confirmed') as used_capacity
                    FROM reservations r
                    WHERE r.date >= DATEADD(day, -30, GETDATE())
                    AND r.status = 'confirmed'
                ) as subquery";
        
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }
    
    private function getReservationsReport() {
        $sql = "SELECT 
                    CAST(r.date AS DATE) as reservation_date,
                    rt.name as route_name,
                    COUNT(*) as total_reservations,
                    COUNT(CASE WHEN r.status = 'confirmed' THEN 1 END) as confirmed,
                    COUNT(CASE WHEN r.status = 'cancelled' THEN 1 END) as cancelled
                FROM reservations r
                JOIN routes rt ON r.route_id = rt.id
                WHERE r.date >= DATEADD(day, -30, GETDATE())
                GROUP BY CAST(r.date AS DATE), rt.name
                ORDER BY reservation_date DESC, rt.name";
        
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }
    
    private function getVehiclesReport() {
        $sql = "SELECT 
                    v.plate,
                    v.model,
                    v.capacity,
                    u.name as driver_name,
                    COUNT(r.id) as total_trips,
                    AVG(used_capacity.occupancy) as avg_occupancy
                FROM vehicles v
                LEFT JOIN users u ON v.driver_id = u.id
                LEFT JOIN routes rt ON rt.vehicle_id = v.id
                LEFT JOIN reservations r ON r.route_id = rt.id AND r.status = 'confirmed'
                LEFT JOIN (
                    SELECT 
                        rt.vehicle_id,
                        COUNT(res.id) as occupancy
                    FROM reservations res
                    JOIN routes rt ON res.route_id = rt.id
                    WHERE res.date >= DATEADD(day, -30, GETDATE())
                    AND res.status = 'confirmed'
                    GROUP BY rt.vehicle_id, res.date
                ) as used_capacity ON used_capacity.vehicle_id = v.id
                WHERE v.active = 1
                GROUP BY v.id, v.plate, v.model, v.capacity, u.name
                ORDER BY total_trips DESC";
        
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }
    
    private function getRoutesReport() {
        $sql = "SELECT 
                    rt.name as route_name,
                    rt.schedule,
                    v.model as vehicle_model,
                    rt.max_capacity,
                    AVG(daily_usage.reservations) as avg_daily_usage,
                    MAX(daily_usage.reservations) as max_daily_usage
                FROM routes rt
                LEFT JOIN vehicles v ON rt.vehicle_id = v.id
                LEFT JOIN (
                    SELECT 
                        route_id,
                        COUNT(*) as reservations
                    FROM reservations
                    WHERE date >= DATEADD(day, -30, GETDATE())
                    AND status = 'confirmed'
                    GROUP BY route_id, date
                ) as daily_usage ON daily_usage.route_id = rt.id
                WHERE rt.active = 1
                GROUP BY rt.id, rt.name, rt.schedule, v.model, rt.max_capacity
                ORDER BY avg_daily_usage DESC";
        
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }
    
    private function getStudentsReport() {
        $sql = "SELECT 
                    u.name as student_name,
                    u.email,
                    u.matricula,
                    u.curso,
                    COUNT(r.id) as total_reservations,
                    COUNT(CASE WHEN r.status = 'confirmed' THEN 1 END) as confirmed_reservations,
                    COUNT(CASE WHEN r.status = 'cancelled' THEN 1 END) as cancelled_reservations,
                    MIN(r.date) as first_reservation,
                    MAX(r.date) as last_reservation
                FROM users u
                LEFT JOIN reservations r ON u.id = r.user_id
                WHERE u.user_type = 'student' 
                AND u.active = 1
                GROUP BY u.id, u.name, u.email, u.matricula, u.curso
                ORDER BY total_reservations DESC";
        
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }
    
    public function getDailyOccupancy($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $sql = "SELECT 
                    rt.name as route_name,
                    rt.schedule,
                    v.model as vehicle_model,
                    rt.max_capacity,
                    COUNT(r.id) as current_reservations,
                    CAST(COUNT(r.id) * 100.0 / rt.max_capacity AS DECIMAL(5,2)) as occupancy_rate
                FROM routes rt
                LEFT JOIN vehicles v ON rt.vehicle_id = v.id
                LEFT JOIN reservations r ON r.route_id = rt.id 
                    AND r.date = ? 
                    AND r.status = 'confirmed'
                WHERE rt.active = 1
                GROUP BY rt.id, rt.name, rt.schedule, v.model, rt.max_capacity
                ORDER BY rt.schedule";
        
        $stmt = $this->db->executeQuery($sql, [$date]);
        return $this->db->fetchAll($stmt);
    }
}
?>