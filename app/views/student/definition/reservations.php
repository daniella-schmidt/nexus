<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Sistema de Gestão de Transporte</title>
    
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
                    fontFamily: { 
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'] 
                    },
                    colors: {
                        nexus: {
                            ink: '#0c0f14',
                            base: '#f5f7fd',
                            a: '#4b687f',
                            b: '#63a8ca',
                            c: '#85b0d8',
                            d: '#af91bd',
                            e: '#966e92',
                            f: '#7c4c68'
                        }
                    },
                    boxShadow: {
                        soft: '0 10px 30px rgba(13, 18, 28, .12)',
                        glow: '0 0 0 4px rgba(99,168,202,.18)',
                        'glow-lg': '0 0 20px 8px rgba(99,168,202,.25)'
                    },
                    backdropBlur: {
                        xs: '2px'
                    },
                    animation: {
                        'pulse-glow': 'pulse-glow 2s ease-in-out infinite alternate',
                        'float': 'float 6s ease-in-out infinite'
                    }
                }
            }
        }
    </script>
    
    <style>
        :root {
            --nexus-a: #4b687f;
            --nexus-b: #63a8ca;
            --nexus-c: #85b0d8;
            --nexus-d: #af91bd;
            --nexus-e: #966e92;
            --nexus-f: #7c4c68;
        }
        
        .gradient-surface { 
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
        
        .glass { 
            backdrop-filter: blur(10px); 
            background: rgba(255,255,255,.6); 
        }
        
        .frost { 
            backdrop-filter: blur(16px) saturate(130%); 
            background: rgba(255,255,255,.5); 
            border: 1px solid rgba(255,255,255,.4); 
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .nexus-gradient-text {
            background: linear-gradient(135deg, var(--nexus-a), var(--nexus-f));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nexus-card {
            transition: all 0.3s ease;
        }
        
        .nexus-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(13, 18, 28, .15);
        }
        
        @keyframes pulse-glow {
            from { 
                box-shadow: 0 0 0 0px rgba(99,168,202,.4); 
            }
            to { 
                box-shadow: 0 0 0 10px rgba(99,168,202,0); 
            }
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg); 
            }
            50% { 
                transform: translateY(-20px) rotate(180deg); 
            }
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape-1 { 
            width: 200px; 
            height: 200px; 
            background: var(--nexus-b); 
            top: 10%; 
            left: 5%; 
            animation-delay: 0s; 
        }
        
        .shape-2 { 
            width: 150px; 
            height: 150px; 
            background: var(--nexus-d); 
            top: 60%; 
            right: 10%; 
            animation-delay: 2s; 
        }
        
        .shape-3 { 
            width: 100px; 
            height: 100px; 
            background: var(--nexus-f); 
            bottom: 20%; 
            left: 20%; 
            animation-delay: 4s; 
        }
        
        /* Form styles consistent with about.html */
        [type='text'],
        [type='email'],
        [type='url'],
        [type='password'],
        [type='number'],
        [type='date'],
        [type='datetime-local'],
        [type='month'],
        [type='search'],
        [type='tel'],
        [type='time'],
        [type='week'],
        [multiple],
        textarea,
        select {
            @apply w-full rounded-xl border border-gray-300/60 bg-white/70 shadow-sm focus:border-nexus-b focus:ring-nexus-b focus:bg-white transition-all;
        }
        
        .btn-primary {
            @apply bg-nexus-b hover:bg-nexus-c text-white font-medium py-2 px-4 rounded-xl shadow-soft hover:shadow-glow transition-all;
        }
        
        .btn-secondary {
            @apply bg-white/70 hover:bg-white text-nexus-ink border border-gray-300/60 font-medium py-2 px-4 rounded-xl shadow-soft transition-all;
        }

        /* Mobile menu styles */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--nexus-a), var(--nexus-f));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-nexus-base text-nexus-ink gradient-surface min-h-screen relative">
    <?php $unread_notifications = $unread_notifications ?? 0; ?>
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Navbar -->
    <header class="sticky top-0 z-40 backdrop-blur-md bg-white/70 border-b border-white/60">
        <nav class="container mx-auto flex items-center justify-between py-3 px-4">
            <div class="flex items-center gap-3">
                <!-- Logomarca -->
                <div class="relative w-10 h-10 rounded-2xl grid place-items-center animate-pulse-glow" 
                     style="background: linear-gradient(135deg, var(--nexus-b), var(--nexus-d))">
                    <span class="w-6 h-6 inline-grid place-items-center rounded-xl">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2l3.3 6.7L22 12l-6.7 3.3L12 22l-3.3-6.7L2 12l6.7-3.3L12 2z"/>
                        </svg>
                    </span>
                </div>
                <span class="text-xl font-extrabold tracking-tight nexus-gradient-text">NEXUS</span>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-7 text-sm">
                <a href="/nexus/dashboard" 
                   class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b">
                    Dashboard
                </a>
                
                <?php if (isset($_SESSION['user_type'])): ?>
                    <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <a href="/nexus/manager/users" 
                           class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b">
                            Administradores
                        </a>
                        <a href="/nexus/manager/vehicles" 
                           class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b">
                            Veículos
                        </a>
                        <a href="/nexus/manager/routes" 
                           class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b">
                            Rotas
                        </a>
                        <a href="/nexus/manager/reports" 
                           class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b">
                            Relatórios
                        </a>
                    <?php elseif ($_SESSION['user_type'] === 'driver'): ?>
                        <a href="/nexus/driver/scan/checkin" 
                           class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b">
                            Check-in
                        </a>
                        <a href="/nexus/driver/reports/passengers" 
                           class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b">
                            Passageiros
                        </a>
                        <a href="/nexus/driver/definition/communication" 
                           class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b">
                            Comunicação
                        </a>
                    <?php elseif ($_SESSION['user_type'] === 'student'): ?>
                        <a href="/nexus/student/definition/reservations" 
                           class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b">
                            Reservas
                        </a>
                        <a href="/nexus/student/card/digital-card" 
                           class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b">
                            Carteirinha
                        </a>
                        <a href="/nexus/student/notifications" 
                           class="hover:opacity-80 transition-all font-medium text-nexus-ink hover:text-nexus-b relative">
                            Notificações
                            <?php if (isset($unread_notifications) && $unread_notifications > 0): ?>
                                <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full text-xs grid place-items-center font-bold">
                                    <?php echo $unread_notifications; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <!-- User Info & Actions -->
            <div class="flex items-center gap-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- User Info -->
                    <div class="hidden sm:flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-sm font-medium text-nexus-ink">
                                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuário'); ?>
                            </p>
                            <p class="text-xs text-nexus-ink/60 capitalize">
                                <?php
                                    $userType = $_SESSION['user_type'] ?? '';
                                    if ($userType === 'student') {
                                        echo 'Estudante';
                                    } elseif ($userType === 'driver') {
                                        echo 'Motorista';
                                    } elseif ($userType === 'admin') {
                                        echo 'Administrador';
                                    } else {
                                        echo 'Usuário';
                                    }
                                ?>
                            </p>
                        </div>
                        <?php if (!empty($_SESSION['user_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['user_photo']); ?>" 
                                 alt="Foto de perfil" 
                                 class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm">
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-full grid place-items-center text-white text-sm font-medium"
                                 style="background: linear-gradient(135deg, var(--nexus-b), var(--nexus-d))">
                                <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Logout Button -->
                    <a href="/nexus/logout" 
                       class="text-sm px-4 py-2 rounded-xl text-white font-medium shadow-soft hover:shadow-glow transition-all" 
                       style="background: var(--nexus-f)">
                        <i class="bi bi-box-arrow-right mr-1"></i>
                        <span class="hidden sm:inline">Sair</span>
                    </a>

                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuButton" class="md:hidden p-2 rounded-lg hover:bg-white/50 transition-all">
                        <i class="bi bi-list text-xl text-nexus-ink"></i>
                    </button>

                <?php else: ?>
                    <!-- Login Button for guests -->
                    <a href="/nexus/login" 
                       class="text-sm px-4 py-2 rounded-xl text-white font-medium shadow-soft hover:shadow-glow transition-all" 
                       style="background: var(--nexus-b)">
                        <i class="bi bi-box-arrow-in-right mr-1"></i>
                        <span class="hidden sm:inline">Entrar</span>
                    </a>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="mobile-menu fixed inset-0 z-50 bg-white/95 backdrop-blur-md md:hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl grid place-items-center" 
                             style="background: linear-gradient(135deg, var(--nexus-b), var(--nexus-d))">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2l3.3 6.7L22 12l-6.7 3.3L12 22l-3.3-6.7L2 12l6.7-3.3L12 2z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold nexus-gradient-text">NEXUS</span>
                    </div>
                    <button id="closeMobileMenu" class="p-2 rounded-lg hover:bg-gray-100 transition-all">
                        <i class="bi bi-x-lg text-xl text-nexus-ink"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <a href="/nexus/dashboard" 
                       class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink">
                        <i class="bi bi-speedometer2 mr-3"></i>Dashboard
                    </a>
                    
                    <?php if (isset($_SESSION['user_type'])): ?>
                        <?php if ($_SESSION['user_type'] === 'admin'): ?>
                            <a href="/nexus/manager/users" 
                               class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink">
                                <i class="bi bi-people mr-3"></i>Administradores
                            </a>
                            <a href="/nexus/manager/vehicles" 
                               class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink">
                                <i class="bi bi-bus-front mr-3"></i>Veículos
                            </a>
                            <a href="/nexus/manager/routes" 
                               class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink">
                                <i class="bi bi-signpost mr-3"></i>Rotas
                            </a>
                            <a href="/nexus/manager/reports" 
                               class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink">
                                <i class="bi bi-graph-up mr-3"></i>Relatórios
                            </a>
                        <?php elseif ($_SESSION['user_type'] === 'driver'): ?>
                            <a href="/nexus/driver/scan/checkin" 
                               class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink">
                                <i class="bi bi-qr-code-scan mr-3"></i>Check-in
                            </a>
                            <a href="/nexus/driver/reports/passengers" 
                               class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink">
                                <i class="bi bi-clipboard-data mr-3"></i>Passageiros
                            </a>
                            <a href="/nexus/driver/definition/communication" 
                               class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink">
                                <i class="bi bi-chat-dots mr-3"></i>Comunicação
                            </a>
                        <?php elseif ($_SESSION['user_type'] === 'student'): ?>
                            <a href="/nexus/student/definition/reservations" 
                               class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink">
                                <i class="bi bi-calendar-check mr-3"></i>Reservas
                            </a>
                            <a href="/nexus/student/card/digital-card" 
                               class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink">
                                <i class="bi bi-qr-code mr-3"></i>Carteirinha
                            </a>
                            <a href="/nexus/student/notifications" 
                               class="block py-3 px-4 rounded-xl hover:bg-nexus-b/10 transition-all font-medium text-nexus-ink relative">
                                <i class="bi bi-bell mr-3"></i>Notificações
                                <?php if (isset($unread_notifications) && $unread_notifications > 0): ?>
                                    <span class="absolute top-3 right-4 w-5 h-5 bg-red-500 text-white rounded-full text-xs grid place-items-center font-bold">
                                        <?php echo $unread_notifications; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-3 py-3 px-4">
                                <?php if (!empty($_SESSION['user_photo'])): ?>
                                    <img src="<?php echo htmlspecialchars($_SESSION['user_photo']); ?>" 
                                         alt="Foto de perfil" 
                                         class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-full grid place-items-center text-white font-medium"
                                         style="background: linear-gradient(135deg, var(--nexus-b), var(--nexus-d))">
                                        <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="font-medium text-nexus-ink"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuário'); ?></p>
                                    <p class="text-sm text-nexus-ink/60 capitalize">
                                        <?php 
                                            echo match($_SESSION['user_type'] ?? '') {
                                                'student' => 'Estudante',
                                                'driver' => 'Motorista',
                                                'admin' => 'Administrador',
                                                default => 'Usuário'
                                            };
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <a href="/nexus/logout" 
                               class="block py-3 px-4 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-all font-medium mt-2">
                                <i class="bi bi-box-arrow-right mr-3"></i>Sair
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-black mb-2 text-nexus-ink">Minhas Reservas</h1>
    <p class="text-nexus-ink/70 mb-8">Gerencie suas reservas de transporte</p>

    <!-- Formulário de Nova Reserva -->
    <div class="frost rounded-2xl p-6 shadow-soft mb-8">
      <h2 class="text-xl font-semibold mb-4 text-nexus-ink">Nova Reserva</h2>
      <form method="POST">
        <div class="grid md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium mb-2 text-nexus-ink">Data Inicial</label>
            <input type="date" name="start_date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>"
                  class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-nexus-ink">Data Final</label>
            <input type="date" name="end_date" value="<?= date('Y-m-d', strtotime('+1 month')) ?>" min="<?= date('Y-m-d') ?>"
                  class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
          </div>
        </div>

        <!-- Faculdade -->
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2 text-nexus-ink">Faculdade *</label>
          <select name="faculdade" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
            <option value="">Selecione sua faculdade</option>
            <option value="UNOESC">UNOESC</option>
            <option value="UNIVERSIDADE_X">Universidade X</option>
            <option value="FACULDADE_Y">Faculdade Y</option>
          </select>
        </div>

        <!-- Dias da Semana -->
        <div class="mb-4">
          <label class="block text-sm font-medium mb-4 text-nexus-ink">Dias da Semana *</label>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <?php
            $days = [
              'Segunda' => 'mon',
              'Terça' => 'tue',
              'Quarta' => 'wed',
              'Quinta' => 'thu',
              'Sexta' => 'fri',
              'Sábado' => 'sat',
              'Domingo' => 'sun'
            ];

            foreach ($days as $label => $value):
            ?>
            <div class="flex items-center p-3 rounded-xl border border-gray-300/60 bg-white/70 hover:bg-white transition-all">
              <input type="checkbox" name="days_of_week[]" value="<?= $value ?>"
                id="day_<?= $value ?>"
                class="rounded border-gray-300 text-nexus-b focus:ring-nexus-b">
              <label for="day_<?= $value ?>" class="ml-3 text-sm font-medium text-nexus-ink"><?= $label ?></label>
            </div>
            <?php endforeach; ?>
          </div>
          <p class="text-xs text-nexus-ink/60 mt-2">Selecione os dias da semana para fazer reservas recorrentes</p>
        </div>



        <div class="grid md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium mb-2 text-nexus-ink">Ponto de Embarque</label>
            <select name="pickup_point" id="pickup_point" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
              <option value="">Selecione o ponto de embarque</option>
              <option value="Centro">Centro</option>
              <option value="Bairro X">Bairro X</option>
              <option value="Bairro Y">Bairro Y</option>
              <option value="Terminal">Terminal</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-nexus-ink">Ponto de Desembarque</label>
            <select name="dropoff_point" id="dropoff_point" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
              <option value="">Selecione o ponto de desembarque</option>
              <option value="Centro">Centro</option>
              <option value="Bairro X">Bairro X</option>
              <option value="Bairro Y">Bairro Y</option>
              <option value="Terminal">Terminal</option>
            </select>
          </div>
        </div>
        <div class="flex justify-end">
          <button type="submit" class="px-6 py-3 rounded-xl text-white font-medium shadow-soft hover:shadow-glow transition-all" style="background:var(--nexus-a)">
            Fazer Reserva
          </button>
        </div>
      </form>
    </div>

    <!-- Lista de Reservas -->
    <div class="frost rounded-2xl p-6 shadow-soft">
      <h2 class="text-xl font-semibold mb-4 text-nexus-ink">Reservas Ativas</h2>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-nexus-a/5">
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Data Inicial/Final</th>
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Faculdade</th>
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Embarque/Desembarque</th>
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Dias da Semana</th>
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Horário</th>
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Status</th>
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $reservation): ?>
            <tr class="border-b border-nexus-ink/10 hover:bg-nexus-b/5 transition-colors">
              <td class="p-4">
                <?= date('d/m/Y', strtotime($reservation['start_date'])) ?> - <?= date('d/m/Y', strtotime($reservation['end_date'])) ?>
              </td>
              <td class="p-4 font-medium"><?= $reservation['collage'] ?></td>
              <td class="p-4">
                <?= $reservation['pickup_point'] ?> → <?= $reservation['dropoff_point'] ?>
              </td>
              <td class="p-4">
                <?php
                $daysMap = [
                  'mon' => 'Seg',
                  'tue' => 'Ter',
                  'wed' => 'Qua',
                  'thu' => 'Qui',
                  'fri' => 'Sex',
                  'sat' => 'Sáb',
                  'sun' => 'Dom'
                ];
                $days = explode(',', $reservation['days_of_week']);
                $formattedDays = [];
                foreach ($days as $day) {
                  if (isset($daysMap[$day])) {
                    $formattedDays[] = $daysMap[$day];
                  }
                }
                echo implode(', ', $formattedDays);
                ?>
              </td>
              <td class="p-4"><?= $reservation['departure_time'] ?></td>
              <td class="p-4">
                <span class="px-3 py-1 rounded-full text-sm font-medium
                  <?= $reservation['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                  <?= $reservation['status'] == 'active' ? 'Ativa' : 'Cancelada' ?>
                </span>
              </td>
              <td class="p-4">
                <?php if ($reservation['status'] == 'active'): ?>
                <button onclick="openCancelModal(<?= $reservation['id'] ?>)" class="text-nexus-f hover:text-nexus-e transition-colors text-sm font-medium">
                  Cancelar
                </button>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <?php if (empty($reservations)): ?>
        <div class="text-center py-12">
          <div class="w-16 h-16 mx-auto rounded-2xl grid place-items-center mb-4 bg-nexus-b/10">
            <i class="bi bi-calendar-x text-nexus-b text-2xl"></i>
          </div>
          <h3 class="text-lg font-semibold text-nexus-ink mb-2">Nenhuma reserva encontrada</h3>
          <p class="text-nexus-ink/70">Faça sua primeira reserva usando o formulário acima.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Modal de Cancelamento -->
  <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
      <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-soft">
          <div class="text-center">
              <div class="w-16 h-16 mx-auto rounded-2xl grid place-items-center mb-4 bg-red-100">
                  <i class="bi bi-exclamation-triangle text-red-600 text-2xl"></i>
              </div>
              <h3 class="text-lg font-semibold text-nexus-ink mb-2">Confirmar Cancelamento</h3>
              <p class="text-nexus-ink/70 mb-6">Tem certeza que deseja cancelar esta reserva? Esta ação não pode ser desfeita.</p>
              
              <!-- FORMULÁRIO DIRETO - sem AJAX -->
              <form method="POST" action="/student/definition/reservations" class="flex gap-3">
                  <input type="hidden" name="reservation_id" id="reservationIdToCancel" value="">
                  <input type="hidden" name="action" value="cancel">
                  
                  <button type="button" onclick="closeCancelModal()" class="flex-1 px-4 py-2 rounded-xl border border-gray-300 text-nexus-ink hover:bg-gray-50 transition-colors">
                      Voltar
                  </button>
                  <button type="submit" class="flex-1 px-4 py-2 rounded-xl text-white font-medium" style="background:var(--nexus-f)">
                      Confirmar Cancelamento
                  </button>
              </form>
          </div>
      </div>
  </div>

  <!-- Form oculto para cancelamento -->
  <form id="cancelForm" method="POST" action="/student/definition/cancel-reservation" style="display: none;">
    <input type="hidden" id="reservationIdToCancel" name="reservation_id" value="">
  </form>

  <!-- Footer -->
  <footer class="bg-white/80 border-t border-white/60 py-8 mt-12">
    <div class="container mx-auto px-4">
      <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-xl grid place-items-center" style="background:var(--nexus-a)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2l3.3 6.7L22 12l-6.7 3.3L12 22l-3.3-6.7L2 12l6.7-3.3L12 2z"/>
            </svg>
          </div>
          <span class="text-lg font-bold" style="color:var(--nexus-a)">NEXUS</span>
        </div>
        <div class="text-sm text-nexus-ink/70">
          © <?php echo date('Y'); ?> NEXUS • Sistema de Transporte Universitário
        </div>
      </div>
    </div>
  </footer>
<script>
function openCancelModal(reservationId) {
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('reservationIdToCancel').value = reservationId;
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}
</script>
</body>
</html>
