<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUSION DE LA BASE DE DATOS Y CREACION DEL OBJETO DE CONEXION
require 'config.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// FORMA DE SOLICITUD
$data = json_decode(file_get_contents("php://input"));

// CREACION DEL ARREGLO MENSAJE Y DEFINICION EN VACIO
$msg['message'] = '';

// VERIFICACION DEL RECIBIMIENTO DE INFORMACION
if(isset($data->identificacion) && isset($data->nombre) && isset($data->curso) && isset($data->nota1) && isset($data->nota2) && isset($data->nota3)){
    // VERIFICACION QUE LOS DATOS NO ESTEN VACIOS
    if(!empty($data->identificacion) && !empty($data->nombre) && !empty($data->curso) && !empty($data->nota1) && !empty($data->nota2) && !empty($data->nota3)){
        $insert_query = "INSERT INTO `estudiante`(identificacion,nombre,curso,nota1,nota2,nota3) VALUES(:identificacion,:nombre,:curso,:nota1,:nota2,:nota3)";
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':identificacion', htmlspecialchars(strip_tags($data->identificacion)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':nombre', htmlspecialchars(strip_tags($data->nombre)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':curso', htmlspecialchars(strip_tags($data->curso)),PDO::PARAM_STR);
		$insert_stmt->bindValue(':nota1', htmlspecialchars(strip_tags($data->nota1)),PDO::PARAM_STR);
		$insert_stmt->bindValue(':nota2', htmlspecialchars(strip_tags($data->nota2)),PDO::PARAM_STR);
		$insert_stmt->bindValue(':nota3', htmlspecialchars(strip_tags($data->nota3)),PDO::PARAM_STR);
        
        if($insert_stmt->execute()){
            $msg['message'] = 'Datos Insertados Correctamente';
        }else{
            $msg['message'] = 'Datos no Insertados';
        } 
        
    }else{
        $msg['message'] = 'Oops! hay un campo desocupado. Por Favor diligenciar todos los campos.';
    }
}
else{
    $msg['message'] = 'Desde API: Por favor diligenciar todos los campos | identificacion, nombre, curso, nota uno, nota dos, nota tres';
}
// SALIDA EN FORMATO JSON
echo  json_encode($msg);
?>
