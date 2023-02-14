<?php

namespace App\Controllers;


use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use DateTime;


class Usuarioscontroller extends Controller
{
    // all users
    public function index(){
        $model = new UsuarioModel();
        $data['usuarios'] = $model->asArray()->orderBy('id', 'DESC')->findAll();

        //echo $data['usuarios'][0];
        var_dump($data);
        //return $this->respond($data);
    }

    public function data()
    {
        /*$file = fopen('/var/www/html/dolmen-data-influ.csv', 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            //$line is an array of the csv elements
            var_dump($line);
        }
        fclose($file);*/
        /* Map Rows and Loop Through Them */
        //$rows   = array_map('str_getcsv', file('/var/www/html/dolmen-data-influ.csv'));
        $rows = array_map(function($row) {     return str_getcsv($row, ';'); }, file('/var/www/html/ectel-data-influ.csv'));
        $header = array_shift($rows);
        $csv    = array();
        $results = array();

        foreach($rows as $clave => $row){
            $csv[] = array_combine($header, $row);
            $actual = $csv[$clave]["﻿actor"];
            $influ = $csv[$clave]["influencer_type"];
            //echo $influ;
            if($influ=="1"){
                $results[$actual]["fitness"]+=$csv[$clave]["weight"];
                //echo "1";
            }
            if($influ=="2"){
                $results[$actual]["travel"]+=$csv[$clave]["weight"];
               // echo "2";
            }
            if($influ=="3"){
                $results[$actual]["beauty"]+=$csv[$clave]["weight"];
            }
            if($influ=="4"){
                $results[$actual]["neutral"]+=$csv[$clave]["weight"];
            }

        }


        $save =array();
        $conta =0;
        foreach($results as $clave => $row){
            $save[$conta]["actor"]=$clave;
            $save[$conta]["fitness"]=$row["fitness"]==null?0:$row["fitness"];
            $save[$conta]["travel"]=$row["travel"]==null?0:$row["travel"];
            $save[$conta]["beauty"]=$row["beauty"]==null?0:$row["beauty"];
            $save[$conta]["neutral"]=$row["neutral"]==null?0:$row["neutral"];

            $conta++;
        }
        //print_r($save);

        $has_header = false;

        foreach ($save as $c) {

            $fp = fopen('/var/www/html/resultados.csv', 'a');

            if (!$has_header) {
                fputcsv($fp, array_keys($c));
                $has_header = true;
            }

            fputcsv($fp, $c);
            fclose($fp);

        }
    }

    public function sendEmail()
    {
        // Email configuration
        //$something = "aaaaaaaaaa".$request->getVar('proyecto');
        //echo $something;

        $message = "Cotización del apartamento en ".$this->request->getVar('proyecto')." ".$this->request->getVar('torre')." ".$this->request->getVar('tipo')." ".$this->request->getVar('piso')." ".$this->request->getVar('area')." con un valor de ".$this->request->getVar('valor')." la cuota inicial es de $".$this->request->getVar('inicial')." a ".$this->request->getVar('meses')." meses las cuotas son de ".$this->request->getVar('cuota');
        $email = \Config\Services::email();
        $email->setFrom('contacto@constructoravalderrama.com.co', 'Constructora Valderrama');
        $email->setTo($this->request->getVar('email'));
        //$email->setTo('mario.mojica0@gmail.com');

        $email->setSubject('Cotización '.$this->request->getVar('proyecto'));
        $email->setMessage($message);
        $email->send();

        //$filename = '/img/yourPhoto.jpg'; //you can use the App patch
        //$email->attach($filename);

        //
        //$email->printDebugger(['headers']);
        $something = "".$this->request->getVar('email').' '.$message;
        echo json_encode($something);
    }

