<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="min-h-screen gradient-surface">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-nexus-ink mb-2">Importar Reservas</h1>
            <p class="text-nexus-ink/70">Importe reservas via arquivo CSV para uso offline</p>
        </div>

        <!-- Upload Form -->
        <div class="frost rounded-xl p-6 mb-8">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Upload de Arquivo CSV</h2>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="csv_file" class="block text-sm font-medium text-nexus-ink mb-2">
                        <i class="bi bi-file-earmark-spreadsheet mr-2"></i>Arquivo CSV *
                    </label>
                    <input
                        type="file"
                        id="csv_file"
                        name="csv_file"
                        accept=".csv"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300/60 bg-white/70 focus:outline-none focus:ring-2 focus:ring-nexus-b focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-nexus-b file:text-white hover:file:bg-nexus-a"
                    >
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="bi bi-info-circle text-blue-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="text-blue-800 font-medium">Formato do arquivo CSV:</h4>
                            <p class="text-blue-700 text-sm mt-1">O arquivo deve conter as seguintes colunas:</p>
                            <ul class="text-blue-700 text-sm mt-2 space-y-1 font-mono">
                                <li>• data (YYYY-MM-DD)</li>
                                <li>• estudante_nome</li>
                                <li>• matricula</li>
                                <li>• rota_nome</li>
                                <li>• horario</li>
                                <li>• status (confirmada/pendente)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-nexus-b text-white py-3 rounded-lg hover:bg-nexus-a transition-colors">
                    <i class="bi bi-upload mr-2"></i>Importar Reservas
                </button>
            </form>
        </div>

        <!-- Sample CSV -->
        <div class="frost rounded-xl p-6 mb-8">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Exemplo de Arquivo CSV</h2>

            <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto">
                <pre class="text-sm text-gray-800 font-mono">
data,estudante_nome,matricula,rota_nome,horario,status
2024-01-15,João Silva,2024001,Centro-Universidade,07:30,confirmada
2024-01-15,Maria Santos,2024002,Centro-Universidade,07:30,confirmada
2024-01-15,Carlos Oliveira,2024003,Universidade-Centro,17:45,confirmada
2024-01-15,Ana Costa,2024004,Universidade-Centro,17:45,confirmada</pre>
            </div>

            <div class="mt-4">
                <a href="/driver/download-sample-csv" class="bg-nexus-c text-white px-4 py-2 rounded-lg hover:bg-nexus-b transition-colors">
                    <i class="bi bi-download mr-2"></i>Baixar Exemplo CSV
                </a>
            </div>
        </div>

        <!-- Import History -->
        <div class="frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Histórico de Importações</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Data/Hora</th>
                            <th class="text-left py-3 px-4 text-nexus-ink font-medium">Arquivo</th>
                            <th class="text-center py-3 px-4 text-nexus-ink font-medium">Reservas Importadas</th>
                            <th class="text-center py-3 px-4 text-nexus-ink font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Import history will be populated here -->
                        <tr>
                            <td colspan="4" class="text-center py-8 text-nexus-ink/70">
                                Nenhuma importação realizada ainda
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Offline Usage Instructions -->
        <div class="mt-8 frost rounded-xl p-6">
            <h2 class="text-xl font-semibold text-nexus-ink mb-6">Uso Offline</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-nexus-ink mb-3">Vantagens do CSV Offline:</h3>
                    <ul class="space-y-2 text-sm text-nexus-ink/70">
                        <li class="flex items-center">
                            <i class="bi bi-check-circle text-green-500 mr-2"></i>
                            Funciona sem conexão com internet
                        </li>
                        <li class="flex items-center">
                            <i class="bi bi-check-circle text-green-500 mr-2"></i>
                            Check-in mais rápido
                        </li>
                        <li class="flex items-center">
                            <i class="bi bi-check-circle text-green-500 mr-2"></i>
                            Backup de dados de reserva
                        </li>
                        <li class="flex items-center">
                            <i class="bi bi-check-circle text-green-500 mr-2"></i>
                            Sincronização automática quando online
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-medium text-nexus-ink mb-3">Como usar:</h3>
                    <ol class="space-y-2 text-sm text-nexus-ink/70">
                        <li class="flex items-start">
                            <span class="bg-nexus-b text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                            Importe o CSV quando estiver online
                        </li>
                        <li class="flex items-start">
                            <span class="bg-nexus-b text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                            Os dados ficam salvos no dispositivo
                        </li>
                        <li class="flex items-start">
                            <span class="bg-nexus-b text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                            Use normalmente mesmo offline
                        </li>
                        <li class="flex items-start">
                            <span class="bg-nexus-b text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">4</span>
                            Dados sincronizam automaticamente quando voltar online
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
