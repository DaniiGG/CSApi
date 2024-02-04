<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); //para acceder al recurso se hace con el método POST
header("Access-Control-Allow-Methods: POST");
// máximo de segundos en caché para los resultados de una solicitud
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once '../config/config.php'; 
require_once '../Lib/BaseDatos.php'; 
require_once '../Models/Paciente.php';
use Models\Paciente;
$patient = new Paciente();
/*
Para recibir una cadena JSON, podemos usar
"php://input" (Devuelve todos los datos sin procesar después de los encabezados HTTP de la solicitud, independientemente del tipo de contenido) junto con la función file_get_contents() que nos ayuda a recibir datos JSON como un archivo y leerlos en una cadena.
*/
$data = json_decode(file_get_contents("php://input"));
if(!empty($data->nombre) && !empty($data->apellidos) && 
!empty($data->correo) && !empty($data->password)){
/* 030 que no estamos validando*/ 
$patient->setNombre ($data->nombre);
$patient->setApellidos ($data->apellidos);
$patient->setCorreo ($data->correo);
$patient->setPassword($data->password);
if($patient->creaPaciente()){
http_response_code (201);
echo json_encode(array("message" => "Paciente creado con exito")
);
}else{

http_response_code(503);
echo json_encode(array("message" => "No se ha podido añadir un nuevo paciente")
);
}
}else{
http_response_code(400);
echo json_encode(
    array("message" => "No se ha podido crear. Datos incompletos."));
}
?>