    public function BorrarDatosPropietario($email){
        //$this->db->distinct();

        //echo ("UPDATE `usuarios` SET `Borrar` = 'SI' WHERE `email` = "."'".$email."'");
        $db = \Config\Database::connect();
        $query = $db->query("UPDATE `usuarios` SET `Borrar` = 'SI' WHERE `email` = "."'".$email."'"); // Produces: SELECT DISTINCT * FROM table
        //echo("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')");

        //$query2 = $db->query("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')"); // Produces: SELECT DISTINCT * FROM table
        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);

    }

    public function BorrarDatosVisitante($email){
        //$this->db->distinct();

        //echo ("UPDATE `usuarios` SET `Borrar` = 'SI' WHERE `email` = "."'".$email."'");
        $db = \Config\Database::connect();
        $query = $db->query("UPDATE `usuariosVisita` SET `Borrar` = 'SI' WHERE `email` = "."'".$email."'"); // Produces: SELECT DISTINCT * FROM table
        //echo("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')");

        //$query2 = $db->query("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')"); // Produces: SELECT DISTINCT * FROM table
        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);

    }


    public function getNoticias(){
        //$this->db->distinct();

        $db = \Config\Database::connect();
        $query = $db->query("SELECT * from noticias"); // Produces: SELECT DISTINCT * FROM table

        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);
    }

    public function getObra($idproyecto){
        //$this->db->distinct();

        $db = \Config\Database::connect();
        $query = $db->query("SELECT * from obra where `id_proyecto` =".$idproyecto); // Produces: SELECT DISTINCT * FROM table

        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);
    }

    public function getGaleria($idproyecto){
        //$this->db->distinct();

        $db = \Config\Database::connect();
        $query = $db->query("SELECT `imagenes` from galeria where `proyecto` =".$db->escape($idproyecto)); // Produces: SELECT DISTINCT * FROM table

        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);
    }

    public function getBonus(){
        //$this->db->distinct();

        $db = \Config\Database::connect();
        $query = $db->query("SELECT * from bonus"); // Produces: SELECT DISTINCT * FROM table

        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);
    }

    public function geTorreByProyecto($proyecto){
        //$this->db->distinct();

        $db = \Config\Database::connect();
        $query = $db->query("SELECT distinct idProyecto, torre as label, idProyecto as value, 'green' as color FROM cotizador where proyecto = ".$db->escape($proyecto)); // Produces: SELECT DISTINCT * FROM table
        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);

        //var_dump($array);
        foreach ($array as $key => $value){
            $array[$key]['id']=$key;
        }
        $rta = json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo $rta;
    }

    public function registrar($nombre,$cedula,$telefono,$email){
        //$this->db->distinct();

        $db = \Config\Database::connect();
        $query = $db->query("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')"); // Produces: SELECT DISTINCT * FROM table
        //echo("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')");

        //$query2 = $db->query("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')"); // Produces: SELECT DISTINCT * FROM table


        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);

    }

    public function registrarVisita($nombre,$telefono,$email){
        //$this->db->distinct();

        $db = \Config\Database::connect();
        $query = $db->query("INSERT INTO usuariosVisita (`nombre`,`telefono`,`email`) values ('".$nombre."','".$telefono."','".$email."')"); // Produces: SELECT DISTINCT * FROM table
        //echo("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')");

        //$query2 = $db->query("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')"); // Produces: SELECT DISTINCT * FROM table


        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);

    }

    public function getPass($cedula){
        //$this->db->distinct();

        $db = \Config\Database::connect();
        $query = $db->query("SELECT * from usuarios where `cedula` = ".$cedula); // Produces: SELECT DISTINCT * FROM table
        //echo("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')");

        //$query2 = $db->query("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')"); // Produces: SELECT DISTINCT * FROM table


        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);

    }


    public function checkPass($cedula,$pass){
        //$this->db->distinct();

        $db = \Config\Database::connect();
        $query = $db->query("SELECT * from usuarios where `cedula` = ".$cedula." and `clave`=".$pass); // Produces: SELECT DISTINCT * FROM table
        //echo("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')");
//echo "SELECT * from usuarios where `cedula` = ".$cedula." and `clave`=".$pass;
        //$query2 = $db->query("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')"); // Produces: SELECT DISTINCT * FROM table


        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);

    }

    public function savePass($cedula,$clave){
        //$this->db->distinct();

        $db = \Config\Database::connect();
        $query = $db->query("UPDATE usuarios set `clave`= ".$clave." WHERE `cedula`= ".$cedula); // Produces: SELECT DISTINCT * FROM table
        //echo ("UPDATE usuarios set `clave`= ".$clave." WHERE `cedula`= ".$cedula);
        //echo("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')");

        //$query2 = $db->query("INSERT INTO usuarios (`nombre`,`cedula`,`telefono`,`email`) values ('".$nombre."','".$cedula."','".$telefono."','".$email."')"); // Produces: SELECT DISTINCT * FROM table


        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);
        echo json_encode($results);

    }


