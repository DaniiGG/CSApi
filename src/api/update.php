<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../config/config.php';
require_once '../Lib/BaseDatos.php';
 require_once '../Models/Paciente.php';


use Models\Paciente;

$patient = new Paciente();
$data = (array) json_decode(file_get_contents('php://input'), TRUE);
// 030 que no estamos validando. Debería existir algun método del tipo $this->validate Paciente($data)
if((!empty($data["id"])) && (!empty($data["nombre"])) && (!empty($data["apellidos"])) &&
(!empty($data["correo"])) && (!empty($data["password"])))
{
$result =$patient->find($data["id"]);
if (!$result) {
http_response_code(404);
echo json_encode(array("message" => "Paciente no encontrado."));
}else{
if($patient->update($data)){
http_response_code(200);
echo json_encode(array("message" => "Paciente modificado con exito."));
} else {
http_response_code(503);
echo json_encode(array("message" => "No se ha podido modificar los datos del paciente."));
}
}
} else {
http_response_code(400);
echo json_encode(array("message" => "No se ha podido modificar los datos del paciente. Revise los datos. Datos incompletos"));
}