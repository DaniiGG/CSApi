<?php
/* Borrar un registro que existe */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once '../config/config.php'; 
require_once '../Lib/BaseDatos.php'; 
require_once '../Models/Paciente.php';

use Models\Paciente;

$patient - new Paciente();

$data = json_decode(file_get_contents("php://input"));
//030 CON LAS CLAVES FORANEAS 

if(!empty($data->id)) {
$id = $data->id;
$result = $patient->find($id);
if (!$result) {
http_response_code (404);
echo json_encode(array("message" => "Paciente no encontrado."));
}else{
    if ($patient->delete($id)){
        http_response_code(200);
        echo json_encode(array("message" => "Paciente borrado."));
} else {
    http_response_code(503);
echo json_encode(array("message" => "No se ha podido borrar el paciente."));
}

}
} else {

http_response_code (400);
echo json_encode(array("message" => "No se ha podido borrar el paciente. Revise los datos"));
}
?>