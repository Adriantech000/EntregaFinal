<?php

include_once __DIR__ . "/../Dao/Database.php"; 
include_once __DIR__ . "/../Model/Exame.php"; 

class ExameDAO {
    private $conexao; 
    private $nome_tabela = "exames_solicitados"; 

    public function __construct($db){
        $this->conexao = $db;
    }
    
    public function obterExamesPorPacienteId($id_paciente){
        $query = "SELECT es.id_exame_solicitado, e.nome_exame, es.data_registro FROM " . $this->nome_tabela . " es JOIN exames e ON es.id_exame = e.id_exame WHERE es.id_paciente = ?";

        // Prepara a query para execução
        $stmt = $this->conexao->prepare($query);
        // Associa o ID do paciente ao placeholder (?)
        $stmt->bindParam(1, $id_paciente);
        // Executa a query
        $stmt->execute();

        $examesSolicitados = [];
        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Cria um objeto genérico para representar o exame solicitado com nome do exame
            $obj = new stdClass();
            $obj->id_exame_solicitado = $linha['id_exame_solicitado'];
            $obj->nome_exame = $linha['nome_exame'];
            $obj->data_registro = $linha['data_registro'];
            $examesSolicitados[] = $obj;
        }
        return $examesSolicitados; // Retorna o array de objetos
    }

    public function adicionarExameSolicitado($id_paciente, $id_exame, $data_registro){
        
        $query = "INSERT INTO " . $this->nome_tabela . " (id_paciente, id_exame, data_registro) VALUES (?, ?, ?)";

        // Prepara a query
        $stmt = $this->conexao->prepare($query);
        // Associa os parâmetros aos placeholders
        $stmt->bindParam(1, $id_paciente);
        $stmt->bindParam(2, $id_exame);
        $stmt->bindParam(3, $data_registro);

        // Executa a query e verifica se foi bem-sucedida
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    
    public function deletarExameSolicitado($id_exame_solicitado){
        // Query SQL para deletar um registro da tabela exames_solicitados
        $query = "DELETE FROM " . $this->nome_tabela . " WHERE id_exame_solicitado = ?";

        // Prepara a query
        $stmt = $this->conexao->prepare($query);
        // Associa o ID do exame solicitado ao placeholder
        $stmt->bindParam(1, $id_exame_solicitado);

        // Executa a query e verifica se foi bem-sucedida
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    
    public function obterTodosExames(){
        // Query SQL para selecionar todos os exames do catálogo
        $query = "SELECT id_exame, nome_exame FROM exames";
        // Prepara e executa a query
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        
        $exames = [];
        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $exames[] = new Exame($linha); // Cria um objeto Exame para cada linha
        }
        return $exames; // Retorna o array de objetos Exame
    }
}

?>

