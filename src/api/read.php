<?php

//petición sin credenciales, cualquiera puede acceder al recurso
header("Access-Control-Allow-Origin: *");
//Tiempo máximo, en segundos, que los datos se almacenan en cache 
header("Access-Control-Max-Age: 3600");
//informa al navegador que el docuento serán un JSON. 
header("Content-Type: application/json; charset=UTF-8");
require_once '../config/config.php'; 

use Lib\BaseDatos;
use Models\Ponentes;

$patient = new Ponentes(); 
$pacientes = $patient->getAll(); 
$patientsCount = $patient->filasAfectadas();
if ($patientsCount >0){
    $PacienteArr = array();
    $PacienteArr["numberPatients"] = $patientsCount; 
    $PacienteArr["patients"] = array();
    foreach ($pacientes as $fila) {
        array_push($PacienteArr["patients"], $fila);
    }
    
    http_response_code(202);
    echo json_encode($PacienteArr);
}

else{
http_response_code(404);
echo json_encode(
array("message" => "No hay pacientes"));
}