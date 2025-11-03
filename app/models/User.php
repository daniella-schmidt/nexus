<?php
// Herança: Classe User herda de Database, reutilizando funcionalidades de conexão e operações básicas.
// Justificativa: Evita duplicação de código de banco de dados, permitindo que User foque em lógica específica de usuário.

// Herança: User herda de Database, agregando funcionalidades de conexão e operações básicas.
// Justificativa: User utiliza Database como base, mas Database pode existir independentemente.

// Polimorfismo: Métodos como save() podem ser implementados de forma diferente dependendo do contexto.
// Justificativa: Permite que User sobrescreva comportamentos herdados para lógica específica de usuário.

// Encapsulamento: Propriedades privadas protegem dados sensíveis como senha.
// Justificativa: Impede acesso direto aos dados, forçando uso de getters e setters.

// Visibilidade de Propriedades e Métodos: Métodos públicos para operações externas, privados para internas.
// Justificativa: Controla o acesso às funcionalidades da classe.

require_once 'Database.php';

class User extends Database {
    // Método para executar queries diretas
    protected function query($sql, $params = []) {
        $stmt = parent::executeQuery($sql, $params);
        return $stmt;
    }
    
    protected function fetchAssoc($stmt) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllDrivers() {
        $sql = "SELECT id, name FROM users WHERE user_type = 'driver' AND active = 1 ORDER BY name";
        try {
            $stmt = $this->executeQuery($sql);
            return $this->fetchAll($stmt);
        } catch (PDOException $e) {
            error_log("Erro ao buscar motoristas: " . $e->getMessage());
            return [];
        }
    }
    
    public function adminExists() {
        $stmt = $this->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'admin'");
        $row = $this->fetchAssoc($stmt);
        return $row['count'] > 0;
    }
    private $id;
    private $name;
    private $email;
    private $matricula;
    private $curso;
    private $phone;
    private $address;
    private $emergencyContact;
    private $profilePhoto;
    private $emailVerified;
    private $userType;
    private $active;
    private $password;
    private $lastLogin;
    private $createdBy;
    
    public function __construct($data = []) {
        parent::__construct();
        
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->matricula = $data['matricula'] ?? '';
            $this->curso = $data['curso'] ?? '';
            $this->phone = $data['phone'] ?? '';
            $this->address = $data['address'] ?? '';
            $this->emergencyContact = $data['emergency_contact'] ?? '';
            $this->profilePhoto = $data['profile_photo'] ?? null;
            $this->emailVerified = isset($data['email_verified']) ? (bool)$data['email_verified'] : false;
            $this->userType = $data['user_type'] ?? 'student';
            $this->active = isset($data['active']) ? (bool)$data['active'] : true;
            $this->password = $data['password'] ?? '';
            $this->lastLogin = $data['last_login'] ?? null;
        }
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getMatricula() { return $this->matricula; }
    public function getCurso() { return $this->curso; }
    public function getPhone() { return $this->phone; }
    public function getAddress() { return $this->address; }
    public function getEmergencyContact() { return $this->emergencyContact; }
    public function getProfilePhoto() { return $this->profilePhoto; }
    public function isEmailVerified() { return $this->emailVerified; }
    public function getUserType() { return $this->userType; }
    public function isActive() { return $this->active; }
    public function getLastLogin() { return $this->lastLogin; }
    
    // Setters
    public function setName($name) { $this->name = $name; }
    public function setEmail($email) { $this->email = $email; }
    public function setMatricula($matricula) { $this->matricula = $matricula; }
    public function setCurso($curso) { $this->curso = $curso; }
    public function setPhone($phone) { $this->phone = $phone; }
    public function setAddress($address) { $this->address = $address; }
    public function setEmergencyContact($contact) { $this->emergencyContact = $contact; }
    public function setProfilePhoto($path) { $this->profilePhoto = $path; }
    public function setEmailVerified($bool) { $this->emailVerified = (bool)$bool; }
    public function setUserType($userType) { $this->userType = $userType; }
    public function setPassword($password) { $this->password = $password; }
    
