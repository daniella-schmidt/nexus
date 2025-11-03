<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Gerenciar Rotas</h1>
            <p class="text-nexus-ink/70">Defina e modifique rotas de transporte</p>
        </div>

        <!-- Add Route Button -->
        <div class="mb-6">
            <button onclick="showCreateModal()" class="bg-nexus-b text-white px-6 py-3 rounded-lg hover:bg-nexus-a transition-colors">
                <i class="bi bi-plus-circle mr-2"></i>Nova Rota
            </button>
        </div>

        <!-- Routes List -->
        <div class="frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Rotas Ativas</h2>

            <?php if (!empty($routes)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($routes as $route): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-nexus-b transition-colors">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="font-medium text-nexus-ink"><?php echo htmlspecialchars($route['name']); ?></h3>
                                    <p class="text-sm text-nexus-ink/70"><?php echo htmlspecialchars($route['origin']); ?> → <?php echo htmlspecialchars($route['destination']); ?></p>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editRoute(<?php echo htmlspecialchars(json_encode($route)); ?>)" class="text-nexus-b hover:text-nexus-a">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-nexus-ink/70">Horário:</span>
                                    <span class="text-nexus-ink"><?php echo htmlspecialchars($route['departure_time']); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-nexus-ink/70">Capacidade:</span>
                                    <span class="text-nexus-ink"><?php echo htmlspecialchars($route['max_capacity']); ?> passageiros</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-nexus-ink/70">Veículo:</span>
                                    <span class="text-nexus-ink"><?php echo htmlspecialchars($route['vehicle_id'] ?? 'Não atribuído'); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="bi bi-map text-4xl text-nexus-ink/30 mb-4"></i>
                    <h3 class="text-lg font-medium text-nexus-ink mb-2">Nenhuma rota encontrada</h3>
                    <p class="text-nexus-ink/70">Crie sua primeira rota para começar</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Create/Edit Route Modal -->
<div id="routeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 id="modalTitle" class="text-xl font-semibold text-nexus-ink">Nova Rota</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form id="routeForm" method="POST">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="route_id" id="routeId">

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-nexus-ink mb-2">Nome da Rota *</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                                placeholder="Ex: Rota Centro-Universidade"
                            >
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="origin" class="block text-sm font-medium text-nexus-ink mb-2">Origem *</label>
                                <input
                                    type="text"
                                    id="origin"
                                    name="origin"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                                    placeholder="Ex: Centro"
                                >
                            </div>
                            <div>
                                <label for="destination" class="block text-sm font-medium text-nexus-ink mb-2">Destino *</label>
                                <input
                                    type="text"
                                    id="destination"
                                    name="destination"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                                    placeholder="Ex: Universidade"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="departure_time" class="block text-sm font-medium text-nexus-ink mb-2">Horário de Saída *</label>
                            <input
                                type="time"
                                id="departure_time"
                                name="departure_time"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                            >
                        </div>

                        <div>
                            <label for="max_capacity" class="block text-sm font-medium text-nexus-ink mb-2">Capacidade Máxima *</label>
                            <input
                                type="number"
                                id="max_capacity"
                                name="max_capacity"
                                required
                                min="1"
                                max="100"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                                placeholder="Ex: 40"
                            >
                        </div>

                        <div>
                            <label for="vehicle_id" class="block text-sm font-medium text-nexus-ink mb-2">Veículo</label>
                            <select
                                id="vehicle_id"
                                name="vehicle_id"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                            >
                                <option value="">Selecionar veículo (opcional)</option>
                                <?php foreach ($vehicles as $vehicle): ?>
                                    <option value="<?php echo htmlspecialchars($vehicle['id']); ?>">
                                        <?php echo htmlspecialchars($vehicle['model']); ?> - <?php echo htmlspecialchars($vehicle['plate']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-3">
                        <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 bg-nexus-b text-white py-3 rounded-lg hover:bg-nexus-a transition-colors">
                            <i class="bi bi-check-circle mr-2"></i>Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showCreateModal() {
    document.getElementById('modalTitle').textContent = 'Nova Rota';
    document.getElementById('formAction').value = 'create';
    document.getElementById('routeId').value = '';
    document.getElementById('routeForm').reset();
    document.getElementById('routeModal').classList.remove('hidden');
}

function editRoute(route) {
    document.getElementById('modalTitle').textContent = 'Editar Rota';
    document.getElementById('formAction').value = 'update';
    document.getElementById('routeId').value = route.id;

    document.getElementById('name').value = route.name;
    document.getElementById('origin').value = route.origin;
    document.getElementById('destination').value = route.destination;
    document.getElementById('departure_time').value = route.departure_time;
    document.getElementById('max_capacity').value = route.max_capacity;
    document.getElementById('vehicle_id').value = route.vehicle_id || '';

    document.getElementById('routeModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('routeModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('routeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
