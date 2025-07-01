<?php


class Exame {
    public $id_exame; 
    public $nome_exame; 

    public function __construct($dados = null) {
        if (is_array($dados)) {
            foreach ($dados as $chave => $valor) {
                if (property_exists($this, $chave)) {
                    $this->$chave = $valor;
                }
            }
        }
    }
}

?>

