<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NEXUS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
        .glass{ 
            backdrop-filter: blur(10px); 
            background: rgba(255,255,255,.8); 
            border: 1px solid rgba(255,255,255,.3);
        }
        .frost{ backdrop-filter: blur(16px) saturate(130%); background: rgba(255,255,255,.5); border: 1px solid rgba(255,255,255,.4); }
        .shadow-soft { box-shadow: 0 10px 30px rgba(13, 18, 28, .12); }
    </style>
</head>
<body class="bg-nexus-base gradient-surface min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-2xl grid place-items-center shadow-soft" style="background:var(--nexus-a)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2l3.3 6.7L22 12l-6.7 3.3L12 22l-3.3-6.7L2 12l6.7-3.3L12 2z"/>
                    </svg>
                </div>
                <span class="text-3xl font-black tracking-tight" style="color:var(--nexus-a)">NEXUS</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Acessar Sistema</h2>
            <p class="mt-2 text-gray-600">Entre com suas credenciais</p>
        </div>
        
        <!-- Formulário -->
        <form class="glass rounded-2xl p-6 shadow-soft space-y-6" method="POST">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-envelope mr-2"></i>Email
                </label>
                <input id="email" name="email" type="email" required 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                       placeholder="seu.email@universidade.edu.br">
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-lock mr-2"></i>Senha
                </label>
                <input id="password" name="password" type="password" required 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                       placeholder="Sua senha">
            </div>
            
            <button type="submit" 
                    class="w-full py-3 px-4 rounded-xl text-white font-medium shadow-soft hover:shadow-glow transition-all duration-300"
                    style="background:var(--nexus-a)"
                    onmouseover="this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="bi bi-box-arrow-in-right mr-2"></i>
                Entrar
            </button>
        </form>
        
        <div class="text-center mt-6">
            <a href="register" class="font-medium text-nexus-b hover:text-nexus-a transition-colors">
                Criar nova conta
            </a>
        </div>

        <!-- Informações Adicionais -->
        <div class="mt-8 grid grid-cols-3 gap-4 text-center">
            <div class="p-3 rounded-xl bg-white/50 border border-white/60">
                <i class="bi bi-shield-check text-nexus-b text-lg"></i>
                <p class="text-xs mt-1 text-gray-600">Seguro</p>
            </div>
            <div class="p-3 rounded-xl bg-white/50 border border-white/60">
                <i class="bi bi-lightning-charge text-nexus-d text-lg"></i>
                <p class="text-xs mt-1 text-gray-600">Rápido</p>
            </div>
            <div class="p-3 rounded-xl bg-white/50 border border-white/60">
                <i class="bi bi-graph-up text-nexus-f text-lg"></i>
                <p class="text-xs mt-1 text-gray-600">Eficiente</p>
            </div>
        </div>
    </div>
</body>
</html>