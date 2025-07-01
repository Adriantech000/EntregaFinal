<?php

include_once __DIR__ . "/../Dao/Database.php";
include_once __DIR__ . "/../Model/Paciente.php";


class PacienteDAO {
    private $conexao;
    private $nome_tabela = "paciente"; 

    
    public function __construct($db){
        $this->conexao = $db;
    }
 
    public function buscar($termoBusca){
        // Query SQL para buscar pacientes onde o nome é similar ao termo ou o registro é igual ao termo
        $query = "SELECT * FROM " . $this->nome_tabela . " WHERE nome LIKE ? OR registro = ?";

        // Prepara a query para execução, prevenindo SQL Injection
        $stmt = $this->conexao->prepare($query);
        // Limpa o termo de busca de possíveis tags HTML e caracteres especiais
        $termoBusca = htmlspecialchars(strip_tags($termoBusca));
        // Associa o termo de busca ao primeiro placeholder (?) como string (para LIKE)
        $stmt->bindValue(1, "%{$termoBusca}%", PDO::PARAM_STR);
        // Associa o termo de busca ao segundo placeholder (?) como inteiro (para registro)
        $stmt->bindValue(2, $termoBusca, PDO::PARAM_INT);
        // Executa a query preparada
        $stmt->execute();

        $pacientes = [];
        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pacientes[] = new Paciente($linha); // Cria um objeto Paciente para cada linha
        }
        return $pacientes; // Retorna o array de objetos Paciente
    }

    public function obterPorId($registro){
        // Query SQL para selecionar um paciente específico pelo registro
        $query = "SELECT * FROM " . $this->nome_tabela . " WHERE registro = ? LIMIT 0,1";

        // Prepara a query
        $stmt = $this->conexao->prepare($query);
        // Associa o ID do registro ao placeholder (?)
        $stmt->bindParam(1, $registro);
        // Executa a query
        $stmt->execute();

        // Obtém a primeira linha do resultado como um array associativo
        $linha = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($linha) {
            return new Paciente($linha); // Retorna um objeto Paciente
        }
        return null; // Retorna null se não encontrado
    }
}

?>

