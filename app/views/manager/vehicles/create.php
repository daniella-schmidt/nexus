<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<!-- Header -->
<section class="gradient-hero py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-nexus-ink mb-2">
                    Novo Veículo
                </h1>
                <p class="text-nexus-ink/70 text-lg">Adicione um novo veículo à frota</p>
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
        <form action="/nexus/manager/vehicles" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-nexus-ink mb-2">Tipo de Veículo*</label>
                    <select class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                            id="type" name="type" required>
                        <option value="">Selecione...</option>
                        <option value="Ônibus">Ônibus</option>
                        <option value="Micro-ônibus">Micro-ônibus</option>
                        <option value="Van">Van</option>
                        <option value="Carro">Carro</option>
                    </select>
                </div>
                <div>
                    <label for="plate" class="block text-sm font-medium text-nexus-ink mb-2">Placa*</label>
                    <input type="text" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="plate" name="plate" required pattern="[A-Z]{3}[0-9][0-9A-Z][0-9]{2}"
                           placeholder="ABC1D23">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="brand" class="block text-sm font-medium text-nexus-ink mb-2">Marca*</label>
                    <input type="text" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="brand" name="brand" required>
                </div>
                <div>
                    <label for="model" class="block text-sm font-medium text-nexus-ink mb-2">Modelo*</label>
                    <input type="text" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="model" name="model" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="year" class="block text-sm font-medium text-nexus-ink mb-2">Ano*</label>
                    <input type="number" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="year" name="year" required min="2000" max="2025">
                </div>
                <div>
                    <label for="capacity" class="block text-sm font-medium text-nexus-ink mb-2">Capacidade de Passageiros*</label>
                    <input type="number" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="capacity" name="capacity" required min="1">
                </div>
                <div>
                    <label for="mileage" class="block text-sm font-medium text-nexus-ink mb-2">Quilometragem Atual*</label>
                    <input type="number" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="mileage" name="mileage" required min="0" step="1">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="fuel_type" class="block text-sm font-medium text-nexus-ink mb-2">Tipo de Combustível*</label>
                    <select class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                            id="fuel_type" name="fuel_type" required>
                        <option value="">Selecione...</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Biodiesel">Biodiesel</option>
                        <option value="Gás Natural">Gás Natural</option>
                        <option value="Híbrido">Híbrido</option>
                        <option value="Elétrico">Elétrico</option>
                    </select>
                </div>
                <div>
                    <label for="last_maintenance" class="block text-sm font-medium text-nexus-ink mb-2">Última Manutenção*</label>
                    <input type="date" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="last_maintenance" name="last_maintenance" required>
                </div>
                <div>
                    <label for="next_maintenance" class="block text-sm font-medium text-nexus-ink mb-2">Próxima Manutenção*</label>
                    <input type="date" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="next_maintenance" name="next_maintenance" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="chassis_number" class="block text-sm font-medium text-nexus-ink mb-2">Número do Chassi*</label>
                    <input type="text" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="chassis_number" name="chassis_number" required 
                           pattern="[A-HJ-NPR-Z0-9]{17}" placeholder="9BW ZZZ377 VT 004251">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-nexus-ink mb-2">Status*</label>
                    <select class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                            id="status" name="status" required>
                        <option value="">Selecione...</option>
                        <option value="Ativo">Ativo</option>
                        <option value="Em Manutenção">Em Manutenção</option>
                        <option value="Em Reparo">Em Reparo</option>
                        <option value="Inativo">Inativo</option>
                        <option value="Vendido">Vendido</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-nexus-ink mb-2">Observações Adicionais</label>
                <textarea class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                          id="notes" name="notes" rows="3" 
                          placeholder="Informações adicionais sobre o veículo, como adaptações especiais, histórico relevante, etc."></textarea>
            </div>
            
            <div class="mt-8 flex justify-end space-x-4">
                <a href="/nexus/manager/vehicles" class="frost px-6 py-3 rounded-xl text-nexus-ink font-medium shadow-soft hover:shadow-glow transition-all border border-white/40">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl text-white font-medium shadow-soft hover:shadow-glow-lg transition-all" style="background:var(--nexus-a)">
                    Salvar Veículo
                </button>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>