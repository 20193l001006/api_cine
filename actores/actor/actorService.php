<?php
    require "actorBL.php";

    class ActorService 
    {
        private $actorDTO;
        private $actorBL;

        public function __construct()
        {
            $this->actorDTO = new ActorDTO();
            $this->actorBL = new ActorBL();
        }

        public function create($nombre, $apellidos)
        {
            $this->actorDTO ->nombre = $nombre;
            $this->actorDTO ->apellidos = $apellidos;
            if(($this->actorBL->create($this->actorDTO)) > 0)
                echo json_encode($this->actorDTO, JSON_PRETTY_PRINT);
            else 
                echo json_encode(array());

        }

        public function read($id) 
        {
            $this->actorDTO = $this->actorBL->read($id);
            echo json_encode($this->actorDTO, JSON_PRETTY_PRINT);
        }

        public function update($id, $nombre, $apellidos)
        {
            $this->actorDTO ->id = $id;
            $this->actorDTO ->nombre = $nombre;
            $this->actorDTO ->apellidos = $apellidos;
            if($this->actorBL->update($this->actorDTO) > 0)
                echo json_encode($this->actorDTO, JSON_PRETTY_PRINT);
            else 
                echo json_encode(array());
        }

        public function delete($id)
        {
            $this -> actorDTO -> id = $id;
            if($this -> actorBL -> delete($this -> actorDTO -> id) > 0)
                echo json_encode($id, JSON_PRETTY_PRINT);
            else
                echo json_encode(array());
        }                                                                  
    }

    $actorService = new ActorService();
    $actorDTO = new ActorDTO();

    $data = json_decode(file_get_contents("php://input"), true); 

    switch($_SERVER['REQUEST_METHOD']){
        case 'POST':
            { 
                if(empty($data)) {
                    $actorDTO -> response = array('CODE' => 'Error', 'MESSAGE' => 'Faltan valores');
                    echo json_encode($actorDTO->response, JSON_PRETTY_PRINT);
                } else {
                    $actorService -> create($data['nombre'], $data['apellidos']);
                }
            break;
            }
        case 'GET':
            {
                if(empty($_GET['param'])) {
                    $actorService -> read(0);
                } else if(is_numeric($_GET['param'])){
                    $actorService -> read($_GET['param']);
                } else {
                    $actorDTO -> response = array('CODE' => 'Error', 'MESSAGE' => 'El parametro incorrecto');
                    echo json_encode($actorDTO->response, JSON_PRETTY_PRINT);
                }
            break;
            }
        case 'PUT':
            {
                if(empty($data)) {
                    $actorDTO -> response = array('CODE' => 'Error', 'MESSAGE' => 'Faltan valores');
                    echo json_encode($actorDTO->response, JSON_PRETTY_PRINT);
                } else {
                    $actorService ->update($data['id'], $data['nombre'], $data['apellidos']);
                }
            break;
            }
        case 'DELETE':
            {
                if(empty($data)) {
                    $actorDTO -> response = array('CODE' => 'Error', 'MESSAGE' => 'Falta el Id');
                    echo json_encode($actorDTO->response, JSON_PRETTY_PRINT);
                } else {
                    $actorService -> delete($data['id']);
                }
            break;
            }
        default:
                $actorDTO -> response = array('CODE' => 'Error', 'TEXT' => 'Petición incorrecta');
                echo json_encode($actorDTO->response, JSON_PRETTY_PRINT);
            break;
    }
?>