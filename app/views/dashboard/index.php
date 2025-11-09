<?php
// Verificar se usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: /nexus/login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard - NEXUS</title>
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
    .frost{ backdrop-filter: blur(16px) saturate(130%); background: rgba(255,255,255,.5); border: 1px solid rgba(255,255,255,.4); }
    .nexus-card {
      transition: all 0.3s ease;
    }
    .nexus-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(13, 18, 28, .15);
    }
  </style>
</head>
<body class="bg-nexus-base text-nexus-ink gradient-surface min-h-screen">
  <!-- Navbar -->
  <header class="sticky top-0 z-40 backdrop-blur-md bg-white/70 border-b border-white/60">
    <nav class="container mx-auto flex items-center justify-between py-3 px-4">
      <div class="flex items-center gap-3">
        <div class="relative w-10 h-10 rounded-2xl grid place-items-center" style="box-shadow: 0 0 0 8px rgba(133,176,216,.18)">
          <span class="w-6 h-6 inline-grid place-items-center rounded-xl" style="background:var(--nexus-f)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2l3.3 6.7L22 12l-6.7 3.3L12 22l-3.3-6.7L2 12l6.7-3.3L12 2z"/>
            </svg>
          </span>
        </div>
        <span class="text-xl font-extrabold tracking-tight" style="color:var(--nexus-a)">NEXUS</span>
      </div>
      
      <div class="flex items-center gap-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="text-right hidden sm:block">
                    </div>
                    <a href="/nexus/logout" class="text-sm px-4 py-2 rounded-xl text-white font-medium shadow-soft hover:shadow-glow transition-all" style="background:var(--nexus-f)">
                        <i class="bi bi-box-arrow-right mr-1"></i>Sair
                    </a>
                <?php else: ?>
                    <a href="/nexus/login" class="text-sm px-4 py-2 rounded-xl text-white font-medium shadow-soft hover:shadow-glow transition-all" style="background:var(--nexus-b)">
                        <i class="bi bi-box-arrow-in-right mr-1"></i>Entrar
                    </a>
                <?php endif; ?>
      </div>
    </nav>
  </header>

  <div class="container mx-auto p-6">
    <!-- Card de Boas-vindas -->
    <div class="frost rounded-2xl p-8 shadow-soft mb-8">
      <h1 class="text-3xl font-black mb-2 text-nexus-ink">Bem-vindo ao NEXUS!</h1>
      <p class="text-nexus-ink/70 text-lg">Sistema de Transporte Universitário</p>
    </div>

    <!-- Informações do Usuário -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

      <div class="nexus-card frost rounded-2xl p-6 shadow-soft">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl grid place-items-center" style="background:var(--nexus-b)">
            <i class="bi bi-envelope text-white text-lg"></i>
          </div>
          <div>
            <h3 class="font-semibold text-nexus-ink">Email</h3>
            <p class="text-nexus-ink/70"><?php echo htmlspecialchars($email); ?></p>
          </div>
        </div>
      </div>

      <?php if ($curso): ?>
      <div class="nexus-card frost rounded-2xl p-6 shadow-soft">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl grid place-items-center" style="background:var(--nexus-f)">
            <i class="bi bi-book text-white text-lg"></i>
          </div>
          <div>
            <h3 class="font-semibold text-nexus-ink">Curso</h3>
            <p class="text-nexus-ink/70"><?php echo htmlspecialchars($curso); ?></p>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Menu de Navegação -->
    <?php if ($type === 'student'): ?>
    <!-- Menu para Estudantes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <a href="/nexus/student/definition/reservations" class="nexus-card frost rounded-2xl p-6 shadow-soft hover:shadow-glow-lg transition-all border border-white/40">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl grid place-items-center" style="background:var(--nexus-b)">
            <i class="bi bi-calendar-check text-white text-xl"></i>
          </div>
          <div>
            <h3 class="font-semibold text-lg text-nexus-ink">Minhas Reservas</h3>
            <p class="text-nexus-ink/70 text-sm">Faça e gerencie suas reservas</p>
          </div>
        </div>
      </a>

      <a href="/nexus/student/card/digital-card" class="nexus-card frost rounded-2xl p-6 shadow-soft hover:shadow-glow-lg transition-all border border-white/40">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl grid place-items-center" style="background:var(--nexus-d)">
            <i class="bi bi-qr-code text-white text-xl"></i>
          </div>
          <div>
            <h3 class="font-semibold text-lg text-nexus-ink">Carteirinha Digital</h3>
            <p class="text-nexus-ink/70 text-sm">Acesse seu QR Code</p>
          </div>
        </div>
      </a>

      <a href="/nexus/student/notifications" class="nexus-card frost rounded-2xl p-6 shadow-soft hover:shadow-glow-lg transition-all border border-white/40">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl grid place-items-center" style="background:var(--nexus-e)">
                <i class="bi bi-bell text-white text-xl"></i>
            </div>
            <div>
                <h3 class="font-semibold text-lg text-nexus-ink">Notificações</h3>
                <p class="text-nexus-ink/70 text-sm">Mensagens dos motoristas</p>
            </div>
        </div>
    </a>

      <a href="/nexus/student/definition/profile" class="nexus-card frost rounded-2xl p-6 shadow-soft hover:shadow-glow-lg transition-all border border-white/40">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl grid place-items-center" style="background:var(--nexus-f)">
            <i class="bi bi-person-gear text-white text-xl"></i>
          </div>
          <div>
            <h3 class="font-semibold text-lg text-nexus-ink">Meu Perfil</h3>
            <p class="text-nexus-ink/70 text-sm">Atualize seus dados</p>
          </div>
        </div>
      </a>
    </div>

    <?php elseif ($type === 'driver'): ?>
    <!-- Menu para Motoristas - Layout Corrigido -->
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="frost rounded-2xl p-6 shadow-soft">
        <div class="flex items-center">
          <div class="p-3 bg-nexus-b/10 rounded-lg">
            <i class="bi bi-calendar-check text-nexus-b text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm text-nexus-ink/70">Reservas Hoje</p>
            <p class="text-2xl font-bold text-nexus-ink"><?php echo count($reservations ?? []); ?></p>
          </div>
        </div>
      </div>

      <div class="frost rounded-2xl p-6 shadow-soft">
        <div class="flex items-center">
          <div class="p-3 bg-nexus-c/10 rounded-lg">
            <i class="bi bi-car-front text-nexus-c text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm text-nexus-ink/70">Veículos Ativos</p>
            <p class="text-2xl font-bold text-nexus-ink"><?php echo count($vehicles ?? []); ?></p>
          </div>
        </div>
      </div>

      <div class="frost rounded-2xl p-6 shadow-soft">
        <div class="flex items-center">
          <div class="p-3 bg-nexus-d/10 rounded-lg">
            <i class="bi bi-map text-nexus-d text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm text-nexus-ink/70">Rotas Ativas</p>
            <p class="text-2xl font-bold text-nexus-ink"><?php echo count($routes ?? []); ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Today's Reservations -->
      <div class="frost rounded-2xl p-6 shadow-soft">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-semibold text-nexus-ink">Reservas de Hoje</h2>
          <a href="/nexus/driver/reports/passengers" class="text-nexus-b hover:text-nexus-a transition-colors text-sm">
            <i class="bi bi-eye mr-2"></i>Ver Detalhes
          </a>
        </div>

        <?php if (!empty($reservations)): ?>
          <div class="space-y-3">
            <?php foreach (array_slice($reservations, 0, 5) as $reservation): ?>
              <div class="flex items-center justify-between p-3 bg-white/50 rounded-lg">
                <div>
                  <p class="font-medium text-nexus-ink"><?php echo htmlspecialchars($reservation['student_name']); ?></p>
                  <p class="text-sm text-nexus-ink/70"><?php echo htmlspecialchars($reservation['matricula']); ?> • <?php echo htmlspecialchars($reservation['route_name']); ?></p>
                </div>
                <div class="text-right">
                  <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Confirmada</span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-8">
            <i class="bi bi-calendar-x text-4xl text-nexus-ink/30 mb-4"></i>
            <p class="text-nexus-ink/70">Nenhuma reserva para hoje</p>
          </div>
        <?php endif; ?>
      </div>

      <!-- Active Routes -->
      <div class="frost rounded-2xl p-6 shadow-soft">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-semibold text-nexus-ink">Rotas Ativas</h2>
          <a href="/nexus/driver/definition/routes" class="text-nexus-b hover:text-nexus-a transition-colors text-sm">
            <i class="bi bi-gear mr-2"></i>Gerenciar
          </a>
        </div>

        <?php if (!empty($routes)): ?>
          <div class="space-y-3">
            <?php foreach ($routes as $route): ?>
              <div class="flex items-center justify-between p-3 bg-white/50 rounded-lg">
                <div>
                  <p class="font-medium text-nexus-ink"><?php echo htmlspecialchars($route['name']); ?></p>
                  <p class="text-sm text-nexus-ink/70"><?php echo htmlspecialchars($route['origin']); ?> → <?php echo htmlspecialchars($route['destination']); ?></p>
                </div>
                <div class="text-right">
                  <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full"><?php echo htmlspecialchars($route['departure_time']); ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-8">
            <i class="bi bi-map text-4xl text-nexus-ink/30 mb-4"></i>
            <p class="text-nexus-ink/70">Nenhuma rota ativa</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-nexus-ink mb-6">Ações Rápidas</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="/nexus/driver/scan/checkin" class="nexus-card frost rounded-2xl p-6 text-center hover:shadow-glow-lg transition-all border border-white/40">
          <i class="bi bi-qr-code-scan text-3xl text-nexus-b mb-3"></i>
          <p class="font-medium text-nexus-ink">Check-in QR</p>
        </a>

        <a href="/nexus/driver/definition/vehicle" class="nexus-card frost rounded-2xl p-6 text-center hover:shadow-glow-lg transition-all border border-white/40">
          <i class="bi bi-car-front text-3xl text-nexus-c mb-3"></i>
          <p class="font-medium text-nexus-ink">Selecionar Veículo</p>
        </a>

        <a href="/nexus/driver/definition/communication" class="nexus-card frost rounded-2xl p-6 text-center hover:shadow-glow-lg transition-all border border-white/40">
          <i class="bi bi-chat-dots text-3xl text-nexus-e mb-3"></i>
          <p class="font-medium text-nexus-ink">Comunicação</p>
        </a>

        <a href="/nexus/driver/definition/profile" class="nexus-card frost rounded-2xl p-6 text-center hover:shadow-glow-lg transition-all border border-white/40">
          <i class="bi bi-person-gear text-nexus-d text-3xl"></i>
          <p class="font-medium text-nexus-ink">Perfil</p>
        </a>
      </div>
    </div>

    <?php elseif ($type === 'admin'): ?>
    <!-- Menu para Administradores -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <a href="/nexus/manager/vehicles" class="nexus-card frost rounded-2xl p-6 shadow-soft hover:shadow-glow-lg transition-all border border-white/40">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl grid place-items-center" style="background:var(--nexus-b)">
            <i class="bi bi-bus-front text-white text-xl"></i>
          </div>
          <div>
            <h3 class="font-semibold text-lg text-nexus-ink">Gerenciar Veículos</h3>
            <p class="text-nexus-ink/70 text-sm">Cadastre e gerencie veículos</p>
          </div>
        </div>
      </a>

      <a href="/nexus/manager/routes" class="nexus-card frost rounded-2xl p-6 shadow-soft hover:shadow-glow-lg transition-all border border-white/40">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl grid place-items-center" style="background:var(--nexus-d)">
            <i class="bi bi-signpost text-white text-xl"></i>
          </div>
          <div>
            <h3 class="font-semibold text-lg text-nexus-ink">Gerenciar Rotas</h3>
            <p class="text-nexus-ink/70 text-sm">Configure as rotas disponíveis</p>
          </div>
        </div>
      </a>

      <a href="/nexus/manager/users" class="nexus-card frost rounded-2xl p-6 shadow-soft hover:shadow-glow-lg transition-all border border-white/40">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl grid place-items-center" style="background:var(--nexus-f)">
            <i class="bi bi-people text-white text-xl"></i>
          </div>
          <div>
            <h3 class="font-semibold text-lg text-nexus-ink">Gerenciar Usuários</h3>
            <p class="text-nexus-ink/70 text-sm">Adicione novos administradores</p>
          </div>
        </div>
      </a>
    </div>
    <?php endif; ?>

  </div>

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
</body>
</html>