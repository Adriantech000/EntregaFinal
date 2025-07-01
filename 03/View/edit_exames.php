<?php
include_once '../Controller/PacienteController.php';
include_once '../Controller/ExameController.php';

$pacienteController = new PacienteController();
$exameController = new ExameController();

$id_paciente = isset($_GET['id_paciente']) ? $_GET['id_paciente'] : null;
$message = '';

if (!$id_paciente) {
    header('Location: ../index.php');
    exit;
}

$paciente = $pacienteController->obterPacientePorId($id_paciente);
if (!$paciente) {
    header('Location: ../index.php');
    exit;
}

// Processar adição de exame
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_exame'])) {
    $id_exame = $_POST['id_exame'];
    $data_registro = date('Y-m-d');
    
    if ($exameController->adicionarExameSolicitado($id_paciente, $id_exame, $data_registro)) {
        $message = 'Exame adicionado com sucesso!';
    } else {
        $message = 'Erro ao adicionar exame.';
    }
}

// Processar remoção de exame
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_exame'])) {
    $id_exame_solicitado = $_POST['id_exame_solicitado'];
    
    if ($exameController->deletarExameSolicitado($id_exame_solicitado)) {
        $message = 'Exame removido com sucesso!';
    } else {
        $message = 'Erro ao remover exame.';
    }
}

$examesPaciente = $exameController->obterExamesPorPacienteId($id_paciente);
$todosExames = $exameController->obterTodosExames();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Exames - <?= htmlspecialchars($paciente['nome']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Editar Exames do Paciente</h1>
            <a href="../index.php" class="btn btn-secondary">Voltar à Pesquisa</a>
        </header>

        <main>
            <div class="patient-summary">
                <h2><?= htmlspecialchars($paciente['nome']) ?></h2>
                <p><strong>ID:</strong> <?= htmlspecialchars($paciente['registro']) ?></p>
                <p><strong>Nome Social:</strong> <?= htmlspecialchars($paciente['nomeSocial']) ?></p>
            </div>

            <?php if ($message): ?>
                <div class="message <?= strpos($message, 'sucesso') !== false ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="exames-section">
                <div class="add-exame-section">
                    <h3>Adicionar Novo Exame</h3>
                    <form method="POST" class="add-exame-form">
                        <div class="form-group">
                            <label for="id_exame">Selecione o Exame:</label>
                            <select name="id_exame" id="id_exame" required>
                                <option value="">Escolha um exame...</option>
                                <?php foreach ($todosExames as $exame): ?>
                                    <option value="<?= $exame['id_exame'] ?>"><?= htmlspecialchars($exame['nome_exame']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" name="add_exame" class="btn btn-primary">Adicionar Exame</button>
                    </form>
                </div>

                <div class="current-exames-section">
                    <h3>Exames Solicitados</h3>
                    <?php if (!empty($examesPaciente)): ?>
                        <div class="exames-list">
                            <?php foreach ($examesPaciente as $exame): ?>
                                <div class="exame-item">
                                    <div class="exame-info">
                                        <h4><?= htmlspecialchars($exame['nome_exame']) ?></h4>
                                        <p>Data de Registro: <?= date('d/m/Y', strtotime($exame['data_registro'])) ?></p>
                                    </div>
                                    <form method="POST" class="remove-form">
                                        <input type="hidden" name="id_exame_solicitado" value="<?= $exame['id_exame_solicitado'] ?>">
                                        <button type="submit" name="remove_exame" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja remover este exame?')">
                                            Remover
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-exames">Nenhum exame solicitado para este paciente.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

