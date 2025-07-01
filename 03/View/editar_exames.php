<?php
// Inclui os controladores de Paciente e Exame para acessar suas funcionalidades
include_once '../Controller/PacienteController.php';
include_once '../Controller/ExameController.php';
include_once '../Model/Paciente.php'; // Inclui a classe Model Paciente
include_once '../Model/Exame.php'; // Inclui a classe Model Exame

// Cria instâncias dos controladores
$controladorPaciente = new PacienteController();
$controladorExame = new ExameController();

// Obtém o ID do paciente da URL (parâmetro GET). Se não houver, define como null.
$id_paciente = isset($_GET['id_paciente']) ? $_GET['id_paciente'] : null;
// Inicializa a variável de mensagem (para feedback ao usuário)
$mensagem = '';

// Se o ID do paciente não foi fornecido, redireciona de volta para a página principal
if (!$id_paciente) {
    header('Location: ../index.php');
    exit;
}

// Obtém os dados do paciente usando o ID fornecido
$paciente = $controladorPaciente->obterPacientePorId($id_paciente);
// Se o paciente não for encontrado, redireciona de volta para a página principal
if (!$paciente) {
    header('Location: ../index.php');
    exit;
}

// --- Lógica para processar a adição de um novo exame --- //
// Verifica se a requisição é POST e se o botão 'adicionar_exame' foi clicado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_exame'])) {
    // Obtém o ID do exame selecionado no formulário
    $id_exame = $_POST['id_exame'];
    // Define a data de registro como a data atual
    $data_registro = date('Y-m-d');
    
    // Tenta adicionar o exame solicitado usando o controlador
    if ($controladorExame->adicionarExameSolicitado($id_paciente, $id_exame, $data_registro)) {
        $mensagem = 'Exame adicionado com sucesso!'; // Mensagem de sucesso
    } else {
        $mensagem = 'Erro ao adicionar exame.'; // Mensagem de erro
    }
}

// --- Lógica para processar a remoção de um exame --- //
// Verifica se a requisição é POST e se o botão 'remover_exame' foi clicado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover_exame'])) {
    // Obtém o ID do exame solicitado a ser removido
    $id_exame_solicitado = $_POST['id_exame_solicitado'];
    
    // Tenta deletar o exame solicitado usando o controlador
    if ($controladorExame->deletarExameSolicitado($id_exame_solicitado)) {
        $mensagem = 'Exame removido com sucesso!'; // Mensagem de sucesso
    } else {
        $mensagem = 'Erro ao remover exame.'; // Mensagem de erro
    }
}

// Obtém a lista de exames já solicitados para o paciente atual
$examesDoPaciente = $controladorExame->obterExamesPorPacienteId($id_paciente);
// Obtém a lista completa de todos os exames disponíveis no catálogo
$todosExames = $controladorExame->obterTodosExames();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Exames - <?= htmlspecialchars($paciente->nome) ?></title>
    <!-- Link para a folha de estilos CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Exames do Paciente</h1>
        <!-- Link para voltar à página principal de busca -->
        <a href="../index.php">Voltar</a>

        <!-- Seção de informações resumidas do paciente -->
        <div class="info-paciente">
            <h2><?= htmlspecialchars($paciente->nome) ?></h2>
            <p><strong>ID:</strong> <?= htmlspecialchars($paciente->registro) ?></p>
            <p><strong>Nome Social:</strong> <?= htmlspecialchars($paciente->nomeSocial) ?></p>
        </div>

        <!-- Exibe mensagens de sucesso ou erro, se houver -->
        <?php if ($mensagem): ?>
            <div class="mensagem <?= strpos($mensagem, 'sucesso') !== false ? 'sucesso' : 'erro' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <!-- Seção principal de gerenciamento de exames (adicionar e listar) -->
        <div class="secao-exames">
            <!-- Seção para adicionar um novo exame -->
            <div class="adicionar-exame">
                <h3>Adicionar Novo Exame</h3>
                <form method="POST">
                    <label for="id_exame">Selecione o Exame:</label>
                    <!-- Dropdown (select) com todos os exames disponíveis no catálogo -->
                    <select name="id_exame" id="id_exame" required>
                        <option value="">Escolha um exame...</option>
                        <?php foreach ($todosExames as $exame): /* @var $exame Exame */ ?>
                            <option value="<?= $exame->id_exame ?>"><?= htmlspecialchars($exame->nome_exame) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="adicionar_exame" class="botao">Adicionar Exame</button>
                </form>
            </div>

            <!-- Seção para exibir os exames já solicitados pelo paciente -->
            <div class="exames-atuais">
                <h3>Exames Solicitados</h3>
                <?php if (!empty($examesDoPaciente)): ?>
                    <!-- Loop para exibir cada exame solicitado -->
                    <?php foreach ($examesDoPaciente as $exameSolicitado): /* @var $exameSolicitado stdClass */ ?>
                        <div class="exame">
                            <div>
                                <h4><?= htmlspecialchars($exameSolicitado->nome_exame) ?></h4>
                                <p>Data de Registro: <?= date('d/m/Y', strtotime($exameSolicitado->data_registro)) ?></p>
                            </div>
                            <!-- Formulário para remover um exame específico -->
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id_exame_solicitado" value="<?= $exameSolicitado->id_exame_solicitado ?>">
                                <!-- Botão de remover com confirmação via JavaScript -->
                                <button type="submit" name="remover_exame" class="botao-remover" onclick="return confirm('Tem certeza que deseja remover este exame?')">
                                    Remover
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum exame solicitado para este paciente.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

