-- =========================================
-- SISTEMA DE TRANSPORTE UNIVERSITÁRIO - NEXUS
-- Banco de dados MySQL - Versão Convertida
-- Versão: 2.1
-- =========================================
-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS nexus
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE nexus;

-- =========================================
-- TABELAS PRINCIPAIS
-- =========================================

-- 1. USERS - Dados dos usuários (estudantes, motoristas, admins)
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    matricula VARCHAR(20) UNIQUE NULL,
    curso VARCHAR(50) NULL,
    phone VARCHAR(15) NULL,
    address VARCHAR(200) NULL,
    emergency_contact VARCHAR(15) NULL,
    password_hash VARCHAR(255) NOT NULL,
    user_type ENUM('student', 'driver', 'admin') NOT NULL,
    profile_photo VARCHAR(255) NULL,
    email_verified BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. SCHEDULES - Turnos disponíveis (manhã, tarde, noite)
DROP TABLE IF EXISTS schedules;
CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    departure_time TIME NOT NULL,
    return_time TIME NOT NULL,
    description VARCHAR(200) NULL,
    max_capacity_multiplier DECIMAL(3,2) DEFAULT 1.0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. VEHICLES - Informações dos veículos
DROP TABLE IF EXISTS vehicles;
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plate VARCHAR(10) NOT NULL UNIQUE,
    model VARCHAR(50) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    capacity INT NOT NULL,
    status ENUM('Ativo', 'Inativo', 'Em Manutenção') DEFAULT 'Ativo',
    last_maintenance DATE NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. SEATS - Layout visual dos assentos