//rrr
    public function geTiposByTorre($proyecto,$torre){
        //$this->db->distinct();

        $token = self::firstlogin();
        //$ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/Ventas/NumeroIdentificacion/804005319');
        $ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/Unidades/PorProyecto/'.$torre);
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $authorization));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $inmueb=json_decode($result,true);


        $arr = array();
        $arrT = array();
        $tiposs = "";
        for ($i=0;$i<sizeof($inmueb);$i++){

            if($inmueb[$i]["estado"]=="DISPONIBLE" && $inmueb[$i]["idTipoUnidad"]!=13 && (strpos($inmueb[$i]["nombre"], 'ELIMINADO') === false)){

                $tipo =$inmueb[$i]["tipoInmueble"]."";
                $stringTipo = preg_replace('/\s+/', '', $tipo);
                //echo $stringTipo;
                if(strpos($stringTipo,"A1") ){
                    $tiposs = $tiposs.','."A1";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"A2")){
                    $tiposs = $tiposs.','."A2";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"A3")){
                    $tiposs = $tiposs.','."A3";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"B1")){
                    $tiposs = $tiposs.','."B1";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"B2")){
                    $tiposs = $tiposs.','."B2";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"B3")){
                    $tiposs = $tiposs.','."B3";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"C1")){
                    $tiposs = $tiposs.','."C1";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"C2")){
                    $tiposs = $tiposs.','."C2";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"C3")){
                    $tiposs = $tiposs.','."C3";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"A")){
                    $tiposs = $tiposs.','."A";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"B")){
                    $tiposs = $tiposs.','."B";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"C")){
                    $tiposs = $tiposs.','."C";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"D")){
                    $tiposs = $tiposs.','."D";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"E") ){
                    $tiposs = $tiposs.','."E";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"F")){
                    $tiposs = $tiposs.','."F";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"G")){
                    $tiposs = $tiposs.','."G";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"H")){
                    $tiposs = $tiposs.','."H";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"I")){
                    $tiposs = $tiposs.','."I";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"J")){
                    $tiposs = $tiposs.','."J";
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

            }
        }
        //echo $tiposs;
        $permiExploded = explode(",",$tiposs);
        $newPermi = array();
        foreach ($permiExploded as $per){
            array_push($newPermi,"'".trim($per)."'");
        }
        $permi = implode(",",$newPermi);

        $db = \Config\Database::connect();
        //$query = $db->query("SELECT tipo as value, tipo as label, 'green' as color, imagen as imagen FROM cotizador where proyecto = ".$db->escape($proyecto)." AND idProyecto =".$db->escape($torre)); // Produces: SELECT DISTINCT * FROM table
        $query = $db->query("SELECT tipo as value, tipo as label, 'green' as color, imagen as imagen FROM cotizador where proyecto = ".$db->escape($proyecto)." AND idProyecto =".$db->escape($torre)." AND  tipoN IN ($permi)"); // Produces: SELECT DISTINCT * FROM table
        //echo "SELECT tipo as value, tipo as label, 'green' as color, imagen as imagen FROM cotizador where proyecto = ".$db->escape($proyecto)." AND idProyecto =".$db->escape($torre)." AND  tipoN IN ($permi)";

        $results = $query->getResult();
        $array = json_decode(json_encode($results),true);

        //var_dump($array);
        foreach ($array as $key => $value){
            $array[$key]['id']=$key;
            $array[$key]['label']="Tipo ".$array[$key]['label'];
        }
        $rta = json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo $rta;
    }

    public function getPisosDisponiblesByTorreProyecto($proyecto,$tipoUsuario){
        $token = self::firstlogin();
        //$ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/Ventas/NumeroIdentificacion/804005319');
        $ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/Unidades/PorProyecto/'.$proyecto);
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $authorization));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $inmueb=json_decode($result,true);


        $arr = array();
        $arrT = array();
        for ($i=0;$i<sizeof($inmueb);$i++){

            if($inmueb[$i]["estado"]=="DISPONIBLE" && $inmueb[$i]["idTipoUnidad"]!=13 && (strpos($inmueb[$i]["nombre"], 'ELIMINADO') === false)){

                $tipo =$inmueb[$i]["tipoInmueble"]."";
                $stringTipo = preg_replace('/\s+/', '', $tipo);
                if(strpos($stringTipo,"A1") && $tipoUsuario=="A1"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"A2") && $tipoUsuario=="A2"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"A3") && $tipoUsuario=="A3"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"B1") && $tipoUsuario=="B1"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"B2") && $tipoUsuario=="B2"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"B3") && $tipoUsuario=="B3"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"C1") && $tipoUsuario=="C1"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"C2") && $tipoUsuario=="C2"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"C3") && $tipoUsuario=="C3"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"A") && $tipoUsuario=="A"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"B") && $tipoUsuario=="B"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"C") && $tipoUsuario=="C"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"D") && $tipoUsuario=="D"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"E") && $tipoUsuario=="E"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"F") && $tipoUsuario=="F"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"G") && $tipoUsuario=="G"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"H") && $tipoUsuario=="H"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"I") && $tipoUsuario=="I"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }

                if(strpos($stringTipo,"J") && $tipoUsuario=="J"){
                    $arrT["id"]= $i;
                    $arrT["color"]= "green";
                    $arrT["value"]= $inmueb[$i]["numeroPiso"];
                    $arrT["label"]= "Piso ".$inmueb[$i]["numeroPiso"]." ".$inmueb[$i]["nombre"];
                    $arrT["tipoborrar"]= $inmueb[$i]["tipoInmueble"];
                    array_push($arr, $arrT);
                }
                //echo $string;

                //$tipoT=explode($string,"-");
                //var_dump($tipoT);
                /*if(strpos($stringTipo,"A")){

                    $arrT["nombre"]= $inmueb[$i]["nombre"];
                    $arrT["tipo"]= $inmueb[$i]["tipoInmueble"];
                    $arrT["valor"]= $inmueb[$i]["valor"];
                    $arrT["areaConstruida"]= $inmueb[$i]["areaConstruida"];
                    $arrT["numeroPiso"]= $inmueb[$i]["numeroPiso"];
                    array_push($arr, $arrT);
                }*/

            }
        }

        $post_data = json_encode($arr);
        echo $post_data;

        //echo $data;
    }

    public function firstlogin(){
        $ch = curl_init( 'https://sincoerp.com:444/SincoConsVal/V3/API/Auth/Usuario' );
        $data='{
            "NomUsuario": "APICBR",
        "ClaveUsuario": "TDPsfmSJaUKuQEwIGnBNnl2ZWZoQNuEpkzpzjG42z8HCvkxGDHkhvtJvME9WQrmPM3Nm0Ejdg2cg7g637aHSQPz4vK3CSqWIEs+Y6L7b6hfd3meR6PtxKAtW3dNZ/ySjw7Y84oc2ddVvy7/tL0PM2BPONHz20e+isqn7Oy7Gc30Db2f7fXykX7jd/14CwpZYGWSUIdkaJr4zd22TZ91XE07Xxbz6fWv57RDeSIot/6JYKsfU++3tVeUOTsSIxkHfgrUjZoj2GKa4GZYiUlNh/4AE9E0wSeTcvLFvgbbp7A1unodC2RfFJBYjYdr8w0n3RRRvFNYs5cd1wYr0elgelqD0B0NO+lppgl+KUTxzxFELrPleQ+ybS9vm9o+ftrICFitJSsXVnMSaSaQGvMwcD6Bbd72c9xtLWbtnyhiZQgHcptMii9LIcdOD09MK6wyxM7bP4tQTdYtb5qxM4fVClyDorxvs2osZessiSfO8RLCwW9vLl8cUbMcGWp1Iv+EXG47fF6Kmhk8TXkHYQQvZkXKwhwp38IZPZkKssNNUJNM=7"
}';
        $payload =  $data;
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $ftoken=json_decode($result);
        $firstToken=$ftoken->access_token;
        //$this->secondlogin($firstToken);
        $ch = curl_init( 'https://sincoerp.com:444/SincoConsVal/V3/API/Auth/Sesion/Iniciar/1/Empresa/1/Sucursal/0' );
        $authorization = "Authorization: Bearer ".$firstToken; // Prepare the authorisation token
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $authorization));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $ftoken=json_decode($result);
        $finalToken=$ftoken->token->access_token;
        //$this->secondlogin($firstToken);
        //echo "<pre>$finalToken</pre>";
        return $finalToken;

        //echo "<pre>$ftoken->access_token</pre>";
    }

    public function secondlogin($token){

    }

    public function getUserInmuebles(){

        $token = self::firstlogin();

        $ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/Ventas/NumeroIdentificacion/37551234');
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $authorization));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $inmueb=json_decode($result,true);
        $data = json_encode($inmueb["agrupacionesComprador"]);
        //$data = json_encode($inmueb);
        //var_dump($inmueb["agrupacionesComprador"]);
        //echo "<pre>$data->nombreCompletoComprador</pre>";
        //print_r($inmueb[0]->nombreCompletoComprador);
       // var_dump($data);
       // print_r($data->nombreCompletoComprador);
        print_r($data);
        //echo $data;
    }

    public function cotizar($idproyecto,$idunidad,$tiempo){

        $token = self::firstlogin();
        $postData ='{
            "idProyecto": '.$idproyecto.',
  "idsUnidades": [
                '.$idunidad.'
            ],
  "plazoFinanciacionEnAnios": '.$tiempo.'
}';


        $arr = array();
        $ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/SalaVentas/Cotizaciones/Simulacion');
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $authorization));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        curl_close($ch);
        $inmueb=json_decode($result,true);
        $data= json_encode($inmueb);
        //echo $inmueb["idProyecto"];
        $d = $inmueb["formaPago"]["cuotasIniciales"][3]["detallesCuotaInicial"][0]["valor"];
        $arr["cuota"]=ceil($d);
        $post_data = json_encode($arr);
        echo $post_data;
        //echo $data;


        //$data = json_encode($inmueb["agrupacionesComprador"]);
        //$data = json_encode($inmueb);
        //var_dump($inmueb["agrupacionesComprador"]);
        //echo "<pre>$data->nombreCompletoComprador</pre>";
        //print_r($inmueb[0]->nombreCompletoComprador);
        // var_dump($data);
        // print_r($data->nombreCompletoComprador);
        //print_r($inmueb);
        //echo $data;
    }

    //RR
    public function getDisponiblesCotizador($proyecto,$tipoU,$piso){
        $token = self::firstlogin();
        //$ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/Ventas/NumeroIdentificacion/804005319');
        $ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/Unidades/PorProyecto/'.$proyecto);
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $authorization));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $inmueb=json_decode($result,true);

        /*$db = \Config\Database::connect();
        $query = $db->query("SELECT imagen FROM cotizador where `idProyecto` = ".$db->escape($proyecto)." AND `tipo` =".$db->escape($tipoU)." "); // Produces: SELECT DISTINCT * FROM table
        $results = $query->getResult();
        //echo "SELECT imagen FROM cotizador where `idProyecto` = ".$db->escape($proyecto)." AND `tipo` =".$db->escape($tipoU)." ";
        $array = json_decode(json_encode($results),true);
        $imagen= $array[0]["imagen"];
        */
        $pn = explode("*",$piso);
        $ppn = $pn[2];



            $arr = array();
            $arrT = array();
            for ($i=0;$i<sizeof($inmueb);$i++){
                //echo $ppn.'  '.$inmueb[$i]["nombre"];

                if($inmueb[$i]["estado"]=="DISPONIBLE" && $inmueb[$i]["idTipoUnidad"]!=13){

                    $tipo =$inmueb[$i]["tipoInmueble"]."";
                    $string = preg_replace('/\s+/', '', $tipo);
                    //echo $string;

                    //$tipoT=explode($string,"-");
                    //var_dump($tipoT);
                    if(strpos($string,$tipoU)){

                        //if($inmueb[$i]["numeroPiso"]==$piso){
                        if($inmueb[$i]["nombre"]==$ppn){

                        //if($inmueb[$i]["numeroPiso"]==$piso){
                        $arrT["id"]= $inmueb[$i]["id"];
                        $arrT["nombre"]= $inmueb[$i]["nombre"];
                        $arrT["tipo"]= $inmueb[$i]["tipoInmueble"];
                        $arrT["valor"]= $inmueb[$i]["valor"];
                        $arrT["areaConstruida"]= $inmueb[$i]["areaConstruida"];
                        $arrT["numeroPiso"]= $inmueb[$i]["numeroPiso"];
                        //$arrT["imagen"]= $imagen;
                        array_push($arr, $arrT);
                        }
                    }





                }
            }

        $post_data = json_encode($arr);
        echo $post_data;

        //echo $data;
    }

    public function getUserPagos($cedula){
        $token = self::firstlogin();
        //$ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/Ventas/NumeroIdentificacion/804005319');
        $ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/Ventas/NumeroIdentificacion/'.$cedula);
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $authorization));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $inmueb=json_decode($result,true);
        $data = json_encode($inmueb["agrupacionesComprador"]);

        $currentDate = new DateTime();


        $hoytime =$currentDate->format('Y-m-d H:i:s');
        $saldo=0;

        $tam = sizeof($inmueb["agrupacionesComprador"]);
        if($tam ==1 ){
            $arrT = array();
            $arr = array();
            $saldo =$inmueb["agrupacionesComprador"][0]["unidadesAgrupacion"][0]["valor"];
            for ($j=0;$j<sizeof($inmueb["agrupacionesComprador"][0]["planPagosAgrupacion"]);$j++) {

                $fechaPactada  = $inmueb["agrupacionesComprador"][0]["planPagosAgrupacion"][$j]["fechaPactada"];
                $pactadatime =strtotime($fechaPactada);
                $hoy = strtotime($hoytime);
                $secs = $pactadatime - $hoy;// == <seconds between the two times>
                $days = $secs / 86400;
                ///echo $days."  ";
                if($days<30){
                    $arrT["key"] =$inmueb["agrupacionesComprador"][0]["planPagosAgrupacion"][$j]["detallePagos"][0]["idPlanPagoDetalle"];
                    $arrT["concepto"] = $inmueb["agrupacionesComprador"][0]["planPagosAgrupacion"][$j]["concepto"];
                    $arrT["valorPagado"] = $inmueb["agrupacionesComprador"][0]["planPagosAgrupacion"][$j]["valorPagado"];
                    $saldo = $saldo- $arrT["valorPagado"];
                    $arrT["saldo"]=$saldo;
                    $arrT["valor"]=$inmueb["agrupacionesComprador"][0]["unidadesAgrupacion"][0]["valor"];
                    $arrT["proyecto"]=$inmueb["agrupacionesComprador"][0]["nombreMacroProyecto"];
                    $arrT["fechaUltimoPago"]=$inmueb["agrupacionesComprador"][0]["planPagosAgrupacion"][$j]["fechaUltimoPago"];
                    $porcentaje=100-(($arrT["saldo"]*100)/$inmueb["agrupacionesComprador"][0]["unidadesAgrupacion"][0]["valor"]);
                    $arrT["porcentaje"]=$porcentaje."%";
                    //$arrT["porcentaje"]="20%";
                    array_push($arr, $arrT);

                    //echo "entro";
                }

                //$arr[$i]["Torre"]=$inmueb["agrupacionesComprador"][$i]["nombreProyectoAgrupacion"];
                //valor$arr[$i]["inmueble"]=$inmueb["agrupacionesComprador"][$i]["unidadesAgrupacion"][0]["nombre"];
                //$datapush = $inmueb["agrupacionesComprador"][$i]["nombreMacroProyecto"];
                //array_push($arr, $datapush);
            }

        }
    /*else{

        $arr = array();
        for ($i=0;$i<sizeof($inmueb["agrupacionesComprador"]);$i++){
            //$arr[$i]["valor"]=$inmueb["agrupacionesComprador"][$i]["unidadesAgrupacion"][0]["valor"];
            $saldo =$inmueb["agrupacionesComprador"][$i]["unidadesAgrupacion"][0]["valor"];
            for ($j=0;$j<sizeof($inmueb["agrupacionesComprador"][$i]["planPagosAgrupacion"]);$j++) {

                $fechaPactada  = $inmueb["agrupacionesComprador"][$i]["planPagosAgrupacion"][$j]["fechaPactada"];
                $pactadatime =strtotime($fechaPactada);
                $hoy = strtotime($hoytime);
                $secs = $pactadatime - $hoy;// == <seconds between the two times>
                $days = $secs / 86400;
                ///echo $days."  ";
                if($days<30){
                    $arr[$i]["pagos"][$j]["concepto"] = $inmueb["agrupacionesComprador"][$i]["planPagosAgrupacion"][$j]["concepto"];
                    $arr[$i]["pagos"][$j]["valorPagado"] = $inmueb["agrupacionesComprador"][$i]["planPagosAgrupacion"][$j]["valorPagado"];
                    $saldo = $saldo- $arr[$i]["pagos"][$j]["valorPagado"];
                    $arr[$i]["pagos"][$j]["saldo"]=$saldo;
                    $arr[$i]["pagos"][$j]["valor"]=$inmueb["agrupacionesComprador"][$i]["unidadesAgrupacion"][0]["valor"];


                }

            }
        }
    }*/
