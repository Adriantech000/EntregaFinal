<?php

include_once __DIR__ . "/../Dao/Database.php"; 
include_once __DIR__ . "/../Dao/ExameDAO.php"; 
include_once __DIR__ . "/../Model/Exame.php"; 


class ExameController {
    private $daoExame; 

    public function __construct(){
        $database = new Database(); 
        $conexao = $database->obterConexao(); 
        $this->daoExame = new ExameDAO($conexao); 
    }

    public function obterExamesPorPacienteId($id_paciente){
        // Chama o método obterExamesPorPacienteId do DAO, que já retorna um array de objetos
        return $this->daoExame->obterExamesPorPacienteId($id_paciente);
    }

    public function adicionarExameSolicitado($id_paciente, $id_exame, $data_registro){
        // Chama o método adicionarExameSolicitado do DAO e retorna o resultado
        return $this->daoExame->adicionarExameSolicitado($id_paciente, $id_exame, $data_registro);
    }

    public function deletarExameSolicitado($id_exame_solicitado){
        // Chama o método deletarExameSolicitado do DAO e retorna o resultado
        return $this->daoExame->deletarExameSolicitado($id_exame_solicitado);
    }

    public function obterTodosExames(){
        // Chama o método obterTodosExames do DAO, que já retorna um array de objetos Exame
        return $this->daoExame->obterTodosExames();
    }
}

?>

