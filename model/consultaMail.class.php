<?php 
set_time_limit(9999);

class OptieneMails{
    // private $mailbox="{mail.lupajuridica.com:993/imap/ssl}";
    private $conexion;
    private $mailbox="{imap.gmail.com:993/imap/ssl}INBOX";
    private $user = "ejemplo@gmail.com";
    private $pass = "ejemplo";  //CLAVE GENERADA POR GMAIL PARA APPS DE TERCEROS #1

    // private $fecha = "20-AUG-2021"; 
    // private $fecha;

    
    function __construct() {
        $this->conexion = false;

        // if ($user!="" and $pass!="") {
            $this->conexion = @imap_open($this->mailbox, $this->user, $this->pass, null, 1, array('DISABLE_AUTHENTICATOR' => 'GSSAPI'));
            
            // var_dump(imap_errors());
        // }
    }

    function isConnect(): ?bool{
        return (!$this->conexion === false);
        
    }

    function buscarMails($filtro=[]): ?array{
        $fecha = "05-MAY-2020";
        // to:(ejemplo@gmail.com) after:2021/12/9 before:2023/12/10
        // $emails=imap_search($this->conexion, 'TO "ejemplo@gmail.com" AFTER "05-MAY-2020" BEFORE "31-DEC-2023"'); 
        $emails=imap_search($this->conexion, 'TO "ejemplo@gmail.com" SINCE "05-May-2020" OLD'); 
        // $emails=imap_search($this->conexion, 'TO "ejemplo@gmail.com" SINCE "'.$fecha.'" '); 
        #$emails=imap_search($this->conexion, 'TO "ejemplo@gmail.com" SEEN'); 
        #$emails = imap_search($this->conexion, 'SUBJECT "Notifica Lupajuridica" SINCE "1 May 2020"');
        // $emails=imap_search($this->conexion, 'ALL'); 
        #var_dump('<pre>',$emails, '</pre>');
        #exit();

        $inbox = [];
        if(is_array($emails)) {
            foreach($emails as $i){
                $inbox[$i] = [
                'index'     => $i,
                'header'    => imap_headerinfo($this->conexion, $i),
                'body'      => imap_fetchbody($this->conexion, $i, 1.2),
                'structure' => imap_fetchstructure($this->conexion, $i),
                ];
            }
        }
        
        return $inbox;
    }
 
    //arregla texto de asunto
    function fix_text_subject($str) {
        $subject = '';
        $subject_array = imap_mime_header_decode($str);
 
        foreach ($subject_array AS $obj)
            $subject .= trim($obj->text);
 
        return $subject;
    }

    //tabla user, pass y filtro fecha
    function tablaInicioSessionCorreo(){
        $tabla = "";
        $hidden = "";
        if ($this->isConnect()) {
            $hidden = "hidden";
        }
        
        #TABLA CREDENCIALES (USER, PASS)
        $tabla .= '<table class="table table-bordered" '.$hidden.'>';

        $tabla .='<thead class="table-secondary">';
            $tabla .='<tr>';
                $tabla .='<th colspan="2" class="text-center fs-5">CREDENCIALES (CORREO) <i class="bi bi-key-fill fs-4"></i> <i class="bi bi-envelope-fill"></i></th>';
            $tabla .='</tr>';
        $tabla .='</thead>';

        $tabla .='<tbody>';
            $tabla .='<tr>';
                $tabla .='<td scope="col" class="fw-bold fs-6">CORREO:</td>';
                $tabla .='<td scope="col">
                            <div class="input-group mb-3">
                                <input required type="email" name="txt_user" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                            </div>
                        </td>';
            $tabla .='</tr>';

            $tabla .='<tr>';
                $tabla .='<td scope="col" class="fw-bold fs-6">CONTRASEÑA:</td>';
                $tabla .='<td scope="col">
                            <div class="input-group mb-3">
                                <input required type="password" name="txt_pass" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                            </div>
                        </td>';
            $tabla .='</tr>';

            $tabla .='<tfoot class="border-0 border border-white">';
                $tabla .='<tr class="">';
                    $tabla .='<td colspan="1" class="text-start">
                                <button type="submit" name="btn_consultar" class="btn btn-primary rounded">CONSULTAR</button>
                            </td>';
                $tabla .='</tr>';
            $tabla .='</tfoot>';
        $tabla .='</tbody>';
        $tabla .='</table>';

        if (!$hidden=="") {
            $hidden = "";
                #TABLA FILTRO (FECHA)
                $tabla .= '<table class="table table-bordered" '.$hidden.'>';
                        
                    $tabla .='<tr>';
                        $tabla .='<th colspan="2" class="text-center fs-5 table-secondary">FILTROS <i class="bi bi-funnel-fill"></i></th>';
                    $tabla .='</tr>';
                    $tabla .='<tr>';
                        $tabla .='<td scope="col" class="fw-bold fs-6">DESDE (fecha):</td>';
                        $tabla .='<td scope="col">
                                        <div class="input-group mb-3">
                                        <input type="date" class="form-control" name="fecha" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                        </div>
                                    </td>';
                    $tabla .='</tr>';

                    $tabla .='<tfoot class="border-0 border border-white">';
                        $tabla .='<tr class="">';
                            $tabla .='<td colspan="1" class="text-start">
                                        <button type="submit" name="btn_filtrar" class="btn btn-primary rounded">FILTRAR</button>
                                    </td>';
                            $tabla .='<td colspan="1" class="text-end">
                                        <button type="submit" name="btn_cerrar_sesion" class="btn btn-danger rounded">CERRAR SESSION</button>
                                    </td>';
                        $tabla .='</tr>';
            $tabla .='</tfoot>';
                    $tabla .='</tbody>';
                
                $tabla .='</table>';
        }
                
        return $tabla;
    }
    
    function extract_attachments($message_number) {
   
        $attachments = array();
        $structure = imap_fetchstructure($this->conexion, $message_number);
       
        if(isset($structure->parts) && count($structure->parts)) {
       
            for($i = 0; $i < count($structure->parts); $i++) {
       
               
                if($structure->parts[$i]->ifdparameters) {
                    foreach($structure->parts[$i]->dparameters as $object) {
                        if(strtolower($object->attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                        if(strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }
               
                
               
                if($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($this->conexion, $message_number, $i+1);
                    if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        $attachments[$i]['filenametemporal'] = $file_name_temporal=$attachments[$i]['filename'];
                        file_put_contents(__DIR__.'/../archivosMail/'.$file_name_temporal, $attachments[$i]['attachment']);
                       
                    }
                    elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
               
            }
           
        }
       
        return !empty($attachments) ? array_column($attachments, 'filenametemporal') : false;
       
    }
}
