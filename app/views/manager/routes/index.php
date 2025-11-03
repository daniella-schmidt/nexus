<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<script>
    function confirmDelete(id) {
        if (confirm('Tem certeza que deseja excluir esta rota?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/nexus/manager/routes/delete/${id}`;
            
            // Adicionar CSRF token se existir
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<!-- Header -->
<section class="gradient-hero py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-nexus-ink mb-2">
                    Gerenciar Rotas
                </h1>
                <p class="text-nexus-ink/70 text-lg">Configure as rotas de transporte disponíveis</p>
            </div>
            <a href="/nexus/manager/routes/create" class="frost px-6 py-3 rounded-xl text-nexus-ink font-medium shadow-soft hover:shadow-glow transition-all border border-white/40">
                <i class="bi bi-plus-lg mr-2"></i> Nova Rota
            </a>
        </div>
    </div>
</section>

<main class="container mx-auto py-8 px-4">
    <!-- Mensagens de Feedback -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="frost rounded-2xl p-4 mb-6 border border-green-200/50 shadow-soft">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 grid place-items-center">
                    <i class="bi bi-check-circle text-green-600"></i>
                </div>
                <span class="text-green-700 font-medium"><?php echo $_SESSION['success']; ?></span>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="frost rounded-2xl p-4 mb-6 border border-red-200/50 shadow-soft">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 grid place-items-center">
                    <i class="bi bi-exclamation-circle text-red-600"></i>
                </div>
                <span class="text-red-700 font-medium"><?php echo $_SESSION['error']; ?></span>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Tabela de Rotas -->
    <div class="frost rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-nexus-a/5">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Nome
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Trajeto
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Horários
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Dias da Semana
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-nexus-ink/10">
                    <?php foreach ($routes as $route): ?>
                    <tr class="hover:bg-nexus-b/5 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-nexus-ink">
                                <?php echo htmlspecialchars($route['name'] ?? 'N/A'); ?>
                            </div>
                            <div class="text-sm text-nexus-ink/70">
                                <?php echo htmlspecialchars($route['description'] ?? 'Sem descrição'); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-nexus-ink">
                                <strong>Partida:</strong> 
                                <?php 
                                $departure = $route['departure_location'] ?? $route['origin'] ?? 'Não informado';
                                echo htmlspecialchars($departure); 
                                ?>
                            </div>
                            <div class="text-sm text-nexus-ink/70">
                                <strong>Chegada:</strong> 
                                <?php 
                                $arrival = $route['arrival_location'] ?? $route['destination'] ?? 'Não informado';
                                echo htmlspecialchars($arrival); 
                                ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-nexus-ink">
                                <strong>Partida:</strong> 
                                <?php 
                                if (isset($route['departure_time'])) {
                                    echo date('H:i', strtotime($route['departure_time']));
                                } else {
                                    echo 'Não informado';
                                }
                                ?>
                            </div>
                            <div class="text-sm text-nexus-ink/70">
                                <strong>Chegada:</strong> 
                                <?php 
                                if (isset($route['arrival_time'])) {
                                    echo date('H:i', strtotime($route['arrival_time']));
                                } else {
                                    echo 'Não informado';
                                }
                                ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-nexus-ink">
                                <?php
                                $daysOfWeek = $route['days_of_week'] ?? '';
                                if (empty($daysOfWeek)) {
                                    echo 'Não definido';
                                } else {
                                    if (is_string($daysOfWeek)) {
                                        $daysArray = explode(',', $daysOfWeek);
                                    } else {
                                        $daysArray = $daysOfWeek;
                                    }
                                    
                                    $dayNames = [
                                        'mon' => 'Seg',
                                        'tue' => 'Ter',
                                        'wed' => 'Qua',
                                        'thu' => 'Qui',
                                        'fri' => 'Sex',
                                        'sat' => 'Sáb',
                                        'sun' => 'Dom'
                                    ];
                                    
                                    $displayDays = [];
                                    foreach ($daysArray as $day) {
                                        $day = trim($day);
                                        if (isset($dayNames[$day])) {
                                            $displayDays[] = $dayNames[$day];
                                        }
                                    }
                                    
                                    echo !empty($displayDays) ? implode(', ', $displayDays) : 'Não definido';
                                }
                                ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                $status = $route['status'] ?? 'Ativo';
                                $statusClass = match($status) {
                                    'Ativo', 'active', 'scheduled' => 'bg-green-100 text-green-800',
                                    'Inativo', 'inactive', 'cancelled' => 'bg-red-100 text-red-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'completed' => 'bg-blue-100 text-blue-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                echo $statusClass;
                                ?>">
                                <?php 
                                $statusText = match($status) {
                                    'Ativo', 'active', 'scheduled' => 'Ativo',
                                    'Inativo', 'inactive', 'cancelled' => 'Inativo',
                                    'in_progress' => 'Em Andamento',
                                    'completed' => 'Concluída',
                                    default => $status
                                };
                                echo htmlspecialchars($statusText); 
                                ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="/nexus/manager/routes/edit/<?php echo $route['id']; ?>" 
                               class="text-nexus-b hover:text-nexus-a mr-4 transition-colors" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $route['id']; ?>)" 
                                    class="text-nexus-f hover:text-nexus-e transition-colors" title="Excluir">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($routes)): ?>
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto rounded-2xl grid place-items-center mb-4 bg-nexus-b/10">
                <i class="bi bi-signpost text-nexus-b text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-nexus-ink mb-2">Nenhuma rota cadastrada</h3>
            <p class="text-nexus-ink/70 mb-4">Comece criando sua primeira rota de transporte.</p>
            <a href="/nexus/manager/routes/create" class="btn-primary inline-flex items-center">
                <i class="bi bi-plus-lg mr-2"></i> Criar Rota
            </a>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>