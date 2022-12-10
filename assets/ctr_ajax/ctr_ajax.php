<?php
session_start();
include(__DIR__."/../../controller/consultaMail.controller.php");
$obj_entidades = new Controller_Read_Excel();

if (isset($_SESSION["perfil"])) {
    if (isset($_REQUEST['accion'])) {
        $metodo = $_REQUEST['accion'];
        if (method_exists($obj_entidades,$metodo)) {
            $Respuesta = $obj_entidades->$metodo();
        }else {
            $Respuesta['msg'] = "Error, la peticion no existe.";
            $Respuesta['status'] = false;
        }
        // echo json_encode($Respuesta);
    }
}else{
    $Respuesta = array('status' => false, 'msg' => 'Acceso Denegado, verifique la session.');
}

echo json_encode($Respuesta);