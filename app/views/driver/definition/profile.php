<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Meu Perfil</h1>
            <p class="text-nexus-ink/70">Gerencie suas informações pessoais</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Info -->
            <div class="frost rounded-xl p-6">
                <div class="text-center mb-6">
                    <div class="relative w-24 h-24 mx-auto mb-4">
                        <?php if (!empty($user['profile_photo']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $user['profile_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>" 
                                 alt="Foto de perfil" 
                                 class="w-full h-full rounded-2xl object-cover border-2 border-nexus-b/30">
                        <?php else: ?>
                            <div class="w-full h-full rounded-2xl bg-nexus-b/10 flex items-center justify-center border-2 border-dashed border-nexus-b/30">
                                <i class="bi bi-person text-4xl text-nexus-b"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-xl font-semibold text-nexus-ink"><?php echo htmlspecialchars($user['name'] ?? 'Motorista'); ?></h3>
                    <p class="text-nexus-ink/70">Motorista</p>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-nexus-ink/70">Email:</span>
                        <span class="text-nexus-ink"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-nexus-ink/70">Telefone:</span>
                        <span class="text-nexus-ink"><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-nexus-ink/70">Status:</span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Ativo</span>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Form -->
            <div class="lg:col-span-2 frost rounded-xl p-6">
                <h2 class="text-xl font-semibold text-nexus-ink mb-6">Editar Perfil</h2>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <!-- Foto do Perfil -->
                    <div class="text-center">
                        <div class="relative w-24 h-24 mx-auto mb-4">
                            <?php if (!empty($user['profile_photo']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $user['profile_photo'])): ?>
                                <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>" 
                                     alt="Foto de perfil" 
                                     class="w-full h-full rounded-2xl object-cover border-2 border-nexus-b/30">
                            <?php else: ?>
                                <div class="w-full h-full rounded-2xl bg-nexus-b/10 flex items-center justify-center border-2 border-dashed border-nexus-b/30">
                                    <i class="bi bi-person text-4xl text-nexus-b"></i>
                                </div>
                            <?php endif; ?>
                            <label for="profile_photo" class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-nexus-f text-white flex items-center justify-center hover:bg-nexus-e transition-colors cursor-pointer">
                                <i class="bi bi-camera text-sm"></i>
                            </label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/gif" class="hidden">
                        </div>
                        <p class="text-sm text-nexus-ink/70">Clique para alterar foto (JPEG, PNG, GIF - máx. 2MB)</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-nexus-ink mb-2">
                                <i class="bi bi-person mr-2"></i>Nome Completo *
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-nexus-ink mb-2">
                                <i class="bi bi-envelope mr-2"></i>Email *
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                            >
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-nexus-ink mb-2">
                                <i class="bi bi-telephone mr-2"></i>Telefone
                            </label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                                placeholder="(11) 99999-9999"
                            >
                        </div>

                        <div>
                            <label for="emergency_contact" class="block text-sm font-medium text-nexus-ink mb-2">
                                <i class="bi bi-shield-exclamation mr-2"></i>Contato de Emergência
                            </label>
                            <input
                                type="tel"
                                id="emergency_contact"
                                name="emergency_contact"
                                value="<?php echo htmlspecialchars($user['emergency_contact'] ?? ''); ?>"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                                placeholder="(11) 99999-9999"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-geo-alt mr-2"></i>Endereço
                        </label>
                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all resize-none"
                            placeholder="Digite seu endereço completo"
                        ><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>

                    <!-- Password Change Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-nexus-ink mb-4">Alterar Senha</h3>
                        <p class="text-sm text-nexus-ink/70 mb-4">Deixe em branco para manter a senha atual</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-nexus-ink mb-2">
                                    <i class="bi bi-lock mr-2"></i>Nova Senha
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                                    placeholder="Digite a nova senha"
                                >
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-nexus-ink mb-2">
                                    <i class="bi bi-lock-fill mr-2"></i>Confirmar Nova Senha
                                </label>
                                <input
                                    type="password"
                                    id="confirm_password"
                                    name="confirm_password"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                                    placeholder="Confirme a nova senha"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="bg-nexus-b text-white px-6 py-3 rounded-lg hover:bg-nexus-a transition-colors">
                            <i class="bi bi-check-circle mr-2"></i>Salvar Alterações
                        </button>
                        <a href="/nexus/driver/dashboard" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors">
                            <i class="bi bi-arrow-left mr-2"></i>Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Stats -->
        <div class="mt-8 frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Estatísticas da Conta</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-nexus-b mb-2"><?php echo $user['routes_count'] ?? '0'; ?></div>
                    <p class="text-nexus-ink/70">Rotas Realizadas</p>
                </div>

                <div class="text-center">
                    <div class="text-3xl font-bold text-nexus-c mb-2"><?php echo $user['passengers_count'] ?? '0'; ?></div>
                    <p class="text-nexus-ink/70">Passageiros Transportados</p>
                </div>

                <div class="text-center">
                    <div class="text-3xl font-bold text-nexus-d mb-2"><?php echo $user['hours_worked'] ?? '0'; ?></div>
                    <p class="text-nexus-ink/70">Horas Trabalhadas</p>
                </div>

                <div class="text-center">
                    <div class="text-3xl font-bold text-nexus-e mb-2"><?php echo $user['punctuality'] ?? '100'; ?>%</div>
                    <p class="text-nexus-ink/70">Pontualidade</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profilePhotoInput = document.getElementById('profile_photo');
    
    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (!file) return;
            
            // Validar tipo de arquivo
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Por favor, selecione apenas imagens JPEG, PNG ou GIF.');
                this.value = ''; // Limpar input
                return;
            }
            
            // Validar tamanho (máximo 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('A imagem deve ter no máximo 2MB.');
                this.value = ''; // Limpar input
                return;
            }
            
            previewImage(this);
        });
    }

    // Password confirmation validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.style.borderColor = '#ef4444';
            confirmPassword.style.backgroundColor = '#fef2f2';
        } else {
            confirmPassword.style.borderColor = '#10b981';
            confirmPassword.style.backgroundColor = '#f0fdf4';
        }
    }

    if (password && confirmPassword) {
        password.addEventListener('input', validatePassword);
        confirmPassword.addEventListener('input', validatePassword);
    }
});

function previewImage(input) {
    const file = input.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const container = input.closest('.relative');
        let existingElement = container.querySelector('img, div');
        
        // Se já é uma imagem, apenas atualizar src
        if (existingElement && existingElement.tagName === 'IMG') {
            existingElement.src = e.target.result;
        } else {
            // Criar nova imagem
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Foto de perfil';
            img.className = 'w-full h-full rounded-2xl object-cover border-2 border-nexus-b/30';
            
            // Substituir elemento existente
            if (existingElement) {
                existingElement.replaceWith(img);
            } else {
                container.appendChild(img);
            }
        }
    };
    
    reader.onerror = function() {
        console.error('Erro ao ler arquivo');
        alert('Erro ao carregar a imagem. Tente novamente.');
    };
    
    reader.readAsDataURL(file);
}
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>