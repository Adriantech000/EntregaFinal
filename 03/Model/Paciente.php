<?php

class Paciente {
    // Propriedades que correspondem às colunas da tabela 'paciente'
    public $registro; // INT(11) NOT NULL AUTO_INCREMENT
    public $nome; // VARCHAR(255) NOT NULL
    public $nomeSocial; // VARCHAR(255) NOT NULL
    public $Sexo; // VARCHAR(255) NOT NULL
    public $telefone; // VARCHAR(20) DEFAULT NULL
    public $data; // DATE DEFAULT NULL
    public $periodo; // VARCHAR(50) DEFAULT NULL
    public $nomeMae; // VARCHAR(255) DEFAULT NULL
    public $examesSolicitados; // VARCHAR(255) DEFAULT NULL
    public $Email; // VARCHAR(255) DEFAULT NULL
    public $Data_Nascimento; // DATE DEFAULT NULL
    public $medicamento; // VARCHAR(255) DEFAULT NULL
    public $medicamentoNome; // VARCHAR(255) DEFAULT NULL
    public $patologia; // VARCHAR(255) DEFAULT NULL

    public function __construct($dados = null) {
        if (is_array($dados)) {
            foreach ($dados as $chave => $valor) {
                // Verifica se a propriedade existe na classe antes de atribuir
                if (property_exists($this, $chave)) {
                    $this->$chave = $valor;
                }
            }
        }
    }
}

?>

