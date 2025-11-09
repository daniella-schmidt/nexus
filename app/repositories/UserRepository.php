<?php
// Agregação: UserRepository agrega Database para operações de banco de dados.
// Justificativa: Utiliza Database como parte de sua implementação para acesso a dados de usuários.

// Associação: UserRepository tem associação com User para operações de autenticação.
// Justificativa: Utiliza User para validação de credenciais, mas User pode existir independentemente.

// Encapsulamento: Propriedades privadas protegem a instância de Database.
// Justificativa: Impede acesso direto à conexão de banco de dados.

// Interfaces: UserRepository implementa RepositoryInterface para padronização.
// Justificativa: Garante que todos os repositórios tenham métodos CRUD consistentes.

// Visibilidade de Propriedades e Métodos: Métodos públicos para operações externas.
// Justificativa: Controla o acesso às funcionalidades do repositório.

require_once __DIR__ . '/../models/User.php';
require_once 'RepositoryInterface.php';

class UserRepository implements RepositoryInterface {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? AND active = 1";
        $stmt = $this->db->executeQuery($sql, [$email]);
        $data = $this->db->fetchOne($stmt);
        
        return $data ? new User($data) : null;
    }

    public function getProfilePhoto($userId) {
        $sql = "SELECT profile_photo FROM users WHERE id = ? AND active = 1";
        $stmt = $this->db->executeQuery($sql, [$userId]);
        $data = $this->db->fetchOne($stmt);
        
        return $data ? $data['profile_photo'] : null;
    }
    
    public function findActiveStudents() {
        $sql = "SELECT * FROM users 
                WHERE user_type = 'student' AND active = 1 
                ORDER BY name";
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }
    
    public function createUser($userData) {
        try {
            $user = new User($userData);
            $result = $user->save();
            
            if (!$result) {
                error_log("Falha ao salvar usuário no repositório");
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Exceção ao criar usuário: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateLastLogin($userId) {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $stmt = $this->db->executeQuery($sql, [$userId]);
        return $stmt->rowCount() > 0;
    }

    public function findById($userId) {
        $sql = "SELECT * FROM users WHERE id = ? AND active = 1";
        $stmt = $this->db->executeQuery($sql, [$userId]);
        $data = $this->db->fetchOne($stmt);

        if ($data) {
            $user = new User($data);
            return $user;
        }
        
        return null;
    }

    public function updateUser($userId, $userData) {
        $sql = "UPDATE users SET ";
        $params = [];
        $updates = [];

        if (isset($userData['name'])) {
            $updates[] = "name = ?";
            $params[] = $userData['name'];
        }

        if (isset($userData['email'])) {
            $updates[] = "email = ?";
            $params[] = $userData['email'];
        }

        if (isset($userData['matricula'])) {
            $updates[] = "matricula = ?";
            $params[] = $userData['matricula'];
        }

        if (isset($userData['curso'])) {
            $updates[] = "curso = ?";
            $params[] = $userData['curso'];
        }

        if (isset($userData['phone'])) {
            $updates[] = "phone = ?";
            $params[] = $userData['phone'];
        }

        if (isset($userData['emergency_contact'])) {
            $updates[] = "emergency_contact = ?";
            $params[] = $userData['emergency_contact'];
        }

        if (isset($userData['profile_photo'])) {
            $updates[] = "profile_photo = ?";
            $params[] = $userData['profile_photo'];
        }

        if (empty($updates)) {
            return false;
        }

        $sql .= implode(", ", $updates) . ", updated_at = NOW() WHERE id = ?";
        $params[] = $userId;

        try {
            $stmt = $this->db->executeQuery($sql, $params);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        }
    }

    // Implementação dos métodos da interface RepositoryInterface
    public function findAll() {
        $sql = "SELECT * FROM users WHERE active = 1 ORDER BY name";
        $stmt = $this->db->executeQuery($sql);
        return $this->db->fetchAll($stmt);
    }

    public function create($data) {
        return $this->createUser($data);
    }

    public function update($id, $data) {
        return $this->updateUser($id, $data);
    }

    public function delete($id) {
        $sql = "UPDATE users SET active = 0 WHERE id = ?";
        $stmt = $this->db->executeQuery($sql, [$id]);
        return $stmt->rowCount() > 0;
    }
}
?>
