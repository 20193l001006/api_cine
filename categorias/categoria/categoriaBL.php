<?php
     require "../conexion.php";
     require "../dto/categoriaDTO.php";

    class CategoriaBL {
        private $conexion;

        public function __construct(){
            $this->conexion = new Conexion();
        }

        public function create($categoriaDTO) {
            $this->conexion->OpenConnection();
            $connsql = $this->conexion->getConnection();
            $lastInsertId = 0;
            
            try{
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatment = $connsql->prepare(
                        "INSERT INTO category VALUES(
                            default,
                            :name,
                            current_timestamp
                        )"
                    );

                    $sqlStatment->bindParam(':name', $categoriaDTO->nombre);
                    $sqlStatment->execute();

                    $lastInsertId = $connsql->lastInsertId();
                    $connsql->commit();
                }
            } catch(PDOException $e) {
                $connsql -> rollBack();
                $lastInsertId = 0;
            }
            return $lastInsertId;
        }

        public function read($id) {
            $this->conexion->OpenConnection();
            $connsql = $this->conexion->getConnection();
            $arrayCategoria = new ArrayObject();
            $sqlquery = "SELECT * FROM category";

            if($id > 0)
                $sqlquery = "SELECT * FROM category WHERE category_id = {$id}";

            try {
                if($connsql) {
                    foreach($connsql->query($sqlquery) as $row) {
                        $categoriaDTO = new CategoriaDTO();
                        $categoriaDTO->id = $row['category_id'];
                        $categoriaDTO->nombre = $row['name'];
                        $arrayCategoria->append($categoriaDTO);
                    }
                }
            } catch(PDOException $e) {
                $arrayCategoria = Array();
            }
            return $arrayCategoria;
        }

        public function update($categoriaDTO) {
            $this->conexion->OpenConnection();
            $connsql = $this->conexion->getConnection();
            
            try{
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatment = $connsql->prepare(
                        "UPDATE category SET 
                            name = :name
                        WHERE category_id = :id"
                    );

                    $sqlStatment -> bindParam(':id', $categoriaDTO->id);
                    $sqlStatment -> bindParam(':name', $categoriaDTO->nombre);
                    $sqlStatment -> execute();

                    $connsql -> commit();
                    return true;
                }
            } catch(PDOException $e) {
                $connsql -> rollBack();
                return false;
            }
        }

        public function delete($id) {
            $this->conexion->OpenConnection();
            $connsql = $this->conexion->getConnection();
            
            try{
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatment = $connsql->prepare(
                        "DELETE FROM category 
                        WHERE category_id = {$id}"
                    );
                    $sqlStatment->execute();
                    $connsql->commit();
                    return $id;
                }
            } catch(PDOException $e) {
                $connsql -> rollBack();
                return 0;
            }
        }
    }
?>