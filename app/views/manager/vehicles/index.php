<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<script>
    function confirmDelete(id) {
        if (confirm('Tem certeza que deseja excluir este veículo?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/nexus/manager/vehicles/delete/${id}`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<!-- Header -->
<section class="gradient-hero py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-nexus-ink mb-2">
                    Gerenciar Veículos
                </h1>
                <p class="text-nexus-ink/70 text-lg">Gerencie a frota de veículos do sistema</p>
            </div>
            <a href="/nexus/manager/vehicles/create" class="frost px-6 py-3 rounded-xl text-nexus-ink font-medium shadow-soft hover:shadow-glow transition-all border border-white/40">
                <i class="bi bi-plus-lg mr-2"></i> Novo Veículo
            </a>
        </div>
    </div>
</section>

<main class="container mx-auto py-8 px-4">
    <!-- Mensagens de Feedback -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="frost rounded-2xl p-4 mb-6 border border-green-200/50 shadow-soft" data-aos="fade-up">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 grid place-items-center">
                    <i class="bi bi-check-circle text-green-600"></i>
                </div>
                <span class="text-green-700 font-medium"><?php echo $_SESSION['success']; ?></span>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="frost rounded-2xl p-4 mb-6 border border-red-200/50 shadow-soft" data-aos="fade-up">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 grid place-items-center">
                    <i class="bi bi-exclamation-circle text-red-600"></i>
                </div>
                <span class="text-red-700 font-medium"><?php echo $_SESSION['error']; ?></span>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Tabela de Veículos -->
    <div class="frost rounded-2xl shadow-soft overflow-hidden" data-aos="fade-up">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-nexus-a/5">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Placa
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Veículo
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Capacidade
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Última Manutenção
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-nexus-ink uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-nexus-ink/10">
                    <?php foreach ($vehicles as $vehicle): ?>
                    <tr class="hover:bg-nexus-b/5 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-nexus-ink">
                                <?php echo htmlspecialchars($vehicle['plate']); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-nexus-ink">
                                <?php echo htmlspecialchars($vehicle['type']); ?>
                            </div>
                            <div class="text-sm text-nexus-ink/70">
                                <?php echo htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']); ?>
                            </div>
                            <div class="text-sm text-nexus-ink/50">
                                <?php echo htmlspecialchars($vehicle['year']); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-nexus-ink">
                                <?php echo htmlspecialchars($vehicle['capacity']); ?> lugares
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                $statusClass = match($vehicle['status']) {
                                    'Ativo' => 'bg-green-100 text-green-800',
                                    'Em Manutenção' => 'bg-yellow-100 text-yellow-800',
                                    'Em Reparo' => 'bg-orange-100 text-orange-800',
                                    'Inativo' => 'bg-red-100 text-red-800',
                                    'Vendido' => 'bg-gray-100 text-gray-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                echo $statusClass;
                                ?>">
                                <?php echo htmlspecialchars($vehicle['status']); 
                                ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-nexus-ink/70">
                            <?php echo $vehicle['last_maintenance'] ? date('d/m/Y', strtotime($vehicle['last_maintenance'])) : 'N/A'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="/nexus/manager/vehicles/edit/<?php echo $vehicle['id']; ?>" 
                               class="text-nexus-b hover:text-nexus-a mr-4 transition-colors" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $vehicle['id']; ?>)" 
                                    class="text-nexus-f hover:text-nexus-e transition-colors" title="Excluir">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($vehicles)): ?>
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto rounded-2xl grid place-items-center mb-4 bg-nexus-b/10">
                <i class="bi bi-inbox text-nexus-b text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-nexus-ink mb-2">Nenhum veículo cadastrado</h3>
            <p class="text-nexus-ink/70 mb-4">Comece adicionando seu primeiro veículo ao sistema.</p>
            <a href="/nexus/manager/vehicles/create" class="btn-primary inline-flex items-center">
                <i class="bi bi-plus-lg mr-2"></i> Adicionar Veículo
            </a>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>