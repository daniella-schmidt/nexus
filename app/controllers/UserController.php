<?php
// Herança: UserController herda de BaseController, reutilizando funcionalidades comuns.
// Justificativa: Evita duplicação de código de autenticação e redirecionamento, focando em lógica específica de gerenciamento de usuários.

// Agregação: UserController agrega UserRepository para operações de usuário.
// Justificativa: Utiliza UserRepository como parte de sua implementação para gerenciamento de usuários administradores.

require_once 'app/controllers/BaseController.php';
require_once 'app/repositories/UserRepository.php';

class UserController extends BaseController {
    private $userRepository;
    
    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
        $this->userRepository = new UserRepository();
    }
    
    public function index() {
        $users = $this->getAllUsers();
        
        $this->renderView('manager/users/index', [
            'users' => $users
        ]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            $this->renderView('manager/users/create');
        }
    }
    
    public function store() {
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
            $this->redirect('/manager/users/create');
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
            $this->redirect('/manager/users');
        } else {
            $this->session->set('error', 'Erro ao cadastrar administrador');
            $this->redirect('/manager/users/create');
        }
    }
    
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            $user = $this->getUserById($id);
            if (!$user) {
                $this->session->set('error', 'Usuário não encontrado');
                $this->redirect('/manager/users');
            }
            
            $this->renderView('manager/users/edit', [
                'user' => $user
            ]);
        }
    }
    
    public function update($id) {
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
            $this->redirect("/manager/users/edit/{$id}");
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
            $params[] = $id;
            
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
        
        $this->redirect('/manager/users');
    }
    
    public function delete($id) {
        // Não permitir excluir a si mesmo
        if ($id == $this->session->get('user_id')) {
            $this->session->set('error', 'Você não pode excluir sua própria conta');
            $this->redirect('/manager/users');
        }
        
        try {
            $db = new Database();
            $sql = "DELETE FROM users WHERE id = ? AND user_type = 'admin'";
            $stmt = $db->executeQuery($sql, [$id]);
            
            if ($stmt->rowCount() > 0) {
                $this->session->set('success', 'Administrador excluído com sucesso!');
            } else {
                $this->session->set('error', 'Administrador não encontrado ou não pode ser excluído');
            }
        } catch (PDOException $e) {
            error_log("Erro ao excluir administrador: " . $e->getMessage());
            $this->session->set('error', 'Erro ao excluir administrador');
        }
        
        $this->redirect('/manager/users');
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
    
    private function getUserById($id) {
        $db = new Database();
        $sql = "SELECT u.*, 
                       CASE WHEN u.active = 1 THEN 'active' ELSE 'inactive' END as status
                FROM users u 
                WHERE u.id = ? AND u.user_type = 'admin'";
        $stmt = $db->executeQuery($sql, [$id]);
        return $db->fetchOne($stmt);
    }
    
    private function renderView($view, $data = []) {
        extract($data);
        $viewPath = "app/views/{$view}.php";
        
        if (!file_exists($viewPath)) {
            error_log("View não encontrada: " . $viewPath);
            throw new Exception("View não encontrada: {$view}");
        }
        
        require_once $viewPath;
    }
}
?>