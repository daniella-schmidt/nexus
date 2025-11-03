<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Meu Perfil - NEXUS</title>
  <!-- Tailwind (Play CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Inter font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
          colors: {
            nexus: {
              ink: '#0c0f14',
              base: '#f5f7fd',
              a: '#4b687f',   // primária escura
              b: '#63a8ca',   // destaque 1
              c: '#85b0d8',   // destaque 2
              d: '#af91bd',   // destaque 3
              e: '#966e92',   // destaque 4
              f: '#7c4c68'    // destaque 5
            }
          },
          boxShadow: {
            soft: '0 10px 30px rgba(13, 18, 28, .12)',
            glow: '0 0 0 4px rgba(99,168,202,.18)',
            'glow-lg': '0 0 20px 8px rgba(99,168,202,.25)'
          }
        }
      }
    }
  </script>

  <style>
    :root{
      --nexus-a:#4b687f; --nexus-b:#63a8ca; --nexus-c:#85b0d8; --nexus-d:#af91bd; --nexus-e:#966e92; --nexus-f:#7c4c68;
    }
    .gradient-surface{
      background-image:
        radial-gradient(1200px 600px at 10% -10%, rgba(99,168,202,.25), transparent 60%),
        radial-gradient(900px 500px at 90% 10%, rgba(175,145,189,.22), transparent 60%),
        linear-gradient(180deg, #fbfcff 0%, #f5f7fd 60%, #eef2f7 100%);
    }
    .frost{ backdrop-filter: blur(16px) saturate(130%); background: rgba(255,255,255,.5); border: 1px solid rgba(255,255,255,.4); }
  </style>
</head>
<body class="bg-nexus-base text-nexus-ink gradient-surface min-h-screen">
  <!-- Navbar -->
  <header class="sticky top-0 z-40 backdrop-blur-md bg-white/70 border-b border-white/60">
    <nav class="container mx-auto flex items-center justify-between py-3 px-4">
      <div class="flex items-center gap-3">
        <div class="relative w-10 h-10 rounded-2xl grid place-items-center" style="box-shadow: 0 0 0 8px rgba(133,176,216,.18)">
          <span class="w-6 h-6 inline-grid place-items-center rounded-xl" style="background:var(--nexus-f)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2l3.3 6.7L22 12l-6.7 3.3L12 22l-3.3-6.7L2 12l6.7-3.3L12 2z"/>
            </svg>
          </span>
        </div>
        <span class="text-xl font-extrabold tracking-tight" style="color:var(--nexus-a)">NEXUS</span>
      </div>

      <div class="flex items-center gap-4">
        <a href="/nexus/dashboard" class="text-sm text-nexus-ink/70 hover:text-nexus-ink transition-colors">
          <i class="bi bi-arrow-left mr-1"></i>Voltar
        </a>
      </div>
    </nav>
  </header>

  <div class="container mx-auto p-6">
    <div class="max-w-2xl mx-auto">
      <h1 class="text-3xl font-black mb-2 text-nexus-ink">Meu Perfil</h1>
      <p class="text-nexus-ink/70 mb-8">Atualize seus dados pessoais</p>

      <!-- Formulário de Perfil -->
      <div class="frost rounded-2xl p-8 shadow-soft">
        <form method="POST" enctype="multipart/form-data">
          <!-- Foto do Perfil -->
          <div class="text-center mb-8">
              <div class="relative w-24 h-24 mx-auto mb-4">
                  <?php if (!empty($profile_photo) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/nexus/public' . $profile_photo)): ?>
                    <img src="<?php echo '/nexus/public' . htmlspecialchars($profile_photo); ?>"
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

          <!-- Dados Pessoais -->
          <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-sm font-medium mb-2 text-nexus-ink">Nome Completo</label>
              <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>"
                     class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-nexus-ink">Email</label>
              <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>"
                     class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
            </div>
          </div>

          <!-- Dados Acadêmicos -->
          <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-sm font-medium mb-2 text-nexus-ink">Matrícula</label>
              <input type="text" name="matricula" value="<?php echo htmlspecialchars($_SESSION['user_matricula'] ?? ''); ?>"
                     class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-nexus-ink">Curso</label>
              <input type="text" name="curso" value="<?php echo htmlspecialchars($_SESSION['user_curso'] ?? ''); ?>"
                     class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent" required>
            </div>
          </div>

          <!-- Contato -->
          <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div>
              <label class="block text-sm font-medium mb-2 text-nexus-ink">Telefone</label>
              <input type="tel" name="phone" value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?>"
                     class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent">
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-nexus-ink">Contato de Emergência</label>
              <input type="tel" name="emergency_contact" value="<?php echo htmlspecialchars($_SESSION['user_emergency_contact'] ?? ''); ?>"
                     class="w-full p-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent">
            </div>
          </div>

          <!-- Botões -->
          <div class="flex flex-col sm:flex-row gap-4">
            <button type="submit" class="flex-1 py-3 px-6 rounded-xl text-white font-medium shadow-soft hover:shadow-glow transition-all" style="background:var(--nexus-a)">
              <i class="bi bi-check-circle mr-2"></i>Salvar Alterações
            </button>
            <a href="/nexus/student/digital-card" class="flex-1 py-3 px-6 rounded-xl border-2 border-nexus-b text-nexus-b font-medium hover:bg-nexus-b hover:text-white transition-all text-center">
              <i class="bi bi-card-text mr-2"></i>Ver Carteirinha
            </a>
          </div>
        </form>
      </div>

      <!-- Histórico de Uso -->
      <div class="frost rounded-2xl p-6 shadow-soft mt-8">
        <h2 class="text-xl font-semibold mb-4 text-nexus-ink">Histórico de Uso</h2>
        <div class="space-y-4">
          <div class="flex items-center justify-between p-4 bg-white/50 rounded-lg">
            <div>
              <p class="font-medium text-nexus-ink">Total de Reservas</p>
              <p class="text-sm text-nexus-ink/70">Este mês</p>
            </div>
            <div class="text-2xl font-bold text-nexus-b">12</div>
          </div>
          <div class="flex items-center justify-between p-4 bg-white/50 rounded-lg">
            <div>
              <p class="font-medium text-nexus-ink">Presença Confirmada</p>
              <p class="text-sm text-nexus-ink/70">Este mês</p>
            </div>
            <div class="text-2xl font-bold text-green-600">10</div>
          </div>
          <div class="flex items-center justify-between p-4 bg-white/50 rounded-lg">
            <div>
              <p class="font-medium text-nexus-ink">Pontualidade</p>
              <p class="text-sm text-nexus-ink/70">Média mensal</p>
            </div>
            <div class="text-2xl font-bold text-nexus-d">95%</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-white/80 border-t border-white/60 py-8 mt-12">
    <div class="container mx-auto px-4">
      <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-xl grid place-items-center" style="background:var(--nexus-a)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2l3.3 6.7L22 12l-6.7 3.3L12 22l-3.3-6.7L2 12l6.7-3.3L12 2z"/>
            </svg>
          </div>
          <span class="text-lg font-bold" style="color:var(--nexus-a)">NEXUS</span>
        </div>
        <div class="text-sm text-nexus-ink/70">
          © <?php echo date('Y'); ?> NEXUS • Sistema de Transporte Universitário
        </div>
      </div>
    </div>
  </footer>

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
</body>
</html>
