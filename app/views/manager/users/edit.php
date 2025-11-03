<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<!-- Header -->
<section class="gradient-hero py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-nexus-ink mb-2">
                    Editar Administrador
                </h1>
                <p class="text-nexus-ink/70 text-lg">Atualize as informações do administrador</p>
            </div>
            <a href="/nexus/manager/users" class="frost px-6 py-3 rounded-xl text-nexus-ink font-medium shadow-soft hover:shadow-glow transition-all border border-white/40">
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
        <form action="/nexus/manager/users/update/<?= $user['id'] ?>" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-nexus-ink mb-2">Nome Completo*</label>
                    <input type="text" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="name" name="name" required value="<?= htmlspecialchars($user['name']) ?>">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-nexus-ink mb-2">Email*</label>
                    <input type="email" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-nexus-ink mb-2">Nova Senha</label>
                    <input type="password" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="password" name="password" minlength="6" 
                           placeholder="Deixe em branco para manter a senha atual">
                    <p class="text-xs text-nexus-ink/50 mt-1">Mínimo 6 caracteres</p>
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-nexus-ink mb-2">Telefone</label>
                    <input type="tel" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="phone" name="phone" placeholder="(11) 99999-9999"
                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-nexus-ink mb-2">Status*</label>
                    <select class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                            id="status" name="status" required>
                        <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Ativo</option>
                        <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <div class="w-full p-3 rounded-xl border border-gray-300/60 bg-gray-100/50">
                        <label class="block text-sm font-medium text-nexus-ink mb-1">Tipo de Usuário</label>
                        <p class="text-nexus-ink/70">Administrador</p>
                        <input type="hidden" name="user_type" value="admin">
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-nexus-a/5 rounded-xl">
                <div class="flex items-center gap-3">
                    <i class="bi bi-info-circle text-nexus-a"></i>
                    <p class="text-sm text-nexus-ink/70">
                        <strong>Informações do sistema:</strong> 
                        Cadastrado em <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?>
                        <?php if ($user['updated_at'] && $user['updated_at'] != $user['created_at']): ?>
                        • Última atualização em <?= date('d/m/Y H:i', strtotime($user['updated_at'])) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="/nexus/manager/users" class="frost px-6 py-3 rounded-xl text-nexus-ink font-medium shadow-soft hover:shadow-glow transition-all border border-white/40">
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