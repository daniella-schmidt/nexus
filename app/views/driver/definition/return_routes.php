<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Rotas de Retorno</h1>
            <p class="text-nexus-ink/70">Defina rotas de retorno para seus veículos</p>
        </div>

        <!-- Current Vehicle Selection -->
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

        <!-- Return Route Form -->
        <div class="frost rounded-xl p-6 mb-8">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Definir Rota de Retorno</h2>

            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-car-front mr-2"></i>Veículo *
                        </label>
                        <select
                            id="vehicle_id"
                            name="vehicle_id"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        >
                            <option value="">Selecione um veículo</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?php echo htmlspecialchars($vehicle['id']); ?>">
                                    <?php echo htmlspecialchars($vehicle['model']); ?> - <?php echo htmlspecialchars($vehicle['plate']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="return_time" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-clock mr-2"></i>Horário de Retorno *
                        </label>
                        <input
                            type="time"
                            id="return_time"
                            name="return_time"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        >
                    </div>
                </div>

                <div>
                    <label for="route_details" class="block text-sm font-medium text-nexus-ink mb-2">
                        <i class="bi bi-map mr-2"></i>Detalhes da Rota de Retorno *
                    </label>
                    <textarea
                        id="route_details"
                        name="route_details"
                        required
                        rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all resize-none"
                        placeholder="Descreva a rota de retorno: pontos de parada, horários estimados, condições especiais..."
                    ></textarea>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="bi bi-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="text-yellow-800 font-medium">Importante:</h4>
                            <ul class="text-yellow-700 text-sm mt-1 space-y-1">
                                <li>• Certifique-se de que o veículo está liberado para retorno</li>
                                <li>• Verifique condições de tráfego e tempo estimado</li>
                                <li>• Informe qualquer passageiro remanescente</li>
                                <li>• Mantenha contato com a central durante o retorno</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-nexus-b text-white py-3 rounded-lg hover:bg-nexus-a transition-colors">
                    <i class="bi bi-check-circle mr-2"></i>Definir Rota de Retorno
                </button>
            </form>
        </div>

        <!-- Active Return Routes -->
        <div class="frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Rotas de Retorno Ativas</h2>

            <div class="space-y-4">
                <!-- This would be populated with active return routes -->
                <div class="text-center py-8">
                    <i class="bi bi-map text-4xl text-nexus-ink/30 mb-4"></i>
                    <h3 class="text-lg font-medium text-nexus-ink mb-2">Nenhuma rota de retorno ativa</h3>
                    <p class="text-nexus-ink/70">Defina rotas de retorno para acompanhar o status dos veículos</p>
                </div>
            </div>
        </div>

        <!-- Route History -->
        <div class="mt-8 frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Histórico de Rotas de Retorno</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Data</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Veículo</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Horário</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Status</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Route history would be populated here -->
                        <tr>
                            <td colspan="5" class="text-center py-8 text-nexus-ink/70">
                                Nenhum histórico de rotas de retorno
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="/driver/communication" class="frost rounded-xl p-6 text-center hover:shadow-glow transition-all">
                <i class="bi bi-chat-dots text-3xl text-nexus-b mb-3"></i>
                <p class="font-medium text-nexus-ink">Comunicar</p>
                <p class="text-sm text-nexus-ink/70">Avisar central</p>
            </a>

            <a href="/driver/dashboard" class="frost rounded-xl p-6 text-center hover:shadow-glow transition-all">
                <i class="bi bi-speedometer2 text-3xl text-nexus-c mb-3"></i>
                <p class="font-medium text-nexus-ink">Dashboard</p>
                <p class="text-sm text-nexus-ink/70">Ver status</p>
            </a>

            <a href="/driver/requests" class="frost rounded-xl p-6 text-center hover:shadow-glow transition-all">
                <i class="bi bi-tools text-3xl text-nexus-d mb-3"></i>
                <p class="font-medium text-nexus-ink">Solicitar</p>
                <p class="text-sm text-nexus-ink/70">Manutenção</p>
            </a>

            <a href="/driver/profile" class="frost rounded-xl p-6 text-center hover:shadow-glow transition-all">
                <i class="bi bi-person text-3xl text-nexus-e mb-3"></i>
                <p class="font-medium text-nexus-ink">Perfil</p>
                <p class="text-sm text-nexus-ink/70">Atualizar</p>
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