$arr2=array_reverse($arr);
        $post_data = json_encode($arr2);
        echo $post_data;
    }

    public function getUserData($cedula){

        $token = self::firstlogin();

        $ch = curl_init('https://sincoerp.com:444/SincoConsVal/V3/CBRClientes/Api/Ventas/NumeroIdentificacion/'.$cedula);
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $authorization));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $inmueb=json_decode($result,true);
        $data = json_encode($inmueb["agrupacionesComprador"]);

        $arr = array();
        for ($i=0;$i<sizeof($inmueb["agrupacionesComprador"]);$i++){
            $arr[$i]["proyecto"]=$inmueb["agrupacionesComprador"][$i]["nombreMacroProyecto"];
            $arr[$i]["imagen"]='https://lacajaguapa.com/valderramaapp/public/images/logos/'.$inmueb["agrupacionesComprador"][$i]["nombreMacroProyecto"].'logo.png';
            $arr[$i]["idproyecto"]=$inmueb["agrupacionesComprador"][$i]["idProyectoAgrupacion"];
            $arr[$i]["Torre"]=$inmueb["agrupacionesComprador"][$i]["nombreProyectoAgrupacion"];
            $arr[$i]["inmueble"]=$inmueb["agrupacionesComprador"][$i]["unidadesAgrupacion"][0]["nombre"];
            //$datapush = $inmueb["agrupacionesComprador"][$i]["nombreMacroProyecto"];
            //array_push($arr, $datapush);
        }

        $post_data = json_encode($arr);
        echo $post_data;
        //$data = json_encode($inmueb);
        //var_dump($inmueb["agrupacionesComprador"]);
        //echo "<pre>$data->nombreCompletoComprador</pre>";
        //print_r($inmueb[0]->nombreCompletoComprador);
        // var_dump($data);
        // print_r($data->nombreCompletoComprador);

        //print_r($data);
        //echo $data;


    }

    public function index2()
    {
        return view('welcome_message');
    }
    public function test()
    {
        echo "holaa";
    }

}
