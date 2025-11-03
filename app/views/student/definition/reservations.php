<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Minhas Reservas - NEXUS</title>
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
            glow: '0 0 0 4px rgba(99,168,202,.18)'
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
        <a href="/nexus/dashboard" class="text-sm text-nexus-ink/70 hover:text-nexus-ink transition-colors">
          <i class="bi bi-arrow-left mr-1"></i>Voltar
        </a>
      </div>
    </nav>
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
            <select name="route_id" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
              <option value="">Selecione uma rota</option>
              <?php foreach ($routes as $route): ?>
                <option value="<?= $route['id'] ?>"><?= htmlspecialchars($route['name']) ?> - <?= htmlspecialchars($route['departure_time']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-nexus-ink">Ponto de Desembarque</label>
            <select name="route_id" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
              <option value="">Selecione uma rota</option>
              <?php foreach ($routes as $route): ?>
                <option value="<?= $route['id'] ?>"><?= htmlspecialchars($route['name']) ?> - <?= htmlspecialchars($route['departure_time']) ?></option>
              <?php endforeach; ?>
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
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Data</th>
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Rota</th>
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Horário</th>
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Status</th>
              <th class="p-4 text-left text-sm font-semibold text-nexus-ink">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $reservation): ?>
            <tr class="border-b border-nexus-ink/10 hover:bg-nexus-b/5 transition-colors">
              <td class="p-4"><?= date('d/m/Y', strtotime($reservation['reservation_date'])) ?></td>
              <td class="p-4 font-medium"><?= $reservation['route_name'] ?></td>
              <td class="p-4"><?= $reservation['schedule'] ?></td>
              <td class="p-4">
                <span class="px-3 py-1 rounded-full text-sm font-medium
                  <?= $reservation['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                  <?= $reservation['status'] == 'active' ? 'Ativa' : 'Cancelada' ?>
                </span>
              </td>
              <td class="p-4">
                <?php if ($reservation['status'] == 'active'): ?>
                <form method="POST" action="/student/definition/cancel-reservation" class="inline">
                  <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                  <button type="submit" class="text-nexus-f hover:text-nexus-e transition-colors text-sm font-medium">
                    Cancelar
                  </button>
                </form>
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
  // Dados das rotas (serão passados do PHP)
const routesData = <?php echo json_encode($routes); ?>;

function updatePickupPoints() {
    const routeSelect = document.querySelector('select[name="route_id"]');
    const pickupSelect = document.querySelector('select[name="pickup_point"]');
    const dropoffSelect = document.querySelector('select[name="dropoff_point"]');

    const selectedRouteId = routeSelect.value;

    // Limpar opções atuais
    pickupSelect.innerHTML = '<option value="">Selecione o ponto de embarque</option>';
    dropoffSelect.innerHTML = '<option value="">Selecione o ponto de desembarque</option>';

    if (selectedRouteId) {
        const selectedRoute = routesData.find(route => route.id == selectedRouteId);
        if (selectedRoute && selectedRoute.pickup_points) {
            const pickupPoints = selectedRoute.pickup_points.split(',').map(point => point.trim());

            // Adicionar pontos de embarque
            pickupPoints.forEach(point => {
                const option = document.createElement('option');
                option.value = point;
                option.textContent = point;
                pickupSelect.appendChild(option);
            });

            // Adicionar pontos de desembarque (pode ser diferente se necessário)
            pickupPoints.forEach(point => {
                const option = document.createElement('option');
                option.value = point;
                option.textContent = point;
                dropoffSelect.appendChild(option);
            });
        } else {
            // Pontos padrão se não houver específicos
            const defaultPoints = ['Unoesc', 'Centro', 'Terminal', 'São Sebastião'];
            defaultPoints.forEach(point => {
                const option1 = document.createElement('option');
                option1.value = point;
                option1.textContent = point;
                pickupSelect.appendChild(option1);

                const option2 = document.createElement('option');
                option2.value = point;
                option2.textContent = point;
                dropoffSelect.appendChild(option2);
            });
        }
    }
}

// Adicionar event listener quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    const routeSelect = document.querySelector('select[name="route_id"]');
    if (routeSelect) {
        routeSelect.addEventListener('change', updatePickupPoints);
        
        // Atualizar imediatamente se já houver um valor selecionado
        if (routeSelect.value) {
            updatePickupPoints();
        }
    }
});
</script>
</body>
</html>