<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
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
    
    // CONSULTA DE ID DESDE LA BASE DE DATOS
    $get_post = "SELECT * FROM `estudiante` WHERE id=:post_id";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':post_id', $post_id,PDO::PARAM_INT);
    $get_stmt->execute();
    
    // COMPRUEBO SI YA HAY CREADO UN ESTUDIANTE
    if($get_stmt->rowCount() > 0){
        
        // OBTENER ESTUDIANTES DE LA BASE DE DATOS
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
        // COMPROBAMOS SI HAY DATOS NUEVOS Y LOS ACTUALIZAMOS SOBRE LOS DATOS ANTERIORES
        $post_identificacion = isset($data->identificacion) ? $data->identificacion : $row['identificacion'];
        $post_nombre = isset($data->nombre) ? $data->nombre : $row['nombre'];
        $post_curso = isset($data->curso) ? $data->curso : $row['curso'];
		$post_nota1 = isset($data->nota1) ? $data->nota1 : $row['nota1'];
		$post_nota2 = isset($data->nota1) ? $data->nota1 : $row['nota2'];
		$post_nota3 = isset($data->nota1) ? $data->nota1 : $row['nota3'];
        
        $update_query = "UPDATE `estudiante` SET identificacion = :identificacion, nombre = :nombre, curso = :curso, nota1 = :nota1, nota2 = :nota2, nota3 = :nota3
        WHERE id = :id";
        
        $update_stmt = $conn->prepare($update_query);
        
        // DATA BINDING
        $update_stmt->bindValue(':identificacion', htmlspecialchars(strip_tags($post_identificacion)),PDO::PARAM_STR);
        $update_stmt->bindValue(':nombre', htmlspecialchars(strip_tags($post_nombre)),PDO::PARAM_STR);
        $update_stmt->bindValue(':curso', htmlspecialchars(strip_tags($post_curso)),PDO::PARAM_STR);
		$update_stmt->bindValue(':nota1', htmlspecialchars(strip_tags($post_nota1)),PDO::PARAM_STR);
		$update_stmt->bindValue(':nota2', htmlspecialchars(strip_tags($post_nota2)),PDO::PARAM_STR);
		$update_stmt->bindValue(':nota3', htmlspecialchars(strip_tags($post_nota3)),PDO::PARAM_STR);
        $update_stmt->bindValue(':id', $post_id,PDO::PARAM_INT);
            
        if($update_stmt->execute()){
            $msg['message'] = 'Datos Actualizados Correctamente';
        }else{
            $msg['message'] = 'datos no encontrados';
        }   
    }
    else{
        $msg['message'] = 'ID invalido';
    }
	// SALIDA EN FORMATO JSON	
    echo  json_encode($msg);
}
?>
