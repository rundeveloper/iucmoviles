<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUSION DE LA BASE DE DATOS Y CREACION DEL OBJETO DE CONEXION
require 'config.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// FORMA DE SOLICITUD
$data = json_decode(file_get_contents("php://input"));

// VERIFICACION, SI ID ESTA DISPONIBLE EN $data
if(isset($data->id)){
    $msg['message'] = '';    
    $post_id = $data->id;
    
    // OBTENER EMPLEADOS POR ID
    $check_post = "SELECT * FROM `estudiante` WHERE id=:post_id";
    $check_post_stmt = $conn->prepare($check_post);
    $check_post_stmt->bindValue(':post_id', $post_id,PDO::PARAM_INT);
    $check_post_stmt->execute();
    
    // VERIFICAMOS SI HAY ESTUDIANTES EN CONSULTA
    if($check_post_stmt->rowCount() > 0){
        
        //ELIMINAR ESTUDIANTE POR ID DE LA BASE DE DATOS
        $delete_post = "DELETE FROM `estudiante` WHERE id=:post_id";
        $delete_post_stmt = $conn->prepare($delete_post);
        $delete_post_stmt->bindValue(':post_id', $post_id,PDO::PARAM_INT);
        
        if($delete_post_stmt->execute()){
            $msg['message'] = 'Estudiante Eliminado Correctamente';
        }else{
            $msg['message'] = 'Estidante No Eliminado';
        }
    }else{
        $msg['message'] = 'ID invalido';
    }
    // SALIDA EN FORMATO JSON
    echo  json_encode($msg);
}
?>