DROP TABLE IF EXISTS seats;
CREATE TABLE seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    seat_number VARCHAR(5) NOT NULL,
    position_row INT NOT NULL,
    position_column CHAR(1) NOT NULL,
    seat_type ENUM('regular', 'priority', 'disabled') DEFAULT 'regular',
    x_position INT NULL,
    y_position INT NULL,
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    UNIQUE KEY UQ_VehicleSeat (vehicle_id, seat_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. ROUTES - Rotas e horários
DROP TABLE IF EXISTS routes;
CREATE TABLE routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    origin VARCHAR(100) NULL,
    destination VARCHAR(100) NOT NULL,
    route_date DATE NOT NULL,
    schedule_id INT NOT NULL,
    departure_time TIME NOT NULL,
    pickup_points TEXT NOT NULL,
    max_capacity INT NOT NULL,
    current_occupancy INT DEFAULT 0,
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'scheduled',
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (schedule_id) REFERENCES schedules(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. RESERVATIONS - Reservas ativas
DROP TABLE IF EXISTS reservations;
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    route_id INT NOT NULL,
    seat_id INT NULL,
    reservation_date DATE NOT NULL,
    start_date DATE NULL,
    end_date DATE NULL,
    days_of_week VARCHAR(50) NULL, -- Ex: 'mon,tue,wed,thu,fri'
    pickup_point VARCHAR(100) NULL,
    dropoff_point VARCHAR(100) NULL,
    status ENUM('active', 'cancelled', 'completed', 'waiting', 'no_show') NOT NULL DEFAULT 'active',
    qr_code VARCHAR(100) UNIQUE NULL,
    confirmation_code VARCHAR(10) NULL,
    confirmed_at TIMESTAMP NULL,
    check_in_time TIMESTAMP NULL,
    cancellation_reason VARCHAR(200) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (route_id) REFERENCES routes(id),
    FOREIGN KEY (seat_id) REFERENCES seats(id),
    UNIQUE KEY UQ_UserRouteDate (user_id, route_id, reservation_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. QR_CODES - Validação de presença
DROP TABLE IF EXISTS qr_codes;
CREATE TABLE qr_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    code VARCHAR(100) UNIQUE NOT NULL,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    status ENUM('active', 'used', 'expired', 'cancelled') DEFAULT 'active',
    scanned_by INT NULL,
    scan_location VARCHAR(100) NULL,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id),
    FOREIGN KEY (scanned_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. WAITING_LIST - Fila de espera
DROP TABLE IF EXISTS waiting_list;
CREATE TABLE waiting_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    route_id INT NOT NULL,
    position INT NOT NULL,
    priority_score INT DEFAULT 0,
    notification_sent BOOLEAN DEFAULT FALSE,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (route_id) REFERENCES routes(id),
    UNIQUE KEY UQ_UserRouteWaiting (user_id, route_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. ATTENDANCE - Histórico de presença
DROP TABLE IF EXISTS attendance;
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    user_id INT NOT NULL,
    route_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent', 'cancelled', 'late', 'early') NOT NULL,
    check_in_time TIMESTAMP NULL,
    check_out_time TIMESTAMP NULL,
    notes VARCHAR(500) NULL,
    recorded_by INT NULL,
    rating INT NULL CHECK (rating >= 1 AND rating <= 5),
    feedback VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (route_id) REFERENCES routes(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. NOTIFICATIONS - Avisos automáticos
DROP TABLE IF EXISTS notifications;
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    message VARCHAR(500) NOT NULL,
    type ENUM('info', 'warning', 'success', 'reservation', 'cancellation', 'waiting_list', 'system') NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'low',
    read_status BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    action_url VARCHAR(200) NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. SYSTEM_LOGS - Logs do sistema
DROP TABLE IF EXISTS system_logs;
CREATE TABLE system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    description VARCHAR(500) NULL,
    table_affected VARCHAR(50) NULL,
    record_id INT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(500) NULL,
    severity ENUM('debug', 'info', 'warning', 'error', 'critical') DEFAULT 'info',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. MAINTENANCE_RECORDS - Registros de manutenção
DROP TABLE IF EXISTS maintenance_records;
CREATE TABLE maintenance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    maintenance_type ENUM('preventive', 'corrective', 'inspection') NOT NULL,
    description VARCHAR(500) NOT NULL,
    cost DECIMAL(10,2) NULL,
    maintenance_date DATE NOT NULL,
    next_maintenance_date DATE NULL,
    technician VARCHAR(100) NULL,
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- ÍNDICES PARA PERFORMANCE
-- =========================================

-- Índices para users
CREATE INDEX IX_users_email ON users(email);
CREATE INDEX IX_users_matricula ON users(matricula);
CREATE INDEX IX_users_type ON users(user_type);
CREATE INDEX IX_users_active ON users(active);

-- Índices para routes
CREATE INDEX IX_routes_date ON routes(route_date);
CREATE INDEX IX_routes_schedule ON routes(schedule_id);
CREATE INDEX IX_routes_status ON routes(status);
CREATE INDEX IX_routes_active ON routes(active);

-- Índices para reservations
CREATE INDEX IX_reservations_user ON reservations(user_id);
CREATE INDEX IX_reservations_route ON reservations(route_id);
CREATE INDEX IX_reservations_date ON reservations(reservation_date);
CREATE INDEX IX_reservations_status ON reservations(status);
CREATE INDEX IX_reservations_qr_code ON reservations(qr_code);

-- Índices para attendance
CREATE INDEX IX_attendance_date ON attendance(attendance_date);
CREATE INDEX IX_attendance_user ON attendance(user_id);
CREATE INDEX IX_attendance_status ON attendance(status);

-- Índices para notifications
CREATE INDEX IX_notifications_user_read ON notifications(user_id, read_status);
CREATE INDEX IX_notifications_created ON notifications(created_at);

-- Índices para waiting_list
CREATE INDEX IX_waiting_list_route ON waiting_list(route_id);
CREATE INDEX IX_waiting_list_position ON waiting_list(route_id, position);

-- =========================================
-- VIEWS ÚTEIS PARA RELATÓRIOS
-- =========================================

-- View para reservas ativas com detalhes
DROP VIEW IF EXISTS v_active_reservations;
CREATE VIEW v_active_reservations AS
SELECT 
    r.id,
    u.name AS student_name,
    u.email,
    u.matricula,
    u.curso,
    u.phone,
    rt.name AS route_name,
    rt.origin,
    rt.destination,
    s.name AS schedule_name,
    s.departure_time,
    s.return_time,
    st.seat_number,
    r.reservation_date,
    r.status,
    r.confirmation_code,
    r.created_at
FROM reservations r
JOIN users u ON r.user_id = u.id
JOIN routes rt ON r.route_id = rt.id
JOIN schedules s ON rt.schedule_id = s.id
LEFT JOIN seats st ON r.seat_id = st.id
WHERE r.status IN ('active', 'waiting')
  AND rt.active = TRUE
  AND u.active = TRUE;

-- View para estatísticas de presença
DROP VIEW IF EXISTS v_attendance_stats;
CREATE VIEW v_attendance_stats AS
SELECT 
    u.id AS user_id,
    u.name,
    u.matricula,
    u.curso,
    COUNT(*) AS total_reservations,
    SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) AS present_count,
    SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) AS absent_count,
    SUM(CASE WHEN a.status = 'late' THEN 1 ELSE 0 END) AS late_count,
    SUM(CASE WHEN a.status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_count,
    CAST(
        (SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(*), 0))
        AS DECIMAL(5,2)
    ) AS attendance_percentage,
    AVG(CAST(a.rating AS DECIMAL(3,2))) AS average_rating
FROM users u
JOIN reservations r ON u.id = r.user_id
JOIN attendance a ON r.id = a.reservation_id
WHERE u.user_type = 'student'
GROUP BY u.id, u.name, u.matricula, u.curso;

-- View para ocupação de rotas
DROP VIEW IF EXISTS v_route_occupancy;
CREATE VIEW v_route_occupancy AS
SELECT 
    r.id AS route_id,
    r.name AS route_name,
    r.origin,
    r.destination,
    r.route_date,
    s.name AS schedule_name,
    s.departure_time,
    v.plate AS vehicle_plate,
    v.model AS vehicle_model,
    v.capacity AS max_capacity,
    r.current_occupancy,
    CAST((r.current_occupancy * 100.0 / v.capacity) AS DECIMAL(5,2)) AS occupancy_percentage,
    (v.capacity - r.current_occupancy) AS available_seats,
    r.status
FROM routes r
JOIN schedules s ON r.schedule_id = s.id
LEFT JOIN vehicles v ON r.id = v.id
WHERE r.route_date >= CURDATE()
AND r.status IN ('scheduled', 'in_progress')
AND r.active = TRUE;

-- View para dashboard administrativo
DROP VIEW IF EXISTS v_admin_dashboard;
CREATE VIEW v_admin_dashboard AS
SELECT 
    (SELECT COUNT(*) FROM users WHERE user_type = 'student' AND active = TRUE) AS total_students,
    (SELECT COUNT(*) FROM users WHERE user_type = 'driver' AND active = TRUE) AS total_drivers,
    (SELECT COUNT(*) FROM vehicles WHERE status = 'Ativo') AS total_vehicles,
    (SELECT COUNT(*) FROM routes WHERE route_date = CURDATE() AND active = TRUE) AS today_routes,
    (SELECT COUNT(*) FROM reservations WHERE reservation_date = CURDATE() AND status = 'active') AS today_reservations,
    (SELECT COUNT(*) FROM reservations WHERE reservation_date = CURDATE() AND status = 'cancelled') AS today_cancellations,
    (SELECT AVG(CAST(current_occupancy AS DECIMAL) / CAST(max_capacity AS DECIMAL) * 100) 
     FROM routes 
     WHERE route_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND active = TRUE) AS avg_occupancy_week,
    (SELECT COUNT(*) FROM maintenance_records WHERE status IN ('scheduled', 'in_progress')) AS pending_maintenance,
    (SELECT COUNT(*) FROM notifications WHERE read_status = FALSE AND created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)) AS unread_notifications_today;

-- =========================================
-- INSERÇÃO DE DADOS INICIAIS
-- =========================================

-- Inserir schedules (turnos)
INSERT INTO schedules (name, departure_time, return_time, description) VALUES
('Manhã', '07:00', '12:00', 'Turno da manhã'),
('Tarde', '13:00', '18:00', 'Turno da tarde'),
('Noite', '19:00', '23:00', 'Turno da noite');

-- Inserir usuário admin padrão
INSERT INTO users (name, email, password_hash, user_type) VALUES
('Administrador', 'admin@nexus.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');