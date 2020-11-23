<?php
    require "../conexion.php";
    require "../dto/actorDTO.php";

    class ActorBL
    {
        private $conn;

        public function __construct()//metodo constructor
        {
            $this -> conn = new Conexion();//llama a la clase conexión
        }

        public function create($actorDTO) 
        {
            $this -> conn -> OpenConnection();
            $Connsql = $this -> conn -> GetConnection();
            $lastInsertId = 0;
            try{
                if($Connsql){
                    $Connsql -> beginTransaction();
                    $sqlStatment = $Connsql -> prepare(
                        "INSERT INTO actor VALUES(
                            default,
                            :first_name,
                            :last_name,
                            current_timestamp
                        )"
                    );

                    $sqlStatment -> bindParam(':first_name', $actorDTO->nombre);
                    $sqlStatment -> bindParam(':last_name', $actorDTO->apellidos);
                    $sqlStatment -> execute();

                    $lastInsertId = $Connsql -> lastInsertId();
                    $Connsql -> commit();
                }
            }catch(PDOException $e){
                $Connsql -> rollback();
                $lastInsertId = 0;
            }
            return $lastInsertId;
        }

        public function read($id)
        {
            $this -> conn -> OpenConnection();
            $Connsql = $this -> conn -> GetConnection();
            $arrayActor = new ArrayObject();
            $SQLQuery = "SELECT * FROM actor";

            if($id > 0)
                $SQLQuery = "SELECT * FROM actor WHERE actor_id = {$id}";
                try{
                    if($Connsql)
                        foreach($Connsql->query($SQLQuery) as $row){
                            $actorDTO = new ActorDTO();
                            $actorDTO -> id = $row['actor_id'];
                            $actorDTO -> nombre = $row['first_name'];
                            $actorDTO -> apellidos = $row['last_name'];
                            $arrayActor->append($actorDTO);
                        }

                } catch(PDOException $e){

                }
                return $arrayActor;
        }

        public function update($actorDTO)
        {
            $this -> conn -> OpenConnection();
            $Connsql = $this -> conn -> GetConnection();

            try{
                if($Connsql){
                    $Connsql -> beginTransaction();
                    $sqlStatment = $Connsql -> prepare(
                        "UPDATE actor SET
                            first_name = :first_name,
                            last_name = :last_name,
                            last_update = current_timestamp
                            WHERE actor_id = :id"
                    );

                    $sqlStatment -> bindParam(':id', $actorDTO->id);
                    $sqlStatment -> bindParam(':first_name', $actorDTO->nombre);
                    $sqlStatment -> bindParam(':last_name', $actorDTO->apellidos);
                    $sqlStatment -> execute();

                    $Connsql -> commit();
                    return true;
                }
            }catch(PDOException $e){
                $Connsql -> rollback();
                return false;
            }
        }

        public function delete($id){ 
            $this -> conn -> OpenConnection();
            $Connsql = $this -> conn -> GetConnection();

            try{
                if($Connsql){
                    $Connsql -> beginTransaction();
                    $sqlStatment = $Connsql -> prepare(
                        "DELETE FROM actor
                            WHERE actor_id = :id"
                    );
                   
                    $sqlStatment -> bindParam(':id', $id); 
                    $sqlStatment -> execute();
                    $Connsql -> commit();
                    
                    return $id;
                    
                }
            }catch(PDOException $e){
                $Connsql -> rollback();
                return 0;
            }
        }
    }

?>