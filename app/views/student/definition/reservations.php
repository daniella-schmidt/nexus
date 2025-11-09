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

    function openCancelModal(reservationId) {
      document.getElementById('cancelModal').classList.remove('hidden');
      document.getElementById('reservationIdToCancel').value = reservationId;
    }

    function closeCancelModal() {
      document.getElementById('cancelModal').classList.add('hidden');
    }

    async function confirmCancel() {
      const reservationId = document.getElementById('reservationIdToCancel').value;
      const formData = new FormData();
      formData.append('reservation_id', reservationId);

      console.log('Enviando requisição AJAX para cancelar reserva:', reservationId);

      try {
        const response = await fetch('/student/definition/cancel-reservation', {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          },
          body: formData
        });

        console.log('Resposta recebida:', response.status, response.statusText);

        if (!response.ok) {
          throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const result = await response.json();
        console.log('Resultado JSON:', result);

        if (result.success) {
          closeCancelModal();
          // Recarregar a página para atualizar a lista de reservas
          location.reload();
        } else {
          alert('Erro ao cancelar reserva: ' + (result.message || 'Erro desconhecido'));
        }
      } catch (error) {
        console.error('Erro na requisição:', error);
        alert('Erro ao processar a solicitação: ' + error.message);
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
