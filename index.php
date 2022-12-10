<?php

/**
 * 
 * Descripcion:
 * 
 * 
 *
 * @author Alexander B.
 * 
 * @todo 
 * 
 * @version  1.0
 */
session_start();

//FORZAMOS OCULTAR NOTICES 
// error_reporting(E_ALL ^ E_NOTICE);
require_once("controller/consultaMail.controller.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta bandeja del correo</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>

    <div class="col-12">
        <div class="col-12">
            <button type="button" class="btn btn-outline-primary " id ="btn_consulta_mail" contenedor_space="contenedor_tabla_mails" accion="obtenerReparto" >Extraer Correos</button>
        </div>

        <div class="col-12">
            <h4 class="text-center text-danger mt-3"> CONSULTA BANDEJA DE ENTRADA - CORREO </h4> 
        </div> 

        <div class="col-12 " id="contenedor_tabla_mails">

            <table class="table table-primary table-striped table-hover  table-bordered ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Cod.</th>
                        <th scope="col">De</th>
                        <th scope="col">Para</th>
                        <th scope="col">Asunto</th>
                        <th scope="col">Fecha Mail</th>
                        <th scope="col">Hora</th>
                        <th scope="col">archivos</th>
                        <th scope="col">Cuerpo del correo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="12">
                            <div class="col-12" style="display: flex; justify-content: center;">
                                <h4 class="m-3" style="color: #b1b1b1">
                                    <a href="#!" class="btn btn-outline-primary " id ="btn_consulta_mail" contenedor_space="contenedor_tabla_mails" accion="obtenerReparto" >Extraer Correos</a>
                                </h4> 
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>


        </div>
    </div>

    <script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/sweetalert2.all.js"></script>
    <script type="text/javascript" src="assets/js/controles.js"></script>
</body> 
</html>