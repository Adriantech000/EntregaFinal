<?php

include_once 'Controller/PacienteController.php';
include_once 'Model/Paciente.php'; 

$controladorPaciente = new PacienteController();
$listaPacientes = [];
$termoBusca = '';

// Verifica se o formulário de busca foi submetido (se existe o parâmetro 'busca' na URL)
if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    // Armazena o termo de busca enviado pelo formulário
    $termoBusca = $_GET['busca'];
    // Chama o método do controlador para buscar pacientes com o termo fornecido
    $listaPacientes = $controladorPaciente->buscarPaciente($termoBusca);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Pacientes</title>
    <!-- Link para o CSS -->
    <link rel="stylesheet" href="View/style.css">
</head>
<body>
    <div class="container">
        <h1>Sistema de Pacientes</h1>
        
        <!--formulário de busca -->
        <div class="busca">
            <h2>Buscar Paciente</h2>
            <form method="GET" action="index.php">
                <input type="text" name="busca" placeholder="Digite o nome ou ID do paciente..." value="<?= htmlspecialchars($termoBusca) ?>" required>
                <button type="submit">Buscar</button>
            </form>
            <?php if (!empty($termoBusca)): ?>
                <a href="index.php">Limpar busca</a>
            <?php endif; ?>
        </div>

        <?php if (!empty($listaPacientes)): ?>
            <div class="resultados">
                <h3>Resultados da Busca</h3>
                <?php foreach ($listaPacientes as $paciente): /* @var $paciente Paciente */ ?>
                    <div class="paciente">
                        <h4><?= htmlspecialchars($paciente->nome) ?> (ID: <?= htmlspecialchars($paciente->registro) ?>)</h4>
                        <table>
                            <tr>
                                <td><strong>Nome Social:</strong></td>
                                <td><?= htmlspecialchars($paciente->nomeSocial) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Sexo:</strong></td>
                                <td><?= htmlspecialchars($paciente->Sexo) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Telefone:</strong></td>
                                <!-- O operador '??' (null coalescing) exibe 'Não informado' se o campo for nulo -->
                                <td><?= htmlspecialchars($paciente->telefone ?? 'Não informado') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?= htmlspecialchars($paciente->Email ?? 'Não informado') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Data de Nascimento:</strong></td>
                                <!-- Formata a data para o padrão brasileiro (d/m/Y) se não for nula -->
                                <td><?= $paciente->Data_Nascimento ? date('d/m/Y', strtotime($paciente->Data_Nascimento)) : 'Não informado' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nome da Mãe:</strong></td>
                                <td><?= htmlspecialchars($paciente->nomeMae ?? 'Não informado') ?></td>
                            </tr>
                            <!-- Exibe a patologia apenas se o campo não estiver vazio -->
                            <?php if (!empty($paciente->patologia)): ?>
                            <tr>
                                <td><strong>Patologia:</strong></td>
                                <td><?= htmlspecialchars($paciente->patologia) ?></td>
                            </tr>
                            <?php endif; ?>
                            <!-- Exibe o medicamento apenas se o campo não estiver vazio -->
                            <?php if (!empty($paciente->medicamento)): ?>
                            <tr>
                                <td><strong>Medicamento:</strong></td>
                                <td><?= htmlspecialchars($paciente->medicamento) ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>

                        <a href="View/editar_exames.php?id_paciente=<?= $paciente->registro ?>" class="botao">Editar Exames</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (!empty($termoBusca)): ?>
            <div class="sem-resultado">
                <p>Nenhum paciente encontrado para a busca: <strong><?= htmlspecialchars($termoBusca) ?></strong></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

