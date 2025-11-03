<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Exportar Relatórios</h1>
            <p class="text-nexus-ink/70">Exporte dados e relatórios para análise externa</p>
        </div>

        <!-- Export Options -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Quick Exports -->
            <div class="frost rounded-xl p-6">
                <h2 class="text-xl font-semibold text-nexus-ink mb-6">Exportações Rápidas</h2>

                <div class="space-y-4">
                    <a href="/driver/export/today-reservations" class="block frost rounded-lg p-4 hover:shadow-glow transition-all">
                        <div class="flex items-center">
                            <div class="p-3 bg-nexus-b/10 rounded-lg mr-4">
                                <i class="bi bi-calendar-check text-nexus-b text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-nexus-ink">Reservas de Hoje</h3>
                                <p class="text-sm text-nexus-ink/70">Lista completa das reservas do dia</p>
                            </div>
                        </div>
                    </a>

                    <a href="/driver/export/weekly-report" class="block frost rounded-lg p-4 hover:shadow-glow transition-all">
                        <div class="flex items-center">
                            <div class="p-3 bg-nexus-c/10 rounded-lg mr-4">
                                <i class="bi bi-bar-chart text-nexus-c text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-nexus-ink">Relatório Semanal</h3>
                                <p class="text-sm text-nexus-ink/70">Resumo da semana atual</p>
                            </div>
                        </div>
                    </a>

                    <a href="/driver/export/passenger-list" class="block frost rounded-lg p-4 hover:shadow-glow transition-all">
                        <div class="flex items-center">
                            <div class="p-3 bg-nexus-d/10 rounded-lg mr-4">
                                <i class="bi bi-people text-nexus-d text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-nexus-ink">Lista de Passageiros</h3>
                                <p class="text-sm text-nexus-ink/70">Todos os passageiros ativos</p>
                            </div>
                        </div>
                    </a>

                    <a href="/driver/export/vehicle-log" class="block frost rounded-lg p-4 hover:shadow-glow transition-all">
                        <div class="flex items-center">
                            <div class="p-3 bg-nexus-e/10 rounded-lg mr-4">
                                <i class="bi bi-car-front text-nexus-e text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-nexus-ink">Log de Veículos</h3>
                                <p class="text-sm text-nexus-ink/70">Histórico de uso dos veículos</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Custom Export -->
            <div class="frost rounded-xl p-6">
                <h2 class="text-xl font-semibold text-nexus-ink mb-6">Exportação Personalizada</h2>

                <form method="POST" class="space-y-4">
                    <div>
                        <label for="export_type" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-file-earmark-spreadsheet mr-2"></i>Tipo de Relatório *
                        </label>
                        <select
                            id="export_type"
                            name="export_type"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        >
                            <option value="">Selecione o tipo</option>
                            <option value="attendance">Relatório de Presença</option>
                            <option value="reservations">Reservas por Período</option>
                            <option value="routes">Dados das Rotas</option>
                            <option value="communication">Histórico de Comunicação</option>
                            <option value="requests">Solicitações Enviadas</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-nexus-ink mb-2">
                                <i class="bi bi-calendar mr-2"></i>Data Inicial
                            </label>
                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                            >
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-nexus-ink mb-2">
                                <i class="bi bi-calendar mr-2"></i>Data Final
                            </label>
                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="route_filter" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-map mr-2"></i>Filtrar por Rota (Opcional)
                        </label>
                        <select
                            id="route_filter"
                            name="route_filter"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        >
                            <option value="">Todas as rotas</option>
                            <?php foreach ($routes as $route): ?>
                                <option value="<?php echo htmlspecialchars($route['id']); ?>">
                                    <?php echo htmlspecialchars($route['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="format" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-filetype-csv mr-2"></i>Formato *
                        </label>
                        <select
                            id="format"
                            name="format"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        >
                            <option value="csv">CSV</option>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-nexus-b text-white py-3 rounded-lg hover:bg-nexus-a transition-colors">
                        <i class="bi bi-download mr-2"></i>Gerar e Baixar Relatório
                    </button>
                </form>
            </div>
        </div>

        <!-- Export History -->
        <div class="mt-8 frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Histórico de Exportações</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Data/Hora</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Tipo</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Período</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Formato</th>
                            <th class="text-center py-3 px-4 text-nexus-ink font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Export history would be populated here -->
                        <tr>
                            <td colspan="5" class="text-center py-8 text-nexus-ink/70">
                                Nenhum histórico de exportação
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Data Privacy Notice -->
        <div class="mt-8 frost rounded-xl p-6 bg-blue-50 border border-blue-200">
            <div class="flex items-start">
                <i class="bi bi-shield-check text-blue-600 text-2xl mt-1 mr-4"></i>
                <div>
                    <h3 class="text-lg font-medium text-blue-800 mb-2">Privacidade e Segurança</h3>
                    <ul class="text-blue-700 space-y-1 text-sm">
                        <li>• Os dados exportados são criptografados durante a transmissão</li>
                        <li>• Arquivos temporários são automaticamente excluídos após 24 horas</li>
                        <li>• Todas as exportações são registradas para auditoria</li>
                        <li>• Dados pessoais são tratados conforme LGPD</li>
                        <li>• Use apenas para fins profissionais e autorizados</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
