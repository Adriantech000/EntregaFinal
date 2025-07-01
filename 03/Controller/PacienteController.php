<?php

include_once __DIR__ . "/../Dao/Database.php"; 
include_once __DIR__ . "/../Dao/PacienteDAO.php"; 
include_once __DIR__ . "/../Model/Paciente.php"; 


class PacienteController {
    private $daoPaciente; 

    public function __construct(){
        $database = new Database(); // Cria uma nova instância da classe Database
        $conexao = $database->obterConexao(); // Obtém a conexão com o banco de dados
        $this->daoPaciente = new PacienteDAO($conexao); // Cria uma instância de PacienteDAO, passando a conexão
    }

    public function buscarPaciente($termoBusca){
        // Chama o método buscar do DAO, que já retorna um array de objetos Paciente
        return $this->daoPaciente->buscar($termoBusca);
    }

    public function obterPacientePorId($registro){
        // Chama o método obterPorId do DAO, que já retorna um objeto Paciente ou null
        return $this->daoPaciente->obterPorId($registro);
    }
}

?>

