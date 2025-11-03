<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Selecionar Veículo</h1>
            <p class="text-nexus-ink/70">Escolha o veículo que utilizará hoje</p>
        </div>

        <!-- Current Selection -->
        <?php if (isset($_SESSION['selected_vehicle'])): ?>
            <div class="mb-6 frost rounded-xl p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="bi bi-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-nexus-ink">Veículo Selecionado</h3>
                        <p class="text-nexus-ink/70">Veículo ID: <?php echo htmlspecialchars($_SESSION['selected_vehicle']); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Vehicle Selection -->
        <div class="frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Veículos Disponíveis</h2>

            <?php if (!empty($vehicles)): ?>
                <form method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($vehicles as $vehicle): ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-nexus-b transition-colors">
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="vehicle_id"
                                        value="<?php echo htmlspecialchars($vehicle['id']); ?>"
                                        class="mr-3"
                                        required
                                    >
                                    <div class="flex items-center">
                                        <div class="p-3 bg-nexus-b/10 rounded-lg mr-4">
                                            <i class="bi bi-car-front text-nexus-b text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-nexus-ink"><?php echo htmlspecialchars($vehicle['model']); ?></h3>
                                            <p class="text-sm text-nexus-ink/70">Placa: <?php echo htmlspecialchars($vehicle['plate']); ?></p>
                                            <p class="text-sm text-nexus-ink/70">Capacidade: <?php echo htmlspecialchars($vehicle['capacity']); ?> passageiros</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-nexus-b text-white py-3 rounded-lg hover:bg-nexus-a transition-colors">
                            <i class="bi bi-check-circle mr-2"></i>Confirmar Seleção
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="bi bi-car-front text-4xl text-nexus-ink/30 mb-4"></i>
                    <p class="text-nexus-ink/70">Nenhum veículo disponível</p>
                    <p class="text-sm text-nexus-ink/50 mt-2">Entre em contato com a central para atribuir veículos</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Vehicle Details -->
        <?php if (!empty($vehicles)): ?>
            <div class="mt-8 frost rounded-xl p-6">
                <h2 class="text-xl font-semibold text-nexus-ink mb-6">Detalhes dos Veículos</h2>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-nexus-ink font-medium">Modelo</th>
                                <th class="text-left py-3 px-4 text-nexus-ink font-medium">Placa</th>
                                <th class="text-left py-3 px-4 text-nexus-ink font-medium">Capacidade</th>
                                <th class="text-left py-3 px-4 text-nexus-ink font-medium">Status</th>
                                <th class="text-left py-3 px-4 text-nexus-ink font-medium">Última Manutenção</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 px-4 text-nexus-ink"><?php echo htmlspecialchars($vehicle['model']); ?></td>
                                    <td class="py-3 px-4 text-nexus-ink"><?php echo htmlspecialchars($vehicle['plate']); ?></td>
                                    <td class="py-3 px-4 text-nexus-ink"><?php echo htmlspecialchars($vehicle['capacity']); ?> passageiros</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Ativo</span>
                                    </td>
                                    <td class="py-3 px-4 text-nexus-ink"><?php echo htmlspecialchars($vehicle['last_maintenance'] ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
