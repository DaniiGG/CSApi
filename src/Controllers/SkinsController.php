<?php

namespace Controllers;

use Lib\Pages;
use Models\Skins;
use Models\Usuario;
use Lib\Security;
use Lib\ResponseHttp;
class SkinsController
{
    private Pages $pages;
    function __construct(){
        $this->pages= new Pages();
    }

    public function validarToken(){
        $tokenValidado =Security::validateToken();

        $resultado=false;

        if($tokenValidado){
            $datos=$tokenValidado['data'];
            $usuario=Usuario::fromArray([]);
            $usuarioEmail=$usuario->buscaMail($datos->data->email);

            if($usuarioEmail){

                if ($usuarioEmail->token==$tokenValidado['token']){
                    $resultado=true;
                }else{
                    echo ResponseHttp::statusMEssage(404,"El token proporcionado no existe",$tokenValidado['token']);
                }
            }else{
                echo ResponseHttp::statusMEssage(404,"Ese correo no existe",'');
            }
        }
        return $resultado ;

    }
    
    public function  read():void{
        $this->header();
        
        if($this->validarToken()){

        
        $skin = new Skins(); 
        $skins = $skin->getAll(); 
        $skinsCount = $skin->filasAfectadas();
        if ($skinsCount >0){
            $skinsArr = array();
            $skinsArr["numberSkins"] = $skinsCount; 
            $skinsArr["skins"] = array();
            foreach ($skins as $fila) {
                array_push($skinsArr["skins"], $fila);
            }
            
            http_response_code(202);
            echo json_encode($skinsArr);
        }
        
        else{
        http_response_code(404);
        echo json_encode(
        array("message" => "No hay skins"));
        }
    }

    }


    public function findByTipo($tipo): void
    {
        $this->header();

        if($this->validarToken()){
            
        $skin = new Skins();
        $result = $skin->findTipo($tipo);

        if ($result) {
            http_response_code(200);
            echo json_encode(["message" => "Skin encontrada.", "skin" => $result]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Skin no encontrada."]);
        }
    }
    }


    public function findByPrecio($precio): void
    {
        $this->header();
        if($this->validarToken()){
        $skin = new Skins();
        $result = $skin->findPrecio($precio);

        if ($result) {
            http_response_code(200);
            echo json_encode(["message" => "Skins encontradas por ese precio.", "skin" => $result]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Skin no encontrada por ese precio."]);
        }
    }
    }

    public function  create():void{
        $this->header();
        /*
        Para recibir una cadena JSON, podemos usar
        "php://input" (Devuelve todos los datos sin procesar después de los encabezados HTTP de la solicitud, independientemente del tipo de contenido) junto con la función file_get_contents() que nos ayuda a recibir datos JSON como un archivo y leerlos en una cadena.
        */
        if($this->validarToken()){
        $skin = new Skins(); 
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->nombre) && !empty($data->tipo) && !empty($data->imagen) &&
        !empty($data->desgaste) && !empty($data->precio)){
        /* 030 que no estamos validando*/ 
        $skin->setNombre ($data->nombre);
        $skin->setTipo ($data->tipo);
        $skin->setImagen($data->imagen);
        $skin->setDesgaste ($data->desgaste);
        $skin->setPrecio ($data->precio);
        
        if($skin->creaSkin()){
        http_response_code (201);
        echo json_encode(array("message" => "Skin creada con exito")
        );
        }else{

        http_response_code(503);
        echo json_encode(array("message" => "No se ha podido añadir una nueva skin")
        );
        }
        }else{
        http_response_code(400);
        echo json_encode(
            array("message" => "No se ha podido crear. Datos incompletos."));
        }
    }
    }


    public function  update():void{
        $this->header();

        if($this->validarToken()){
        $skin = new Skins();
        $data = (array) json_decode(file_get_contents('php://input'), TRUE);
        // 030 que no estamos validando. Debería existir algun método del tipo $this->validate Paciente($data)
        if((!empty($data["id"])) && (!empty($data["nombre"])) && (!empty($data["tipo"])) &&
        (!empty($data["imagen"])) && (!empty($data["desgaste"])) && (!empty($data["precio"])))
        {
        $result =$skin->find($data["id"]);
        if (!$result) {
        http_response_code(404);
        echo json_encode(array("message" => "skin no encontrada."));
        }else{
        if($skin->update($data)){
        http_response_code(200);
        echo json_encode(array("message" => "Skin modificada con exito."));
        } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se ha podido modificar los datos de la skin."));
        }
        }
        } else {
        http_response_code(400);
        echo json_encode(array("message" => "No se ha podido modificar los datos del paciente. Revise los datos. Datos incompletos"));
        }
    }
    }


    public function delete($id): void {
        $this->header();
    
        if ($this->validarToken()) {
            $skin = new Skins();
    
            if (!empty($id)) {
                $result = $skin->find($id);
    
                if (!$result) {
                    http_response_code(404);
                    echo json_encode(array("message" => "Skin no encontrada."));
                } else {
                    if ($skin->delete($id)) {
                        http_response_code(200);
                        echo json_encode(array("message" => "Skin borrada."));
                    } else {
                        http_response_code(503);
                        echo json_encode(array("message" => "No se ha podido borrar la skin."));
                    }
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "No se ha proporcionado un ID válido."));
            }
        }
    }



    public function header(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Methods: DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }



}