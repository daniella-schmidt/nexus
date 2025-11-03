<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<!-- Header -->
<section class="gradient-hero py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-nexus-ink mb-2">
                    Editar Veículo
                </h1>
                <p class="text-nexus-ink/70 text-lg">Atualize as informações do veículo</p>
            </div>
            <a href="/nexus/manager/vehicles" class="frost px-6 py-3 rounded-xl text-nexus-ink font-medium shadow-soft hover:shadow-glow transition-all border border-white/40">
                <i class="bi bi-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>
</section>

<main class="container mx-auto py-8 px-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="frost rounded-2xl p-4 mb-6 border border-red-200/50 shadow-soft">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 grid place-items-center">
                    <i class="bi bi-exclamation-circle text-red-600"></i>
                </div>
                <span class="text-red-700 font-medium"><?= $_SESSION['error']; ?></span>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="frost rounded-2xl shadow-soft p-8">
        <form action="/nexus/manager/vehicles/update/<?= $vehicle['id'] ?>" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-nexus-ink mb-2">Tipo de Veículo*</label>
                    <select class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                            id="type" name="type" required>
                        <option value="">Selecione...</option>
                        <option value="Ônibus" <?= $vehicle['type'] === 'Ônibus' ? 'selected' : '' ?>>Ônibus</option>
                        <option value="Micro-ônibus" <?= $vehicle['type'] === 'Micro-ônibus' ? 'selected' : '' ?>>Micro-ônibus</option>
                        <option value="Van" <?= $vehicle['type'] === 'Van' ? 'selected' : '' ?>>Van</option>
                        <option value="Carro" <?= $vehicle['type'] === 'Carro' ? 'selected' : '' ?>>Carro</option>
                    </select>
                </div>
                <div>
                    <label for="plate" class="block text-sm font-medium text-nexus-ink mb-2">Placa*</label>
                    <input type="text" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="plate" name="plate" required pattern="[A-Z]{3}[0-9][0-9A-Z][0-9]{2}"
                           placeholder="ABC1D23" value="<?= htmlspecialchars($vehicle['plate']) ?>">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="brand" class="block text-sm font-medium text-nexus-ink mb-2">Marca*</label>
                    <input type="text" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="brand" name="brand" required value="<?= htmlspecialchars($vehicle['brand']) ?>">
                </div>
                <div>
                    <label for="model" class="block text-sm font-medium text-nexus-ink mb-2">Modelo*</label>
                    <input type="text" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="model" name="model" required value="<?= htmlspecialchars($vehicle['model']) ?>">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="year" class="block text-sm font-medium text-nexus-ink mb-2">Ano*</label>
                    <input type="number" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="year" name="year" required min="2000" max="2025" value="<?= htmlspecialchars($vehicle['year']) ?>">
                </div>
                <div>
                    <label for="capacity" class="block text-sm font-medium text-nexus-ink mb-2">Capacidade de Passageiros*</label>
                    <input type="number" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="capacity" name="capacity" required min="1" value="<?= htmlspecialchars($vehicle['capacity']) ?>">
                </div>
                <div>
                    <label for="mileage" class="block text-sm font-medium text-nexus-ink mb-2">Quilometragem Atual*</label>
                    <input type="number" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="mileage" name="mileage" required min="0" step="1" value="<?= htmlspecialchars($vehicle['mileage']) ?>">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="fuel_type" class="block text-sm font-medium text-nexus-ink mb-2">Tipo de Combustível*</label>
                    <select class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                            id="fuel_type" name="fuel_type" required>
                        <option value="">Selecione...</option>
                        <option value="Diesel" <?= $vehicle['fuel_type'] === 'Diesel' ? 'selected' : '' ?>>Diesel</option>
                        <option value="Biodiesel" <?= $vehicle['fuel_type'] === 'Biodiesel' ? 'selected' : '' ?>>Biodiesel</option>
                        <option value="Gás Natural" <?= $vehicle['fuel_type'] === 'Gás Natural' ? 'selected' : '' ?>>Gás Natural</option>
                        <option value="Híbrido" <?= $vehicle['fuel_type'] === 'Híbrido' ? 'selected' : '' ?>>Híbrido</option>
                        <option value="Elétrico" <?= $vehicle['fuel_type'] === 'Elétrico' ? 'selected' : '' ?>>Elétrico</option>
                    </select>
                </div>
                <div>
                    <label for="last_maintenance" class="block text-sm font-medium text-nexus-ink mb-2">Última Manutenção*</label>
                    <input type="date" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="last_maintenance" name="last_maintenance" required value="<?= htmlspecialchars($vehicle['last_maintenance']) ?>">
                </div>
                <div>
                    <label for="next_maintenance" class="block text-sm font-medium text-nexus-ink mb-2">Próxima Manutenção*</label>
                    <input type="date" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="next_maintenance" name="next_maintenance" required value="<?= htmlspecialchars($vehicle['next_maintenance']) ?>">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="chassis_number" class="block text-sm font-medium text-nexus-ink mb-2">Número do Chassi*</label>
                    <input type="text" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="chassis_number" name="chassis_number" required 
                           pattern="[A-HJ-NPR-Z0-9]{17}" placeholder="9BW ZZZ377 VT 004251"
                           value="<?= htmlspecialchars($vehicle['chassis_number']) ?>">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-nexus-ink mb-2">Status*</label>
                    <select class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                            id="status" name="status" required>
                        <option value="">Selecione...</option>
                        <option value="Ativo" <?= $vehicle['status'] === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                        <option value="Em Manutenção" <?= $vehicle['status'] === 'Em Manutenção' ? 'selected' : '' ?>>Em Manutenção</option>
                        <option value="Em Reparo" <?= $vehicle['status'] === 'Em Reparo' ? 'selected' : '' ?>>Em Reparo</option>
                        <option value="Inativo" <?= $vehicle['status'] === 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                        <option value="Vendido" <?= $vehicle['status'] === 'Vendido' ? 'selected' : '' ?>>Vendido</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-nexus-ink mb-2">Observações Adicionais</label>
                <textarea class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                          id="notes" name="notes" rows="3" 
                          placeholder="Informações adicionais sobre o veículo, como adaptações especiais, histórico relevante, etc."><?= htmlspecialchars($vehicle['notes'] ?? '') ?></textarea>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="/nexus/manager/vehicles" class="frost px-6 py-3 rounded-xl text-nexus-ink font-medium shadow-soft hover:shadow-glow transition-all border border-white/40">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl text-white font-medium shadow-soft hover:shadow-glow-lg transition-all" style="background:var(--nexus-a)">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>