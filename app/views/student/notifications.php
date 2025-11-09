<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /nexus/login');
    exit();
}
?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Notificações</h1>
            <p class="text-nexus-ink/70">Mensagens e atualizações importantes</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Notifications List -->
            <div class="lg:col-span-2">
                <div class="frost rounded-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-nexus-ink">Mensagens Recentes</h2>
                        <div class="flex items-center gap-4">
                            <span id="unread-count" class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium hidden"></span>
                            <button onclick="markAllAsRead()" class="bg-nexus-b text-white px-4 py-2 rounded-lg hover:bg-nexus-a transition-colors text-sm">
                                <i class="bi bi-check-all mr-2"></i>Marcar todas como lidas
                            </button>
                        </div>
                    </div>

                    <div id="notifications-container">
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                                <div class="border border-gray-200/60 rounded-lg p-4 mb-4 hover:shadow-glow transition-all <?php echo !$notification['read_status'] ? 'new-notification' : ''; ?>"
                                     data-notification-id="<?php echo $notification['id']; ?>">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h3 class="font-semibold text-nexus-ink"><?php echo htmlspecialchars($notification['title']); ?></h3>
                                                <?php if (!$notification['read_status']): ?>
                                                    <span class="bg-nexus-b text-white px-2 py-0.5 rounded-full text-xs">Nova</span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="text-nexus-ink/80 mb-3"><?php echo htmlspecialchars($notification['message']); ?></p>
                                            <div class="flex items-center gap-4 text-sm text-nexus-ink/60">
                                                <span><i class="bi bi-clock mr-1"></i><?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?></span>
                                                <span class="capitalize"><?php echo $notification['priority']; ?></span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 ml-4">
                                            <?php if (!$notification['read_status']): ?>
                                                <button onclick="markAsRead(<?php echo $notification['id']; ?>)"
                                                        class="text-nexus-b hover:text-nexus-a p-2 rounded-full hover:bg-nexus-b/10 transition-colors"
                                                        title="Marcar como lida">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button onclick="deleteNotification(<?php echo $notification['id']; ?>)"
                                                    class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors"
                                                    title="Excluir notificação">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-12">
                                <i class="bi bi-bell text-4xl text-nexus-ink/30 mb-4"></i>
                                <p class="text-nexus-ink/70 text-lg">Nenhuma notificação</p>
                                <p class="text-nexus-ink/50 text-sm">Novas mensagens dos motoristas aparecerão aqui</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="frost rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-nexus-ink mb-4">Estatísticas</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-nexus-ink/70">Total de notificações</span>
                            <span class="font-medium text-nexus-ink"><?php echo count($notifications); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-nexus-ink/70">Não lidas</span>
                            <span class="font-medium text-nexus-b" id="unread-count-sidebar"><?php echo $unreadCount; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="frost rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-nexus-ink mb-4">Ações Rápidas</h3>
                    <div class="space-y-3">
                        <button onclick="markAllAsRead()" class="w-full bg-nexus-b text-white py-2 px-4 rounded-lg hover:bg-nexus-a transition-colors text-sm">
                            <i class="bi bi-check-all mr-2"></i>Marcar todas como lidas
                        </button>
                        <button onclick="window.location.reload()" class="w-full bg-gray-100 text-nexus-ink py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                            <i class="bi bi-arrow-clockwise mr-2"></i>Atualizar página
                        </button>
                    </div>
                </div>

                <!-- Help -->
                <div class="frost rounded-xl p-6 bg-blue-50 border border-blue-200">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="bi bi-info-circle text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-blue-800 mb-1">Dicas</h4>
                            <p class="text-blue-700 text-sm">
                                Clique no ícone de check para marcar notificações como lidas.
                                As notificações não lidas aparecem destacadas em azul.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.new-notification {
    border-left: 4px solid #63a8ca;
    background-color: #f0f9ff;
}
</style>

<!-- Audio para notificação -->
<audio id="notification-sound" preload="auto">
    <source src="/nexus/assets/notification.mp3" type="audio/mpeg">
</audio>

<script>
let eventSource;
const notificationSound = document.getElementById('notification-sound');

// Iniciar conexão SSE
function startNotificationStream() {
    eventSource = new EventSource('/nexus/notifications/stream');

    eventSource.onmessage = function(event) {
        const data = JSON.parse(event.data);
        updateUnreadCount(data.unread_count);
    };

    eventSource.addEventListener('new_notification', function(event) {
        const notification = JSON.parse(event.data);
        showNewNotification(notification);
        playNotificationSound();
        updateUnreadCount(notification.unread_count);
    });

    eventSource.onerror = function(event) {
        console.error('Erro na conexão SSE:', event);
        // Tentar reconectar após 5 segundos
        setTimeout(startNotificationStream, 5000);
    };
}

