<?php
// Herança: AdminController herda de BaseController, reutilizando funcionalidades comuns.
// Justificativa: Evita duplicação de código de autenticação e redirecionamento, focando em lógica específica de administração.

// Agregação: AdminController agrega UserRepository para operações de usuário.
// Justificativa: Utiliza UserRepository como parte de sua implementação para gerenciamento de usuários administradores.

require_once 'BaseController.php';
require_once '../repositories/UserRepository.php';
require_once '../models/User.php';

class AdminController extends BaseController {
    // Agregação: Propriedade privada para repositório, acessível apenas dentro da classe.
    // Justificativa: Permite composição de objetos, onde UserRepository é um componente usado para operações de dados.
    private $userRepository;
    
    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
        $this->userRepository = new UserRepository();
    }
    
    public function users() {
        // Listar todos os usuários
        $users = $this->getAllUsers();
        
        $this->renderView('admin/users', [
            'users' => $users
        ]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createAdmin();
        } else {
            $this->renderView('admin/create');
        }
    }
    
    private function createAdmin() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $errors = [];
        if (empty($name)) $errors[] = "Nome é obrigatório";
        if (empty($email)) $errors[] = "Email é obrigatório";
        if (empty($password)) $errors[] = "Senha é obrigatória";
        if (strlen($password) < 6) $errors[] = "A senha deve ter pelo menos 6 caracteres";
        
        // Verificar se email já existe
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser) {
            $errors[] = "Este email já está cadastrado";
        }
        
        if (!empty($errors)) {
            $this->session->set('error', implode(', ', $errors));
            $this->redirect('/admin/users/create');
        }
        
        $userData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'user_type' => 'admin',
            'password' => $password,
            'email_verified' => 1,
            'active' => 1
        ];
        
        if ($this->userRepository->createUser($userData)) {
            $this->session->set('success', 'Administrador cadastrado com sucesso!');
            $this->redirect('/admin/users');
        } else {
            $this->session->set('error', 'Erro ao cadastrar administrador');
            $this->redirect('/admin/users/create');
        }
    }
    
    public function edit($userId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateAdmin($userId);
        } else {
            $user = User::findById($userId);
            if (!$user) {
                $this->session->set('error', 'Usuário não encontrado');
                $this->redirect('/admin/users');
            }
            
            $this->renderView('admin/edit', [
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'phone' => $user->getPhone(),
                    'user_type' => $user->getUserType(),
                    'active' => $user->isActive(),
                    'created_at' => '', 
                    'updated_at' => ''  
                ]
            ]);
        }
    }
    
    private function updateAdmin($userId) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $status = $_POST['status'] ?? 'active';
        
        $errors = [];
        if (empty($name)) $errors[] = "Nome é obrigatório";
        if (empty($email)) $errors[] = "Email é obrigatório";
        if (!empty($password) && strlen($password) < 6) $errors[] = "A senha deve ter pelo menos 6 caracteres";
        
        if (!empty($errors)) {
            $this->session->set('error', implode(', ', $errors));
            $this->redirect("/admin/users/edit/{$userId}");
        }
        
        try {
            $db = new Database();
            $sql = "UPDATE users SET name = ?, email = ?, phone = ?, active = ?, updated_at = NOW()";
            $params = [$name, $email, $phone, $status === 'active' ? 1 : 0];
            
            if (!empty($password)) {
                $sql .= ", password_hash = ?";
                $params[] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $userId;
            
            $stmt = $db->executeQuery($sql, $params);
            
            if ($stmt->rowCount() > 0) {
                $this->session->set('success', 'Administrador atualizado com sucesso!');
            } else {
                $this->session->set('error', 'Nenhuma alteração foi realizada');
            }
        } catch (PDOException $e) {
            error_log("Erro ao atualizar administrador: " . $e->getMessage());
            $this->session->set('error', 'Erro ao atualizar administrador');
        }
        
        $this->redirect('/admin/users');
    }
    
    public function delete($userId) {
        // Não permitir excluir a si mesmo
        if ($userId == $this->session->get('user_id')) {
            $this->session->set('error', 'Você não pode excluir sua própria conta');
            $this->redirect('/admin/users');
        }
        
        try {
            $db = new Database();
            $sql = "DELETE FROM users WHERE id = ? AND user_type = 'admin'";
            $stmt = $db->executeQuery($sql, [$userId]);
            
            if ($stmt->rowCount() > 0) {
                $this->session->set('success', 'Administrador excluído com sucesso!');
            } else {
                $this->session->set('error', 'Administrador não encontrado ou não pode ser excluído');
            }
        } catch (PDOException $e) {
            error_log("Erro ao excluir administrador: " . $e->getMessage());
            $this->session->set('error', 'Erro ao excluir administrador');
        }
        
        $this->redirect('/admin/users');
    }
    
    public function toggleStatus($userId) {
        // Não permitir desativar a si mesmo
        if ($userId == $this->session->get('user_id')) {
            $this->session->set('error', 'Você não pode desativar sua própria conta');
            $this->redirect('/admin/users');
        }
        
        $user = User::findById($userId);
        if ($user) {
            $newStatus = $user->isActive() ? 0 : 1;
            $sql = "UPDATE users SET active = ?, updated_at = NOW() WHERE id = ?";
            
            try {
                $db = new Database();
                $stmt = $db->executeQuery($sql, [$newStatus, $userId]);
                
                $action = $newStatus ? 'ativada' : 'desativada';
                $this->session->set('success', "Conta {$action} com sucesso!");
            } catch (PDOException $e) {
                error_log("Erro ao alterar status do usuário: " . $e->getMessage());
                $this->session->set('error', 'Erro ao alterar status do usuário');
            }
        }
        
        $this->redirect('/admin/users');
    }
    
    private function getAllUsers() {
        $db = new Database();
        $sql = "SELECT u.*, 
                       CASE WHEN u.active = 1 THEN 'active' ELSE 'inactive' END as status
                FROM users u 
                WHERE u.user_type = 'admin'
                ORDER BY u.name";
        $stmt = $db->executeQuery($sql);
        return $db->fetchAll($stmt);
    }
    
    private function renderView($view, $data = []) {
        extract($data);
        require_once "../views/admin/{$view}.php";
    }
}
?>