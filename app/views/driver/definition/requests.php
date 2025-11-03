<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Solicitações</h1>
            <p class="text-nexus-ink/70">Envie solicitações para manutenção, material ou suporte</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- New Request Form -->
            <div class="frost rounded-xl p-6">
                <h2 class="text-xl font-semibold text-nexus-ink mb-6">Nova Solicitação</h2>

                <form method="POST" class="space-y-4">
                    <div>
                        <label for="request_type" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-tag mr-2"></i>Tipo de Solicitação *
                        </label>
                        <select
                            id="request_type"
                            name="request_type"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        >
                            <option value="">Selecione o tipo</option>
                            <option value="Manutenção">Manutenção de Veículo</option>
                            <option value="Material">Material/Cartões</option>
                            <option value="Suporte">Suporte Técnico</option>
                            <option value="Treinamento">Treinamento</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-textarea-t mr-2"></i>Descrição Detalhada *
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            required
                            rows="4"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all resize-none"
                            placeholder="Descreva sua solicitação em detalhes..."
                        ></textarea>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="bi bi-info-circle text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <h4 class="text-blue-800 font-medium">Dicas para uma boa solicitação:</h4>
                                <ul class="text-blue-700 text-sm mt-1 space-y-1">
                                    <li>• Seja específico sobre o problema</li>
                                    <li>• Inclua detalhes técnicos quando possível</li>
                                    <li>• Mencione urgência se aplicável</li>
                                    <li>• Forneça contexto adicional se necessário</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-nexus-b text-white py-3 rounded-lg hover:bg-nexus-a transition-colors">
                        <i class="bi bi-send mr-2"></i>Enviar Solicitação
                    </button>
                </form>
            </div>

            <!-- Request History -->
            <div class="frost rounded-xl p-6">
                <h2 class="text-xl font-semibold text-nexus-ink mb-6">Histórico de Solicitações</h2>

                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $request): ?>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="font-medium text-nexus-ink"><?php echo htmlspecialchars($request['type']); ?></h4>
                                        <p class="text-sm text-nexus-ink/70"><?php echo date('d/m/Y H:i', strtotime($request['created_at'])); ?></p>
                                    </div>
                                    <span class="px-2 py-1 <?php
                                        switch($request['status']) {
                                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'approved': echo 'bg-green-100 text-green-800'; break;
                                            case 'rejected': echo 'bg-red-100 text-red-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                    ?> text-xs rounded-full">
                                        <?php
                                        switch($request['status']) {
                                            case 'pending': echo 'Pendente'; break;
                                            case 'approved': echo 'Aprovada'; break;
                                            case 'rejected': echo 'Rejeitada'; break;
                                            default: echo 'Desconhecido';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <p class="text-nexus-ink text-sm"><?php echo htmlspecialchars($request['description']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="bi bi-clipboard-list text-4xl text-nexus-ink/30 mb-4"></i>
                            <p class="text-nexus-ink/70">Nenhuma solicitação encontrada</p>
                            <p class="text-sm text-nexus-ink/50">Suas solicitações aparecerão aqui</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Request Templates -->
        <div class="mt-8 frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Modelos de Solicitação</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button onclick="useTemplate('Manutenção', 'Veículo apresenta problema no sistema elétrico. Faróis não funcionam adequadamente.')"
                        class="frost rounded-xl p-4 text-left hover:shadow-glow transition-all">
                    <i class="bi bi-tools text-2xl text-nexus-b mb-2"></i>
                    <h3 class="font-medium text-nexus-ink">Problema Elétrico</h3>
                    <p class="text-sm text-nexus-ink/70">Faróis, bateria, sistema elétrico</p>
                </button>

                <button onclick="useTemplate('Material', 'Solicito reposição de cartões de check-in. Estoque atual: 5 unidades.')"
                        class="frost rounded-xl p-4 text-left hover:shadow-glow transition-all">
                    <i class="bi bi-card-text text-2xl text-nexus-c mb-2"></i>
                    <h3 class="font-medium text-nexus-ink">Cartões Check-in</h3>
                    <p class="text-sm text-nexus-ink/70">Reposição de material</p>
                </button>

                <button onclick="useTemplate('Suporte', 'Dificuldade com o sistema de QR Code. Scanner não reconhece códigos válidos.')"
                        class="frost rounded-xl p-4 text-left hover:shadow-glow transition-all">
                    <i class="bi bi-headset text-2xl text-nexus-d mb-2"></i>
                    <h3 class="font-medium text-nexus-ink">Suporte Técnico</h3>
                    <p class="text-sm text-nexus-ink/70">Problemas com sistema</p>
                </button>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="mt-8 frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Contatos Úteis</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="p-4 bg-nexus-b/10 rounded-lg mb-3">
                        <i class="bi bi-wrench text-3xl text-nexus-b"></i>
                    </div>
                    <h3 class="font-medium text-nexus-ink">Oficina</h3>
                    <p class="text-sm text-nexus-ink/70">Manutenção de veículos</p>
                    <p class="text-sm text-nexus-ink font-mono">(11) 3333-3333</p>
                </div>

                <div class="text-center">
                    <div class="p-4 bg-nexus-c/10 rounded-lg mb-3">
                        <i class="bi bi-box-seam text-3xl text-nexus-c"></i>
                    </div>
                    <h3 class="font-medium text-nexus-ink">Almoxarifado</h3>
                    <p class="text-sm text-nexus-ink/70">Material e suprimentos</p>
                    <p class="text-sm text-nexus-ink font-mono">(11) 4444-4444</p>
                </div>

                <div class="text-center">
                    <div class="p-4 bg-nexus-d/10 rounded-lg mb-3">
                        <i class="bi bi-headset text-3xl text-nexus-d"></i>
                    </div>
                    <h3 class="font-medium text-nexus-ink">Suporte TI</h3>
                    <p class="text-sm text-nexus-ink/70">Problemas técnicos</p>
                    <p class="text-sm text-nexus-ink font-mono">(11) 5555-5555</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function useTemplate(type, description) {
    document.getElementById('request_type').value = type;
    document.getElementById('description').value = description;
    // Scroll to form
    document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
}
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
