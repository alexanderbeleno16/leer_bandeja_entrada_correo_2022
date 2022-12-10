<?php
require_once(__DIR__.'/../model/consultaMail.class.php');
/**
 * Controlador
 */
class Controller_Read_Excel
{

	public $Model;

	function __construct()
	{
		$this->Model = new OptieneMails();
        
	}

    public function obtenerReparto()
    {
        $rst["status"] = true;
        $rst["html"]   = "";
        
        if ($this->Model->isConnect()) {
            $resp = $this->Model->buscarMails();

            if (is_array($resp) && !empty($resp)) {

                $rst["html"] .= '
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
                ';
                
                    $datos = []; $n=0;
                    foreach ($resp as $i => $mail) {$n++;
                        
                        $hora = subStr($mail['header']->date, 16, 9);
                        
                        $asunto = $this->Model->fix_text_subject($mail['header']->Subject);
                        $archivos = $this->Model->extract_attachments($mail['index']);

                        // var_dump($hora);
                        // var_dump($asunto);
                        // var_dump($mail['body']);
                        // var_dump($mail['header']->fromaddress);
                        // exit();


                        $cuerpoMail = 'body_correo'.rand(1,100).'.html';
                        file_put_contents(__DIR__.'/../archivosMail/'.$cuerpoMail, $mail['body']);

                        $enlacesArchivos=$enlaceCuerpoMail="";
                        $enlaceCuerpoMail .= '<a href="archivosMail/'.$cuerpoMail.'" class="text-danger fw-bold" target="_blank" >Ver</a>';
                        if (is_array($archivos)) {
                            foreach ($archivos as $file) {
                                // if( $file != "=?UTF-8?Q?Recurso_extraordinario_de_revisi=C3=B3n_Activos_2014-648.pdf?=" ){
                                    $enlacesArchivos .= '<li>
                                                        <a href="archivosMail/' . $file . '" target="_blank" >Archivo</a>
                                                        <a href="archivosMail/' . $file . '" download="' . $file . '" class="text-danger fw-bold" >Descargar</a>
                                                    </li>';
                                // }
                            }
                        }

                        $rst["html"] .= '
                                <tr>
                                    <td scope="row">'.($n).'</td>
                                    <td>'.rand(1,100).'</td>
                                    <td>'.($mail['header']->fromaddress).'</td>
                                    <td>'.($mail['header']->toaddress).'</td>
                                    <td>'.$asunto.'</td>
                                    <td>'.($mail['header']->date).'</td>
                                    <td>'.$hora.'</td>
                                    <td>'.$enlacesArchivos.'</td>
                                    <td>'.$enlaceCuerpoMail.'</td>
                                </tr>
                        ';
                    }

                    $rst["html"] .= '
                        </tbody>
                    </table>
                    ';
                $rst['datos'] = $datos;
            } else {
                $rst["status"]  = false;
                $rst["msg"]     = "No se encontraron correos...";
            }
        } else {
            $rst["status"]  = false;
            $rst["msg"]     = "ERROR, no se pudo conectar al Mail.";
        }

        return $rst;
    }



}