<?php
    require_once './categoriaBL.php';

    class CategoriaService {
        private $categoriaDTO;
        private $categoriaBL;

        public function __construct() {
            $this->categoriaDTO = new CategoriaDTO();
            $this->categoriaBL = new CategoriaBL();
        }

        public function create($nombre) {
            $this->categoriaDTO->nombre = $nombre;
            if($this->categoriaBL->create($this->categoriaDTO) > 0)
                echo json_encode($this ->categoriaDTO, JSON_PRETTY_PRINT);
            else
                echo json_encode(array());  
        }

        public function read($id) {
            $this->categoriaDTO = $this->categoriaBL->read($id);
            echo json_encode($this->categoriaDTO, JSON_PRETTY_PRINT);
        }

        public function update($id, $nombre) {
            $this->categoriaDTO->id = $id;
            $this->categoriaDTO->nombre = $nombre;
            if($this->categoriaBL->update($this->categoriaDTO) > 0)
                echo json_encode($this->categoriaDTO, JSON_PRETTY_PRINT);
            else
                echo json_encode(array());
        }

        public function delete($id) {
            $this->categoriaDTO->id = $id;
            if($this->categoriaBL->delete($this->categoriaDTO->id) > 0)
                echo json_encode($id, JSON_PRETTY_PRINT);
            else
                echo json_encode(array());
        }
    }

    $categoriaService = new CategoriaService();
    $categoriaDTO = new CategoriaDTO();
    
    $data = json_decode(file_get_contents('php://input'), true);

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET': {
                if(empty($_GET['param'])) {
                    $categoriaService->read(0);
                } else {
                    if(is_numeric($_GET['param'])){
                        $categoriaService->read($_GET['param']);
                    } else {
                        $categoriaDTO->response = array('CODE' => 'Error', 'MESSAGE' => 'El parametro debe ser numerico');
                        echo json_encode($categoriaDTO->response, JSON_PRETTY_PRINT);
                    }
                }
            break;
        }
        case 'POST': {
                if(!isset($data['nombre']) && empty($data['nombre'])) {
                    $categoriaDTO->response = array('CODE' => 'Error', 'MESSAGE' => 'Faltan valores');
                    echo json_encode($categoriaDTO->response, JSON_PRETTY_PRINT);
                } else {
                    $categoriaService->create($data['nombre']);
                }
            break;
        }
        case 'PUT': {
                if((!isset($data['nombre']) && empty($data['nombre'])) && (!isset($data['id']) && empty($data['id']))) {
                    $categoriaDTO->response = array('CODE' => 'Error', 'MESSAGE' => 'Faltan valores');
                    echo json_encode($categoriaDTO->response, JSON_PRETTY_PRINT);
                } else {
                    $categoriaService->update($data['id'], $data['nombre']);
                }
            break;
        }
        case 'DELETE': {
                if(!isset($data['id']) && empty($data['id'])) {
                    $categoriaDTO->response = array('CODE' => 'Error', 'MESSAGE' => 'Falta el Id');
                    echo json_encode($categoriaDTO->response, JSON_PRETTY_PRINT);
                } else {
                    $categoriaService->delete($data['id']);
                }
            break;
        }
        default: {
            $categoriaDTO = new CategoriaDTO();
            $categoriaDTO->response = array('CODE' => 'Error', 'MESSAGE' => 'Peticion incorrecta');
            echo json_encode($categoriaDTO->response, JSON_PRETTY_PRINT);
        }
    }
?>