function showNewNotification(notification) {
    const container = document.getElementById('notifications-container');
    const notificationHtml = `
        <div class="border border-gray-200/60 rounded-lg p-4 mb-4 hover:shadow-glow transition-all new-notification"
             data-notification-id="${notification.id}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-semibold text-nexus-ink">${notification.title}</h3>
                        <span class="bg-nexus-b text-white px-2 py-0.5 rounded-full text-xs">Nova</span>
                    </div>
                    <p class="text-nexus-ink/80 mb-3">${notification.message}</p>
                    <div class="flex items-center gap-4 text-sm text-nexus-ink/60">
                        <span><i class="bi bi-clock mr-1"></i>${new Date().toLocaleString('pt-BR')}</span>
                        <span class="capitalize">${notification.priority}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 ml-4">
                    <button onclick="markAsRead(${notification.id})"
                            class="text-nexus-b hover:text-nexus-a p-2 rounded-full hover:bg-nexus-b/10 transition-colors"
                            title="Marcar como lida">
                        <i class="bi bi-check-lg"></i>
                    </button>
                    <button onclick="deleteNotification(${notification.id})"
                            class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors"
                            title="Excluir notificação">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    // Adicionar no topo
    if (container.children.length > 0 && container.firstChild.classList.contains('text-center')) {
        container.innerHTML = notificationHtml;
    } else {
        container.insertAdjacentHTML('afterbegin', notificationHtml);
    }

    // Mostrar badge de notificação
    showNotificationBadge(notification);
}

function showNotificationBadge(notification) {
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(notification.title, {
            body: notification.message,
            icon: '/nexus/assets/logo.png'
        });
    }
}

function playNotificationSound() {
    try {
        notificationSound.play();
    } catch (error) {
        console.log('Não foi possível reproduzir o som:', error);
    }
}

function updateUnreadCount(count) {
    const badges = document.querySelectorAll('#unread-count, #unread-count-sidebar');
    badges.forEach(badge => {
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    });
}

async function markAsRead(notificationId) {
    try {
        const response = await fetch('/nexus/student/notifications/mark-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `notification_id=${notificationId}`
        });

        if (response.ok) {
            // Atualizar UI
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('new-notification');
                notificationElement.querySelector('.bg-nexus-b').remove();
                notificationElement.querySelector('button[onclick*="markAsRead"]').remove();
            }

            // Atualizar contador
            const currentCount = parseInt(document.getElementById('unread-count').textContent || '0');
            updateUnreadCount(Math.max(0, currentCount - 1));
        } else {
            console.error('Erro na resposta:', response.status);
        }
    } catch (error) {
        console.error('Erro ao marcar como lida:', error);
    }
}

async function markAllAsRead() {
    try {
        const response = await fetch('/nexus/student/notifications/mark-all-read', {
            method: 'POST'
        });

        if (response.ok) {
            // Atualizar UI
            document.querySelectorAll('.new-notification').forEach(element => {
                element.classList.remove('new-notification');
                const badge = element.querySelector('.bg-nexus-b');
                if (badge) badge.remove();
                const button = element.querySelector('button[onclick*="markAsRead"]');
                if (button) button.remove();
            });

            updateUnreadCount(0);
        } else {
            console.error('Erro na resposta:', response.status);
        }
    } catch (error) {
        console.error('Erro ao marcar todas como lidas:', error);
    }
}

async function deleteNotification(notificationId) {
    if (!confirm('Tem certeza que deseja excluir esta notificação?')) {
        return;
    }

    try {
        const response = await fetch('/nexus/student/notifications/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `notification_id=${notificationId}`
        });

        if (response.ok) {
            document.querySelector(`[data-notification-id="${notificationId}"]`).remove();

            // Verificar se container ficou vazio
            const container = document.getElementById('notifications-container');
            if (container.children.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <i class="bi bi-bell text-4xl text-nexus-ink/30 mb-4"></i>
                        <p class="text-nexus-ink/70 text-lg">Nenhuma notificação</p>
                        <p class="text-nexus-ink/50 text-sm">Novas mensagens dos motoristas aparecerão aqui</p>
                    </div>
                `;
            }
        } else {
            console.error('Erro na resposta:', response.status);
        }
    } catch (error) {
        console.error('Erro ao excluir notificação:', error);
    }
}

// Pedir permissão para notificações do navegador
if ('Notification' in window) {
    Notification.requestPermission();
}

// Iniciar quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    startNotificationStream();

    // Carregar contador inicial
    fetch('/nexus/notifications/unread-count')
        .then(response => response.json())
        .then(data => updateUnreadCount(data.unread_count))
        .catch(error => console.error('Erro ao carregar contador:', error));
});

// Fechar conexão quando a página for fechada
window.addEventListener('beforeunload', function() {
    if (eventSource) {
        eventSource.close();
    }
});
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
