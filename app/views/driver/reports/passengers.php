<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Lista de Passageiros</h1>
            <p class="text-nexus-ink/70">Visualize passageiros por rota e data</p>
        </div>

        <!-- Filters -->
        <div class="frost rounded-xl p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="route_id" class="block text-sm font-medium text-nexus-ink mb-2">
                        <i class="bi bi-map mr-2"></i>Rota
                    </label>
                    <select
                        id="route_id"
                        name="route_id"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                    >
                        <option value="">Todas as rotas</option>
                        <?php foreach ($routes as $route): ?>
                            <option value="<?php echo htmlspecialchars($route['id']); ?>" <?php echo ($selectedRoute == $route['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($route['name']); ?> (<?php echo htmlspecialchars($route['origin']); ?> → <?php echo htmlspecialchars($route['destination']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-nexus-ink mb-2">
                        <i class="bi bi-calendar mr-2"></i>Data
                    </label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="<?php echo htmlspecialchars($selectedDate); ?>"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                    >
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-nexus-b text-white py-3 rounded-lg hover:bg-nexus-a transition-colors">
                        <i class="bi bi-search mr-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Passengers List -->
        <div class="frost rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-nexus-ink">
                    Passageiros
                    <?php if ($selectedRoute): ?>
                        <span class="text-sm text-nexus-ink/70 ml-2">
                            (<?php echo htmlspecialchars($routes[array_search($selectedRoute, array_column($routes, 'id'))]['name'] ?? 'Rota'); ?>)
                        </span>
                    <?php endif; ?>
                    <span class="text-sm text-nexus-ink/70 ml-2">
                        - <?php echo date('d/m/Y', strtotime($selectedDate)); ?>
                    </span>
                </h2>

                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                        <?php echo count($passengers ?? []); ?> passageiros
                    </span>
                    <a href="/driver/export?date=<?php echo htmlspecialchars($selectedDate); ?>&route_id=<?php echo htmlspecialchars($selectedRoute); ?>" class="bg-nexus-c text-white px-4 py-2 rounded-lg hover:bg-nexus-b transition-colors">
                        <i class="bi bi-download mr-2"></i>Exportar CSV
                    </a>
                </div>
            </div>

            <?php if (!empty($passengers)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-nexus-ink font-medium">Nome</th>
                                <th class="text-left py-3 px-4 text-nexus-ink font-medium">Matrícula</th>
                                <th class="text-left py-3 px-4 text-nexus-ink font-medium">Curso</th>
                                <th class="text-left py-3 px-4 text-nexus-ink font-medium">Status</th>
                                <th class="text-left py-3 px-4 text-nexus-ink font-medium">Data Reserva</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($passengers as $passenger): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4 text-nexus-ink font-medium">
                                        <?php echo htmlspecialchars($passenger['student_name']); ?>
                                    </td>
                                    <td class="py-3 px-4 text-nexus-ink">
                                        <?php echo htmlspecialchars($passenger['matricula']); ?>
                                    </td>
                                    <td class="py-3 px-4 text-nexus-ink">
                                        <?php echo htmlspecialchars($passenger['curso']); ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                            Confirmada
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-nexus-ink">
                                        <?php echo date('d/m/Y H:i', strtotime($passenger['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="bi bi-people text-4xl text-nexus-ink/30 mb-4"></i>
                    <h3 class="text-lg font-medium text-nexus-ink mb-2">Nenhum passageiro encontrado</h3>
                    <p class="text-nexus-ink/70">
                        <?php if ($selectedRoute): ?>
                            Não há passageiros confirmados para esta rota na data selecionada.
                        <?php else: ?>
                            Selecione uma rota para visualizar os passageiros.
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Summary Stats -->
        <?php if (!empty($passengers)): ?>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="frost rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold text-nexus-b mb-2"><?php echo count($passengers); ?></div>
                    <p class="text-nexus-ink/70">Total de Passageiros</p>
                </div>

                <div class="frost rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold text-nexus-c mb-2">
                        <?php
                        $courses = array_count_values(array_column($passengers, 'curso'));
                        echo count($courses);
                        ?>
                    </div>
                    <p class="text-nexus-ink/70">Cursos Diferentes</p>
                </div>

                <div class="frost rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold text-nexus-d mb-2">
                        <?php
                        $avgReservations = count($passengers) / max(1, count($routes));
                        echo number_format($avgReservations, 1);
                        ?>
                    </div>
                    <p class="text-nexus-ink/70">Média por Rota</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
