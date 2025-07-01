<?php


class Database {
    private $host = "localhost";
    private $nome_banco = "projetointegrado"; 
    private $usuario = "root"; 
    private $senha = ""; 
    public $conexao;

    public function obterConexao(){
        $this->conexao = null;

        try{
            $this->conexao = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->nome_banco, $this->usuario, $this->senha);
            $this->conexao->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Erro de conexão: " . $exception->getMessage();
        }

        return $this->conexao; 
    }
}

?>

