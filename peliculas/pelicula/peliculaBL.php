<?php
    require_once '../dto/peliculaDTO.php';
    require_once '../conexion.php';

    class PeliculaBL {
        private $conexion;

        public function __construct(){
            $this->conexion = new Conexion();
        }

        public function read() {
            $this->conexion->OpenConnection();
            $connsql = $this->conexion->getConnection();
            $arrayPelicula = new ArrayObject();
            $sqlquery = "SELECT * FROM film";

            try {
                if($connsql) {
                    foreach($connsql->query($sqlquery) as $row) {
                        $array = array();
                        $array['id'] = $row['film_id'];
                        $array['titulo'] = $row['title'];
                        $array['descripcion'] = $row['description'];
                        $arrayPelicula->append($array);
                    }
                }
            } catch(PDOException $e) {
                $arrayPelicula = Array();
            }
            return $arrayPelicula;
        }

        public function readById($id) {
            $this->conexion->OpenConnection();
            $connsql = $this->conexion->getConnection();
            $arrayPelicula = new ArrayObject();
            $sqlquery = "SELECT * 
                        FROM film f INNER JOIN language l ON f.language_id = l.language_id
                        WHERE film_id = {$id}";
 
            try {
                if($connsql) {
                    foreach($connsql->query($sqlquery) as $row) {
                        $peliculaDTO = new PeliculaDTO();
                        $peliculaDTO->id = $row['film_id'];
                        $peliculaDTO->titulo = $row['title'];
                        $peliculaDTO->descripcion = $row['description'];
                        $peliculaDTO->anio = $row['release_year'];
                        $peliculaDTO->lenguaje_original = $row['name'];
                        $peliculaDTO->duracion = $row['rental_duration'];
                        $peliculaDTO->rating = $row['rating'];
                        $peliculaDTO->actores = self::ReadActorByPeliculaId($peliculaDTO->id);
                        $arrayPelicula->append($peliculaDTO);
                    }
                }
            } catch(PDOException $e) {
                $arrayPelicula = array();
            }
            return $arrayPelicula;
        }

        private function ReadActorByPeliculaId($id) {
            $this->conexion->OpenConnection();
            $connsql = $this->conexion->getConnection();
            $arrayActor = new ArrayObject();
            $sqlquery = "SELECT first_name, last_name
                        FROM actor a INNER JOIN film_actor fa ON a.actor_id = fa.actor_id
                        WHERE film_id = {$id}";
                        
            try {
                if($connsql) {
                    foreach($connsql->query($sqlquery) as $row) {
                        $actor = array();
                        $actor['nombre'] = $row['first_name'];
                        $actor['apellidos'] = $row['last_name'];
                        $arrayActor->append($actor);
                    }
                }
            } catch(PDOException $e) {
                $arrayActor = array();
            }
            return $arrayActor;
        }
    }
?>