    public function authenticate($email, $password) {
        $sql = "SELECT id, name, email, password_hash, user_type, active, matricula, curso,
                       phone, address, emergency_contact, profile_photo, email_verified, last_login
                FROM users
                WHERE email = ? AND active = 1";
        
        $stmt = $this->executeQuery($sql, [$email]);
        $user = $this->fetchOne($stmt);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $this->id = $user['id'];
            $this->name = $user['name'];
            $this->email = $user['email'];
            $this->matricula = $user['matricula'];
            $this->curso = $user['curso'];
            $this->userType = $user['user_type'];
            $this->phone = $user['phone'] ?? '';
            $this->address = $user['address'] ?? '';
            $this->emergencyContact = $user['emergency_contact'] ?? '';
            $this->profilePhoto = $user['profile_photo'] ?? null;
            $this->emailVerified = isset($user['email_verified']) ? (bool)$user['email_verified'] : false;
            $this->lastLogin = $user['last_login'] ?? null;

            // Update last_login timestamp
            try {
                $this->executeQuery("UPDATE users SET last_login = NOW() WHERE id = ?", [$this->id]);
            } catch (PDOException $e) {
                error_log("Erro ao atualizar last_login: " . $e->getMessage());
            }

            return true;
        }
        
        return false;
    }
    
    public function save() {
        if ($this->id) {
            return $this->update();
        } else {
            return $this->create();
        }
    }
    
    private function create() {
        // VALIDAÇÃO ESPECÍFICA PARA ESTUDANTES
        if ($this->userType === 'student') {
            if (empty($this->matricula) || empty($this->curso)) {
                error_log("Estudante precisa de matrícula e curso: matricula={$this->matricula}, curso={$this->curso}");
                return false;
            }
        }
        
        // VERIFICA CAMPOS OBRIGATÓRIOS GERAIS
        if (empty($this->name) || empty($this->email) || empty($this->password)) {
            error_log("Campos obrigatórios faltando: name={$this->name}, email={$this->email}, password=" . (!empty($this->password) ? "set" : "empty"));
            return false;
        }

        // Verifica se já existe admin ao tentar criar um - AGORA PERMITE SE O USUÁRIO ATUAL FOR ADMIN
        if ($this->userType === 'admin') {
            session_start();
            if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
                error_log("Tentativa não autorizada de criar admin");
                return false;
            }
        }
        
        // Resto do método permanece igual...
        $sql = "INSERT INTO users (
                    name, email, matricula, curso, phone, 
                    address, emergency_contact, password_hash, 
                    user_type, profile_photo, email_verified, 
                    active, created_at, updated_at
                ) VALUES (
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, 
                    ?, ?, ?,
                    1, NOW(), NOW()
                )";
        
        $passwordHash = password_hash($this->password, PASSWORD_DEFAULT);
        
        $params = [
            $this->name,
            $this->email,
            $this->matricula ?: null,
            $this->curso ?: null,
            $this->phone ?: null,
            $this->address ?: null,
            $this->emergencyContact ?: null,
            $passwordHash,
            $this->userType,
            $this->profilePhoto ?: null,
            $this->emailVerified ? 1 : 0
        ];
        
        try {
            error_log("Iniciando criação de usuário...");
            error_log("Dados: " . print_r([
                'name' => $this->name,
                'email' => $this->email,
                'matricula' => $this->matricula,
                'curso' => $this->curso,
                'user_type' => $this->userType
            ], true));

            $stmt = $this->executeQuery($sql, $params);
            
            if (!$stmt) {
                error_log("Erro na preparação do SQL");
                error_log("SQL: " . $sql);
                error_log("Params: " . print_r($params, true));
                return false;
            }
            
            $this->id = $this->conn->lastInsertId();
            
            if ($this->id > 0) {
                error_log("Usuário criado com sucesso. ID: " . $this->id);
                return true;
            } else {
                error_log("Falha ao criar usuário. Não foi gerado um ID.");
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            error_log("Erro completo: " . print_r($e, true));
            return false;
        }
    }
    private function update() {
        $sql = "UPDATE users SET name = ?, email = ?, matricula = ?, curso = ?, phone = ?, address = ?, emergency_contact = ?, profile_photo = ?, email_verified = ?, updated_at = NOW()
                WHERE id = ?";
        
        $params = [
            $this->name,
            $this->email,
            $this->matricula ?: null,
            $this->curso ?: null,
            $this->phone ?: null,
            $this->address ?: null,
            $this->emergencyContact ?: null,
            $this->profilePhoto ?: null,
            $this->emailVerified ? 1 : 0,
            $this->id
        ];
        
        try {
            $stmt = $this->executeQuery($sql, $params);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        }
    }
    
    public static function findById($id) {
        $db = new Database();
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $db->executeQuery($sql, [$id]);
        $data = $db->fetchOne($stmt);
        
        return $data ? new User($data) : null;
    }
    
    public static function getAllStudents() {
        $db = new Database();
        $sql = "SELECT * FROM users WHERE user_type = 'student' AND active = 1 
                ORDER BY name";
        $stmt = $db->executeQuery($sql);
        return $db->fetchAll($stmt);
    }
}
?>