<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cadastro - NEXUS</title>
  <!-- Tailwind (Play CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Inter font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
          colors: {
            nexus: {
              ink: '#0c0f14',
              base: '#f5f7fd',
              a: '#4b687f',   // primária escura
              b: '#63a8ca',   // destaque 1
              c: '#85b0d8',   // destaque 2
              d: '#af91bd',   // destaque 3
              e: '#966e92',   // destaque 4
              f: '#7c4c68'    // destaque 5
            }
          },
          boxShadow: {
            soft: '0 10px 30px rgba(13, 18, 28, .12)',
            glow: '0 0 0 4px rgba(99,168,202,.18)',
            'glow-lg': '0 0 20px 8px rgba(99,168,202,.25)'
          }
        }
      }
    }
  </script>
  
  <style>
    :root{
      --nexus-a:#4b687f; --nexus-b:#63a8ca; --nexus-c:#85b0d8; --nexus-d:#af91bd; --nexus-e:#966e92; --nexus-f:#7c4c68;
    }
    .gradient-surface{ 
      background-image: 
        radial-gradient(1200px 600px at 10% -10%, rgba(99,168,202,.25), transparent 60%),
        radial-gradient(900px 500px at 90% 10%, rgba(175,145,189,.22), transparent 60%),
        linear-gradient(180deg, #fbfcff 0%, #f5f7fd 60%, #eef2f7 100%);
    }
    .gradient-hero {
      background-image: 
        radial-gradient(800px 800px at 20% 30%, rgba(99,168,202,.25), transparent 60%),
        radial-gradient(600px 600px at 80% 70%, rgba(175,145,189,.22), transparent 60%),
        linear-gradient(135deg, #fbfcff 0%, #f5f7fd 100%);
    }
    .glass{ backdrop-filter: blur(10px); background: rgba(255,255,255,.6); }
    .frost{ backdrop-filter: blur(16px) saturate(130%); background: rgba(255,255,255,.5); border: 1px solid rgba(255,255,255,.4); }
    .brand-chip{ background: linear-gradient(135deg, var(--nexus-b), var(--nexus-d)); }
  </style>
</head>
<body class="bg-nexus-base text-nexus-ink gradient-surface min-h-screen flex items-center justify-center p-4">
  
  <!-- Background Elements -->
  <div class="absolute top-0 left-0 w-full h-full z-0 opacity-10">
    <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full" style="background: var(--nexus-b); filter: blur(60px);"></div>
    <div class="absolute bottom-1/3 right-1/4 w-80 h-80 rounded-full" style="background: var(--nexus-d); filter: blur(60px);"></div>
  </div>

  <div class="max-w-2xl w-full relative z-10">
    <!-- Header -->
    <div class="text-center mb-8">
      <div class="flex items-center justify-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-2xl grid place-items-center shadow-soft" style="background:var(--nexus-a)">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2l3.3 6.7L22 12l-6.7 3.3L12 22l-3.3-6.7L2 12l6.7-3.3L12 2z"/>
          </svg>
        </div>
        <span class="text-3xl font-black tracking-tight" style="color:var(--nexus-a)">NEXUS</span>
      </div>
      <h2 class="text-2xl font-bold text-nexus-ink">Criar sua conta</h2>
      <p class="mt-2 text-nexus-ink/70">Junte-se ao sistema de transporte universitário</p>
    </div>

    <!-- Formulário -->
    <form class="frost rounded-2xl p-8 shadow-soft" method="POST" enctype="multipart/form-data">
      <!-- Mensagens de Erro/Sucesso -->
      <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
          <div class="flex items-center gap-2">
            <i class="bi bi-exclamation-circle"></i>
            <span class="font-medium"><?php echo htmlspecialchars($_SESSION['error']); ?></span>
          </div>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <div class="grid md:grid-cols-2 gap-6">
        <!-- Nome Completo -->
        <div class="md:col-span-2">
          <label for="name" class="block text-sm font-medium text-nexus-ink mb-2">
            <i class="bi bi-person mr-2"></i>Nome Completo *
          </label>
          <input 
            id="name" 
            name="name" 
            type="text" 
            required 
            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
            placeholder="Digite seu nome completo"
            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
          >
        </div>

        <!-- Email -->
        <div class="md:col-span-2">
          <label for="email" class="block text-sm font-medium text-nexus-ink mb-2">
            <i class="bi bi-envelope mr-2"></i>Email Institucional *
          </label>
          <input 
            id="email" 
            name="email" 
            type="email" 
            required 
            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
            placeholder="seu.email@universidade.edu.br"
            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
          >
        </div>

        <!-- Tipo de Usuário -->
        <div>
          <label for="user_type" class="block text-sm font-medium text-nexus-ink mb-2">
            <i class="bi bi-person-badge mr-2"></i>Tipo de usuário *
          </label>
          <select id="user_type" name="user_type" class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all">
            <option value="student" <?php echo (($_POST['user_type'] ?? 'student') === 'student') ? 'selected' : ''; ?>>Estudante</option>
            <option value="driver" <?php echo (($_POST['user_type'] ?? '') === 'driver') ? 'selected' : ''; ?>>Motorista</option>
          </select>
        </div>

        <!-- Matrícula -->
        <div id="matricula_group">
          <label for="matricula" class="block text-sm font-medium text-nexus-ink mb-2">
            <i class="bi bi-card-text mr-2"></i>Matrícula <span id="matricula_required">*</span>
          </label>
          <input
            id="matricula"
            name="matricula"
            type="text"
            value="<?php echo htmlspecialchars($_POST['matricula'] ?? ''); ?>"
            placeholder="Sua matrícula universitária"
            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
          >
        </div>

        <!-- Curso -->
        <div id="curso_group">
          <label for="curso" class="block text-sm font-medium text-nexus-ink mb-2">
            <i class="bi bi-book mr-2"></i>Curso <span id="curso_required">*</span>
          </label>
          <select
            id="curso"
            name="curso"
            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
          >
            <option value="">Selecione seu curso</option>
            <option value="Administração" <?php echo (($_POST['curso'] ?? '') === 'Administração') ? 'selected' : ''; ?>>Administração</option>
            <option value="Arquitetura e Urbanismo" <?php echo (($_POST['curso'] ?? '') === 'Arquitetura e Urbanismo') ? 'selected' : ''; ?>>Arquitetura e Urbanismo</option>
            <option value="Ciência da Computação" <?php echo (($_POST['curso'] ?? '') === 'Ciência da Computação') ? 'selected' : ''; ?>>Ciência da Computação</option>
            <option value="Direito" <?php echo (($_POST['curso'] ?? '') === 'Direito') ? 'selected' : ''; ?>>Direito</option>
            <option value="Engenharia Civil" <?php echo (($_POST['curso'] ?? '') === 'Engenharia Civil') ? 'selected' : ''; ?>>Engenharia Civil</option>
            <option value="Engenharia de Software" <?php echo (($_POST['curso'] ?? '') === 'Engenharia de Software') ? 'selected' : ''; ?>>Engenharia de Software</option>
            <option value="Medicina" <?php echo (($_POST['curso'] ?? '') === 'Medicina') ? 'selected' : ''; ?>>Medicina</option>
            <option value="Outro" <?php echo (($_POST['curso'] ?? '') === 'Outro') ? 'selected' : ''; ?>>Outro</option>
          </select>
        </div>

        <!-- Telefone -->
        <div>
          <label for="phone" class="block text-sm font-medium text-nexus-ink mb-2">
            <i class="bi bi-telephone mr-2"></i>Telefone
          </label>
          <input 
            id="phone" 
            name="phone" 
            type="tel" 
            value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
            placeholder="(00) 00000-0000"
            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
          >
        </div>

        <!-- Endereço -->
        <div>
          <label for="address" class="block text-sm font-medium text-nexus-ink mb-2">
            <i class="bi bi-geo-alt mr-2"></i>Endereço
          </label>
          <input 
            id="address" 
            name="address" 
            type="text" 
            value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>"
            placeholder="Seu endereço completo"
            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
          >
        </div>

        <!-- Senha -->
        <div>
          <label for="password" class="block text-sm font-medium text-nexus-ink mb-2">
            <i class="bi bi-lock mr-2"></i>Senha *
          </label>
          <input 
            id="password" 
            name="password" 
            type="password" 
            required 
            placeholder="Crie uma senha segura"
            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
          >
        </div>

        <!-- Confirmar Senha -->
        <div>
          <label for="confirm_password" class="block text-sm font-medium text-nexus-ink mb-2">
            <i class="bi bi-shield-lock mr-2"></i>Confirmar Senha *
          </label>
          <input 
            id="confirm_password" 
            name="confirm_password" 
            type="password" 
            required 
            placeholder="Digite a senha novamente"
            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
          >
        </div>
      </div>

      <!-- Termos -->
      <div class="flex items-start gap-3 mt-6">
        <input 
          id="terms" 
          name="terms" 
          type="checkbox" 
          required
          class="mt-1 rounded focus:ring-nexus-b"
          <?php echo isset($_POST['terms']) ? 'checked' : ''; ?>
        >
        <label for="terms" class="text-sm text-nexus-ink/70">
          Concordo com os <a href="/terms" class="text-nexus-b hover:text-nexus-a font-medium">Termos de Serviço</a> 
          e <a href="/privacy" class="text-nexus-b hover:text-nexus-a font-medium">Política de Privacidade</a>
        </label>
      </div>

      <!-- Botão de Cadastro -->
      <button 
        type="submit"
        class="w-full mt-6 py-3 px-4 rounded-xl text-white font-medium shadow-soft hover:shadow-glow-lg transition-all duration-300"
        style="background:var(--nexus-a)"
      >
        <i class="bi bi-person-plus mr-2"></i>
        Criar Conta
      </button>

      <!-- Link para Login -->
      <div class="text-center mt-6">
        <p class="text-nexus-ink/70">
          Já tem uma conta? 
          <a href="login" class="font-medium text-nexus-b hover:text-nexus-a transition-colors">
            Fazer login
          </a>
        </p>
      </div>
    </form>

    <!-- Informações Adicionais -->
    <div class="mt-8 grid grid-cols-3 gap-4 text-center">
      <div class="p-3 rounded-xl bg-white/50 border border-white/60">
        <i class="bi bi-shield-check text-nexus-b text-lg"></i>
        <p class="text-xs mt-1 text-nexus-ink/70">Seguro</p>
      </div>
      <div class="p-3 rounded-xl bg-white/50 border border-white/60">
        <i class="bi bi-lightning-charge text-nexus-d text-lg"></i>
        <p class="text-xs mt-1 text-nexus-ink/70">Rápido</p>
      </div>
      <div class="p-3 rounded-xl bg-white/50 border border-white/60">
        <i class="bi bi-graph-up text-nexus-f text-lg"></i>
        <p class="text-xs mt-1 text-nexus-ink/70">Eficiente</p>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const userType = document.getElementById('user_type');
      const matriculaGroup = document.getElementById('matricula_group');
      const cursoGroup = document.getElementById('curso_group');

      function toggleFields() {
        const matriculaRequired = document.getElementById('matricula_required');
        const cursoRequired = document.getElementById('curso_required');
        const matriculaInput = document.getElementById('matricula');
        const cursoSelect = document.getElementById('curso');

        if (userType.value === 'driver') {
          matriculaGroup.style.display = 'none';
          cursoGroup.style.display = 'none';
          matriculaInput.required = false;
          cursoSelect.required = false;
        } else {
          matriculaGroup.style.display = 'block';
          cursoGroup.style.display = 'block';
          matriculaInput.required = true;
          cursoSelect.required = true;
        }
      }

      userType.addEventListener('change', toggleFields);
      toggleFields();

      // Validação de senha
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('confirm_password');
      
      function validatePassword() {
        if (password.value !== confirmPassword.value) {
          confirmPassword.style.borderColor = '#ef4444';
          confirmPassword.style.backgroundColor = '#fef2f2';
        } else {
          confirmPassword.style.borderColor = '#10b981';
          confirmPassword.style.backgroundColor = '#f0fdf4';
        }
      }
      
      password.addEventListener('input', validatePassword);
      confirmPassword.addEventListener('input', validatePassword);
    });
  </script>
</body>
</html>