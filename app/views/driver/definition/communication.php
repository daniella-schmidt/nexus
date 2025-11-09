<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Comunicação com Central</h1>
            <p class="text-nexus-ink/70">Envie mensagens e receba atualizações da central de operações</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Send Message -->
            <div class="frost rounded-xl p-6">
                <h2 class="text-xl font-semibold text-nexus-ink mb-6">Enviar Mensagem</h2>

                <form method="POST" class="space-y-4">
                    <div>
                        <label for="message" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-chat-dots mr-2"></i>Mensagem *
                        </label>
                        <textarea
                            id="message"
                            name="message"
                            required
                            rows="4"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all resize-none"
                            placeholder="Digite sua mensagem para todos os estudantes..."
                        ></textarea>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-flag mr-2"></i>Prioridade
                        </label>
                        <select
                            id="priority"
                            name="priority"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        >
                            <option value="normal">Normal</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-nexus-b text-white py-3 rounded-lg hover:bg-nexus-a transition-colors">
                        <i class="bi bi-send mr-2"></i>Enviar Mensagem para Todos os Estudantes
                    </button>
                </form>
            </div>

            <!-- Message History -->
            <div class="frost rounded-xl p-6">
                <h2 class="text-xl font-semibold text-nexus-ink mb-6">Mensagens Recentes</h2>

                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $message): ?>
                            <div class="border border-gray-200 rounded-lg p-4 <?php echo $message['priority'] === 'high' ? 'border-l-4 border-l-red-500' : ''; ?>">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center">
                                        <i class="bi bi-person-circle text-nexus-b text-lg mr-2"></i>
                                        <span class="font-medium text-nexus-ink">Central de Operações</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <?php if ($message['priority'] === 'high'): ?>
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Alta Prioridade</span>
                                        <?php endif; ?>
                                        <span class="text-xs text-nexus-ink/70"><?php echo date('d/m H:i', strtotime($message['timestamp'])); ?></span>
                                    </div>
                                </div>
                                <p class="text-nexus-ink"><?php echo htmlspecialchars($message['message']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="bi bi-chat-square text-4xl text-nexus-ink/30 mb-4"></i>
                            <p class="text-nexus-ink/70">Nenhuma mensagem recente</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Ações Rápidas</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button onclick="sendQuickMessage('Atraso de 15 minutos na rota')" class="frost rounded-xl p-4 text-center hover:shadow-glow transition-all">
                    <i class="bi bi-clock text-2xl text-nexus-b mb-2"></i>
                    <p class="text-sm font-medium text-nexus-ink">Atraso</p>
                </button>

                <button onclick="sendQuickMessage('Veículo com problema mecânico')" class="frost rounded-xl p-4 text-center hover:shadow-glow transition-all">
                    <i class="bi bi-tools text-2xl text-nexus-c mb-2"></i>
                    <p class="text-sm font-medium text-nexus-ink">Problema Mecânico</p>
                </button>

                <button onclick="sendQuickMessage('Rota concluída com sucesso')" class="frost rounded-xl p-4 text-center hover:shadow-glow transition-all">
                    <i class="bi bi-check-circle text-2xl text-nexus-d mb-2"></i>
                    <p class="text-sm font-medium text-nexus-ink">Rota Concluída</p>
                </button>

                <button onclick="sendQuickMessage('Solicito suporte técnico')" class="frost rounded-xl p-4 text-center hover:shadow-glow transition-all">
                    <i class="bi bi-headset text-2xl text-nexus-e mb-2"></i>
                    <p class="text-sm font-medium text-nexus-ink">Suporte</p>
                </button>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="mt-8 frost rounded-xl p-6 bg-red-50 border border-red-200">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-medium text-red-800">Contato de Emergência</h3>
                    <p class="text-red-700 text-sm">Para situações de emergência, ligue imediatamente</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-lg">
                    <p class="text-sm text-red-700">Central de Operações</p>
                    <p class="font-mono text-red-800">(11) 9999-9999</p>
                </div>
                <div class="bg-white p-4 rounded-lg">
                    <p class="text-sm text-red-700">Suporte Técnico 24h</p>
                    <p class="font-mono text-red-800">(11) 8888-8888</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sendQuickMessage(message) {
    document.getElementById('message').value = message;
    document.getElementById('priority').value = 'high';
    // Scroll to form
    document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
}
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
