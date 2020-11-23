<?php
        class conexion {
            private $Host = "";
            private $User = "";
            private $Password = "";
            private $DataBase = "";
            private $Connection = "";

            public function __construct() {
                $this -> Host = 'sakila.mysql.database.azure.com';
                $this -> User = 'ahc2806@sakila';
                $this -> Password = 'CiA28069';
                $this -> DataBase = 'sakila';
            }

            public function OpenConnection() {
                try{
                    $this-> Connection = new PDO("mysql:host={$this->Host}; dbname={$this->DataBase}", $this->User, $this->Password);
                    $this-> Connection-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e){
                    $this-> Connection = false;
                }
            }

            public function ClosConnection() {
                mysql_close($this-> Connection);
            }

            public function GetConnection() {
                return $this-> Connection;
            }
        }
?>