<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Check-in de Passageiros</h1>
            <p class="text-nexus-ink/70">Use o scanner QR Code para validar reservas</p>
        </div>

        <!-- QR Code Scanner -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="frost rounded-xl p-6">
                <h2 class="text-xl font-semibold text-nexus-ink mb-6">Scanner QR Code</h2>

                <div class="text-center mb-6">
                    <div id="qr-reader" class="mx-auto mb-4" style="width: 300px; height: 300px;"></div>
                    <button id="start-scan" class="bg-nexus-b text-white px-6 py-3 rounded-lg hover:bg-nexus-a transition-colors">
                        <i class="bi bi-camera mr-2"></i>Iniciar Scanner
                    </button>
                </div>

                <form id="qr-form" method="POST" class="space-y-4">
                    <div>
                        <label for="qr_data" class="block text-sm font-medium text-nexus-ink mb-2">
                            <i class="bi bi-qr-code mr-2"></i>Código QR Manual
                        </label>
                        <input
                            type="text"
                            id="qr_data"
                            name="qr_data"
                            placeholder="Digite o código QR ou use o scanner"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all"
                        >
                    </div>

                    <button type="submit" class="w-full bg-nexus-b text-white py-3 rounded-lg hover:bg-nexus-a transition-colors">
                        <i class="bi bi-check-circle mr-2"></i>Validar Check-in
                    </button>
                </form>
            </div>

            <!-- Today's Reservations -->
            <div class="frost rounded-xl p-6">
                <h2 class="text-xl font-semibold text-nexus-ink mb-6">Reservas de Hoje</h2>

                <?php if (!empty($reservations)): ?>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php foreach ($reservations as $reservation): ?>
                            <div class="flex items-center justify-between p-3 bg-white/50 rounded-lg">
                                <div>
                                    <p class="font-medium text-nexus-ink"><?php echo htmlspecialchars($reservation['student_name']); ?></p>
                                    <p class="text-sm text-nexus-ink/70"><?php echo htmlspecialchars($reservation['matricula']); ?> • <?php echo htmlspecialchars($reservation['route_name']); ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Confirmada</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="bi bi-calendar-x text-4xl text-nexus-ink/30 mb-4"></i>
                        <p class="text-nexus-ink/70">Nenhuma reserva para hoje</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Check-in History -->
        <div class="mt-8 frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Histórico de Check-ins (Hoje)</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Horário</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Passageiro</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Matrícula</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Rota</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody id="checkin-history">
                        <!-- Check-ins will be added here dynamically -->
                        <tr>
                            <td colspan="5" class="text-center py-8 text-nexus-ink/70">
                                Nenhum check-in realizado ainda hoje
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Scanner Script -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let html5QrcodeScanner = null;

    const startScanBtn = document.getElementById('start-scan');
    const qrReader = document.getElementById('qr-reader');
    const qrDataInput = document.getElementById('qr_data');

    startScanBtn.addEventListener('click', function() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().then(() => {
                html5QrcodeScanner = null;
                startScanBtn.textContent = 'Iniciar Scanner';
                qrReader.innerHTML = '';
            });
            return;
        }

        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            { fps: 10, qrbox: 250 }
        );

        html5QrcodeScanner.render(function(decodedText) {
            qrDataInput.value = decodedText;
            html5QrcodeScanner.clear();
            html5QrcodeScanner = null;
            startScanBtn.textContent = 'Iniciar Scanner';
        }, function(error) {
            console.log(error);
        });

        startScanBtn.textContent = 'Parar Scanner';
    });

    // Auto-submit form when QR code is scanned
    qrDataInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            // Optional: auto-submit after a short delay
            // document.getElementById('qr-form').submit();
        }
    });
});
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
