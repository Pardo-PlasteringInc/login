<?php

$con = new mysqli('sql301.tonohost.com','ottos_24675194','8n31v4df','ottos_24675194_registerphp');
if($con -> connect_error){
    die('Conexion no establecida: ' . $con -> connect_error);
}
// else{
//     echo 'Conexion exitosa';
// }

?>