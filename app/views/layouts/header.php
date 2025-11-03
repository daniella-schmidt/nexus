<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Sistema de Gestão de Transporte</title>
    
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
                    },
                    backdropBlur: {
                        xs: '2px'
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
        .gradient-hero {
            background-image: 
                radial-gradient(800px 800px at 20% 30%, rgba(99,168,202,.25), transparent 60%),
                radial-gradient(600px 600px at 80% 70%, rgba(175,145,189,.22), transparent 60%),
                linear-gradient(135deg, #fbfcff 0%, #f5f7fd 100%);
        }
        .glass{ backdrop-filter: blur(10px); background: rgba(255,255,255,.6); }
        .frost{ backdrop-filter: blur(16px) saturate(130%); background: rgba(255,255,255,.5); border: 1px solid rgba(255,255,255,.4); }
        .brand-chip{ background: linear-gradient(135deg, var(--nexus-b), var(--nexus-d)); }
        .brand-ring{ box-shadow: 0 0 0 8px rgba(133,176,216,.18); }
        .nexus-card {
            transition: all 0.3s ease;
        }
        .nexus-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(13, 18, 28, .15);
        }
        .nexus-gradient-text {
            background: linear-gradient(135deg, var(--nexus-a), var(--nexus-f));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Form styles consistent with about.html */
        [type='text'],
        [type='email'],
        [type='url'],
        [type='password'],
        [type='number'],
        [type='date'],
        [type='datetime-local'],
        [type='month'],
        [type='search'],
        [type='tel'],
        [type='time'],
        [type='week'],
        [multiple],
        textarea,
        select {
            @apply w-full rounded-xl border border-gray-300/60 bg-white/70 shadow-sm focus:border-nexus-b focus:ring-nexus-b focus:bg-white transition-all;
        }
        
        .btn-primary {
            @apply bg-nexus-b hover:bg-nexus-c text-white font-medium py-2 px-4 rounded-xl shadow-soft hover:shadow-glow transition-all;
        }
        
        .btn-secondary {
            @apply bg-white/70 hover:bg-white text-nexus-ink border border-gray-300/60 font-medium py-2 px-4 rounded-xl shadow-soft transition-all;
        }
    </style>
</head>
<body class="bg-nexus-base text-nexus-ink gradient-surface min-h-screen">
    <!-- Navbar -->
    <header class="sticky top-0 z-40 backdrop-blur-md bg-white/70 border-b border-white/60">
        <nav class="container mx-auto flex items-center justify-between py-3 px-4">
            <div class="flex items-center gap-3">
                <!-- Logomarca -->
                <div class="relative w-10 h-10 brand-ring rounded-2xl grid place-items-center">
                    <span class="w-6 h-6 inline-grid place-items-center rounded-xl" style="background:var(--nexus-f)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2l3.3 6.7L22 12l-6.7 3.3L12 22l-3.3-6.7L2 12l6.7-3.3L12 2z"/>
                        </svg>
                    </span>
                </div>
                <span class="text-xl font-extrabold tracking-tight" style="color:var(--nexus-a)">NEXUS</span>
            </div>
            
            <div class="hidden md:flex items-center gap-7 text-sm">
                <a href="/nexus/dashboard" class="hover:opacity-80 transition-all font-medium">Dashboard</a>
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                <a href="/nexus/manager/users" class="hover:opacity-80 transition-all font-medium">Administradores</a>
                <a href="/nexus/manager/vehicles" class="hover:opacity-80 transition-all font-medium">Veículos</a>
                <a href="/nexus/manager/routes" class="hover:opacity-80 transition-all font-medium">Rotas</a>
                <a href="/nexus/manager/reports" class="hover:opacity-80 transition-all font-medium">Relatórios</a>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center gap-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/nexus/logout" class="text-sm px-4 py-2 rounded-xl text-white font-medium shadow-soft hover:shadow-glow transition-all" style="background:var(--nexus-f)">
                        <i class="bi bi-box-arrow-right mr-1"></i>Sair
                    </a>
                <?php else: ?>
                    <a href="/nexus/login" class="text-sm px-4 py-2 rounded-xl text-white font-medium shadow-soft hover:shadow-glow transition-all" style="background:var(--nexus-b)">
                        <i class="bi bi-box-arrow-in-right mr-1"></i>Entrar
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>