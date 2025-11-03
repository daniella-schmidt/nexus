<?php
// Herança: AuthController herda de BaseController, reutilizando funcionalidades comuns.
// Justificativa: Evita duplicação de código de autenticação e redirecionamento, focando em lógica específica de autenticação.

// Agregação: AuthController agrega UserRepository para operações de usuário.
// Justificativa: Utiliza UserRepository como parte de sua implementação para autenticação e registro de usuários.

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

class AuthController extends BaseController {
    // Agregação: Propriedade privada para repositório, acessível apenas dentro da classe.
    // Justificativa: Permite composição de objetos, onde UserRepository é um componente usado para operações de dados.
    private $userRepository;
    
    public function __construct() {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->userRepository->findByEmail($email);
            
            if ($user && $user->authenticate($email, $password)) {
                $this->session->set('user_id', $user->getId());
                $this->session->set('user_name', $user->getName());
                $this->session->set('user_type', $user->getUserType());
                $this->session->set('user_email', $user->getEmail());
                $this->session->set('user_matricula', $user->getMatricula());
                $this->session->set('user_curso', $user->getCurso());
                
                $this->userRepository->updateLastLogin($user->getId());
                
                $this->redirect('/dashboard');
            } else {
                $this->session->set('error', 'Credenciais inválidas');
                $this->redirect('/login');
            }
        } else {
            $this->renderView('auth/login');
        }
    }
    
    public function logout() {
        $this->session->destroy();
        $this->redirect('/login');
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // DEBUG: Log todos os dados do POST
            error_log("=== DADOS DO FORMULÁRIO ===");
            foreach ($_POST as $key => $value) {
                error_log("$key: $value");
            }
            
            // VALIDAR CAMPOS - usar trim() para remover espaços
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $matricula = trim($_POST['matricula'] ?? '');
            $curso = trim($_POST['curso'] ?? '');
            // Tipo de usuário: allow only 'student' or 'driver' from the public form
            $user_type = trim($_POST['user_type'] ?? 'student');
            if (!in_array($user_type, ['student','driver'], true)) {
                $user_type = 'student';
            }
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $emergency_contact = trim($_POST['emergency_contact'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // VALIDAÇÕES
            $errors = [];
            if (empty($name)) $errors[] = "Nome é obrigatório";
            if (empty($email)) $errors[] = "Email é obrigatório";
            // Se for estudante, matrícula e curso são obrigatórios
            if ($user_type === 'student') {
                if (empty($matricula)) $errors[] = "Matrícula é obrigatória";
                if (empty($curso)) $errors[] = "Curso é obrigatório";
            }
            if (empty($password)) $errors[] = "Senha é obrigatória";
            
            if (!empty($errors)) {
                $this->session->set('error', implode(', ', $errors));
                $this->redirect('/register');
            }
            
            if ($password !== $confirm_password) {
                $this->session->set('error', 'As senhas não coincidem');
                $this->redirect('/register');
            }
            
            if (strlen($password) < 6) {
                $this->session->set('error', 'A senha deve ter pelo menos 6 caracteres');
                $this->redirect('/register');
            }
            
            // VERIFICAR SE EMAIL JÁ EXISTE
            $existingUser = $this->userRepository->findByEmail($email);
            if ($existingUser) {
                $this->session->set('error', 'Este email já está cadastrado');
                $this->redirect('/register');
            }

            // Processar upload de foto de perfil (opcional)
            $profilePath = null;
            if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/profile_photos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $originalName = $_FILES['profile_photo']['name'];
                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                $newName = uniqid('pf_') . ($ext ? '.' . $ext : '');
                $dest = $uploadDir . $newName;

                if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $dest)) {
                    // Caminho relativo acessível pela aplicação
                    $profilePath = '/uploads/profile_photos/' . $newName;
                } else {
                    error_log('Falha ao mover arquivo de perfil para destino');
                }
            }

            // Enforce: drivers must not have matricula/curso
            if ($user_type === 'driver') {
                $matricula = null;
                $curso = null;
            }

            $userData = [
                'name' => $name,
                'email' => $email,
                'matricula' => $matricula,
                'curso' => $curso,
                'phone' => $phone,
                'address' => $address,
                'emergency_contact' => $emergency_contact,
                'profile_photo' => $profilePath,
                'user_type' => $user_type,
                'password' => $password,
                'email_verified' => 0
            ];
            
            if ($this->userRepository->createUser($userData)) {
                $this->session->set('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
                $this->redirect('/login');
            } else {
                $this->session->set('error', 'Erro ao cadastrar usuário. Tente novamente.');
                $this->redirect('/register');
            }
        } else {
            $this->renderView('auth/register');
        }
    }
    
    private function renderView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>