<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Carteirinha Digital - NEXUS</title>
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
    .card-gradient {
      background: linear-gradient(135deg, var(--nexus-a) 0%, var(--nexus-b) 100%);
    }
    .qr-placeholder {
      background: repeating-linear-gradient(45deg, #f0f0f0, #f0f0f0 10px, #e0e0e0 10px, #e0e0e0 20px);
    }
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
      <h1 class="text-3xl font-black mb-2 text-nexus-ink text-center p-4">Carteirinha Digital</h1>

      <!-- Carteirinha Digital -->
      <div class="frost rounded-3xl p-8 shadow-soft mb-8">
        <div class="card-gradient rounded-2xl p-6 text-white relative overflow-hidden">
          <!-- Padrão de fundo -->
          <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%" viewBox="0 0 400 200" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern id="pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                  <circle cx="20" cy="20" r="2" fill="white"/>
                </pattern>
              </defs>
              <rect width="100%" height="100%" fill="url(#pattern)"/>
            </svg>
          </div>

          <div class="relative z-10">
            <!-- Header da Carteirinha -->
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2l3.3 6.7L22 12l-6.7 3.3L12 22l-3.3-6.7L2 12l6.7-3.3L12 2z"/>
                  </svg>
                </div>
                <div>
                  <h2 class="text-xl font-bold">NEXUS</h2>
                  <p class="text-sm opacity-90">Sistema de Transporte</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm opacity-90">Estudantil</p>
                <p class="text-xs opacity-75">Válida até 31/12/<?php echo date('Y'); ?></p>
              </div>
            </div>

            <!-- Foto do Perfil -->
            <div class="flex items-center gap-6 mb-6">
              <div class="w-20 h-20 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center overflow-hidden">
                  <?php if (!empty($profile_photo) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/nexus/public' . $profile_photo)): ?>
                      <img src="<?php echo '/nexus/public' . htmlspecialchars($profile_photo); ?>"
                          alt="Foto de perfil"
                          class="w-full h-full object-cover">
                  <?php else: ?>
                      <i class="bi bi-person text-3xl text-white"></i>
                  <?php endif; ?>
              </div>
              <div class="flex-1">
                  <h3 class="text-xl font-bold mb-1"><?php echo htmlspecialchars($name); ?></h3>
                  <p class="text-sm opacity-90 mb-1"><?php echo htmlspecialchars($matricula); ?></p>
                  <p class="text-sm opacity-90"><?php echo htmlspecialchars($curso); ?></p>
              </div>
          </div>

            <!-- QR Code -->
              <div class="flex items-center justify-between">
                  <div class="flex-1">
                      <p class="text-sm opacity-90 mb-2">Código QR para validação</p>
                      <div class="w-24 h-24 bg-white rounded-lg flex items-center justify-center p-2">
                          <?php
                            // Gerar QR Code com dados do usuário
                            $qrData = json_encode([
                                'user_id' => $_SESSION['user_id'],
                                'matricula' => $matricula,
                                'name' => $name,
                                'expires' => date('Y-m-d H:i:s', strtotime('+1 hour'))
                            ]);
                            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
                            ?>
                          <img src="<?php echo $qrUrl; ?>" 
                              alt="QR Code" 
                              class="w-full h-full"
                              onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                          <div class="w-full h-full qr-placeholder rounded-lg flex items-center justify-center hidden">
                              <i class="bi bi-qr-code text-2xl text-gray-600"></i>
                          </div>
                      </div>
                  </div>
                  <div class="text-right">
                      <p class="text-xs opacity-75">ID: <?php echo htmlspecialchars($matricula); ?></p>
                      <p class="text-xs opacity-75">Emitido em: <?php echo date('d/m/Y'); ?></p>
                  </div>
              </div>
          </div>
        </div>
      </div>

      <!-- Informações Adicionais -->
      <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="frost rounded-2xl p-6 shadow-soft">
          <div class="flex items-center gap-4 mb-4">
            <div class="w-10 h-10 rounded-xl grid place-items-center" style="background:var(--nexus-b)">
              <i class="bi bi-info-circle text-white"></i>
            </div>
            <h3 class="font-semibold text-nexus-ink">Como usar</h3>
          </div>
          <ul class="text-sm text-nexus-ink/70 space-y-2">
            <li>• Apresente o QR Code no embarque</li>
            <li>• Valide sua presença no transporte</li>
            <li>• Mantenha atualizado seus dados</li>
          </ul>
        </div>

        <div class="frost rounded-2xl p-6 shadow-soft">
          <div class="flex items-center gap-4 mb-4">
            <div class="w-10 h-10 rounded-xl grid place-items-center" style="background:var(--nexus-d)">
              <i class="bi bi-shield-check text-white"></i>
            </div>
            <h3 class="font-semibold text-nexus-ink">Segurança</h3>
          </div>
          <ul class="text-sm text-nexus-ink/70 space-y-2">
            <li>• Código único e pessoal</li>
            <li>• Válido apenas para você</li>
            <li>• Renovação automática</li>
          </ul>
        </div>
      </div>

      <!-- Botões de Ação
      <div class="flex flex-col sm:flex-row gap-4">
        <button onclick="window.print()" class="flex-1 py-3 px-6 rounded-xl text-white font-medium shadow-soft hover:shadow-glow transition-all text-center" style="background:var(--nexus-a)">
          <i class="bi bi-printer mr-2"></i>Imprimir Carteirinha
        </button>
        <button onclick="downloadCard()" class="flex-1 py-3 px-6 rounded-xl border-2 border-nexus-b text-nexus-b font-medium hover:bg-nexus-b hover:text-white transition-all text-center">
          <i class="bi bi-download mr-2"></i>Baixar como PDF
        </button>
      </div>
       -->
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
    function downloadCard() {
    // Usar html2canvas e jsPDF para gerar PDF
    const cardElement = document.querySelector('.card-gradient');

    html2canvas(cardElement).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF();
        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save('carteirinha-nexus-' + new Date().toISOString().split('T')[0] + '.pdf');
    });
}
  </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</body>
</html>
