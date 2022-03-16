<?php
    class Database{
        private $hostname = 'odontogama.online';
        private $username = 'odontoga_admin';
        private $password = 'Zxcvbnm@2022';
        private $database = 'odontoga_agenda';
        private $conexao;

        public function conectar(){
            $this->conexao = null;
            try
            {
                $this->conexao = new PDO('mysql:host=' . $this->hostname . ';dbname=' . $this->database . ';charset=utf8', 
                $this->username, $this->password);
            }
            catch(Exception $e)
            {
                die('Erro : '.$e->getMessage());
            }

            return $this->conexao;
        }
    }
?>
