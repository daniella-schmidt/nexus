<?php require_once __DIR__ . '/../../../layouts/header.php'; ?>

<!-- Header -->
<section class="gradient-hero py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-nexus-ink mb-2">
                    Novo Administrador
                </h1>
                <p class="text-nexus-ink/70 text-lg">Adicione um novo administrador ao sistema</p>
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
        <form action="/nexus/manager/users/create" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-nexus-ink mb-2">Nome Completo*</label>
                    <input type="text" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="name" name="name" required value="<?= $_POST['name'] ?? '' ?>">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-nexus-ink mb-2">Email*</label>
                    <input type="email" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="email" name="email" required value="<?= $_POST['email'] ?? '' ?>">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-nexus-ink mb-2">Senha*</label>
                    <input type="password" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="password" name="password" required minlength="6">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-nexus-ink mb-2">Telefone</label>
                    <input type="tel" class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" 
                           id="phone" name="phone" placeholder="(11) 99999-9999" value="<?= $_POST['phone'] ?? '' ?>">
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="/nexus/manager/users" class="frost px-6 py-3 rounded-xl text-nexus-ink font-medium shadow-soft hover:shadow-glow transition-all border border-white/40">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl text-white font-medium shadow-soft hover:shadow-glow-lg transition-all" style="background:var(--nexus-a)">
                    Cadastrar Administrador
                </button>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>