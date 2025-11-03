<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<!-- Header -->
<section class="gradient-hero py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-nexus-ink mb-2">
                    Editar Rota
                </h1>
                <p class="text-nexus-ink/70 text-lg">Atualize as informações da rota</p>
            </div>
            <a href="/nexus/manager/routes" class="frost px-6 py-3 rounded-xl text-nexus-ink font-medium shadow-soft hover:shadow-glow transition-all border border-white/40">
                <i class="bi bi-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>
</section>

<main class="container mx-auto py-8 px-4">
    <!-- Mensagens de Feedback -->
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

    <!-- Formulário -->
    <div class="frost rounded-2xl shadow-soft p-8">
        <form action="/nexus/manager/routes/update/<?php echo $route['id']; ?>" method="POST">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Nome da Rota -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-nexus-ink mb-2">Nome da Rota *</label>
                    <input type="text" name="name" id="name" required
                        class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        value="<?php echo htmlspecialchars($route['name'] ?? ''); ?>"
                        placeholder="Ex: Rota Centro - IFSC">
                </div>

                <!-- Descrição -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-nexus-ink mb-2">Descrição</label>
                    <input type="text" name="description" id="description"
                        class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        value="<?php echo htmlspecialchars($route['description'] ?? ''); ?>"
                        placeholder="Ex: Rota de retorno do campus">
                </div>

                <!-- Local de Partida -->
                <div>
                    <label for="departure_location" class="block text-sm font-medium text-nexus-ink mb-2">Local de Partida *</label>
                    <input type="text" name="departure_location" id="departure_location" required
                        class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        value="<?php echo htmlspecialchars($route['departure_location'] ?? $route['origin'] ?? ''); ?>"
                        placeholder="Ex: IFSC - Campus Florianópolis">
                </div>

                <!-- Local de Chegada -->
                <div>
                    <label for="arrival_location" class="block text-sm font-medium text-nexus-ink mb-2">Local de Chegada *</label>
                    <input type="text" name="arrival_location" id="arrival_location" required
                        class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        value="<?php echo htmlspecialchars($route['arrival_location'] ?? $route['destination'] ?? ''); ?>"
                        placeholder="Ex: Centro - Terminal Urbano">
                </div>

                <!-- Horário de Partida -->
                <div>
                    <label for="departure_time" class="block text-sm font-medium text-nexus-ink mb-2">Horário de Partida *</label>
                    <input type="time" name="departure_time" id="departure_time" required
                        class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        value="<?php echo htmlspecialchars($route['departure_time'] ?? ''); ?>">
                </div>

                <!-- Horário de Chegada -->
                <div>
                    <label for="arrival_time" class="block text-sm font-medium text-nexus-ink mb-2">Horário de Chegada *</label>
                    <input type="time" name="arrival_time" id="arrival_time" required
                        class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        value="<?php echo htmlspecialchars($route['arrival_time'] ?? ''); ?>">
                </div>

                <!-- Dias da Semana -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-nexus-ink mb-4">Dias da Semana *</label>
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
                        
                        // Obter dias selecionados da rota
                        $selectedDays = [];
                        if (isset($route['days_of_week']) && !empty($route['days_of_week'])) {
                            if (is_string($route['days_of_week'])) {
                                $selectedDays = explode(',', $route['days_of_week']);
                                $selectedDays = array_map('trim', $selectedDays);
                            } else if (is_array($route['days_of_week'])) {
                                $selectedDays = $route['days_of_week'];
                            }
                        }
                        
                        foreach ($days as $label => $value): 
                            $isChecked = in_array($value, $selectedDays);
                        ?>
                        <div class="flex items-center p-3 rounded-xl border border-gray-300/60 bg-white/70 hover:bg-white transition-all">
                            <input type="checkbox" name="days_of_week[]" value="<?= $value ?>" 
                                id="day_<?= $value ?>" 
                                class="rounded border-gray-300 text-nexus-b focus:ring-nexus-b"
                                <?= $isChecked ? 'checked' : '' ?>>
                            <label for="day_<?= $value ?>" class="ml-3 text-sm font-medium text-nexus-ink"><?= $label ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label for="status" class="block text-sm font-medium text-nexus-ink mb-2">Status *</label>
                    <select name="status" id="status" required
                        class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all">
                        <?php
                        $currentStatus = $route['status'] ?? 'Ativo';
                        $statusOptions = [
                            'Ativo' => 'Ativo',
                            'Inativo' => 'Inativo',
                            'scheduled' => 'Agendada',
                            'in_progress' => 'Em Andamento', 
                            'completed' => 'Concluída',
                            'cancelled' => 'Cancelada'
                        ];
                        
                        foreach ($statusOptions as $value => $label): 
                            $selected = ($currentStatus === $value) ? 'selected' : '';
                        ?>
                        <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="/nexus/manager/routes" class="frost px-6 py-3 rounded-xl text-nexus-ink font-medium shadow-soft hover:shadow-glow transition-all border border-white/40">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl text-white font-medium shadow-soft hover:shadow-glow-lg transition-all" style="background:var(--nexus-a)">
                    Atualizar Rota
                </button>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>