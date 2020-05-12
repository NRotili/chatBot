<?php

function processMessage($update) {
	
    //Comparo action con el valor
    if($update["queryResult"]["action"] == "input.Ticket"){
        //Obtengo parametros desde DialogFlow
        $params = $update["queryResult"]["parameters"];
        //Obtengo los valores de los parámetros
        $nombre = $params["name"];
		$correo = $params["email"];
		$telefono = $params["phone"];
		//$tema = $params["subject"];
		$mensaje = $params["message"];
		
		$ticket = getTicket($nombre, $correo, $telefono);
		
		sendMessage(array(
			"fulfillmentText" => "El numero de ticket es ".$ticket."",
			"source"=> ""
		));
	}else{
        //Error
        sendMessage(array(
            "fulfillmentText"=> "Se ha producido un error",
            "source"=> ""
        ));
    }
}

function sendMessage($parameters) {
    echo json_encode($parameters);
}

//obtengo el post desde DialogFlow
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);
if(isset($update["queryResult"]["action"])) {
    processMessage($update);
}

/*function getTicket($nombre, $correo, $telefono, $mensaje){
	$numeroticket = '123456';
	return $ticket_numero = $numeroticket;
}*/

function getTicket($nombre, $correo, $telefono){
	
	$config = array(
		'url'=>'http://villaconstitucion.gob.ar/mesadeayuda/api/http.php/tickets.json',
		'key'=>'00F1545A75D822F84FB3DB8888E942A2'
	);
 
	$data = array(
		'name' => $nombre, //NOMBRE
		'email' => $correo, //CORREO
		'phone' => $telefono,
		'subject' => "Reclamos Contribuyentes", //TITULO
		'message' => $mensaje, //MENSAJE
		//'ip' => $_SERVER['REMOTE_ADDR'], //IP CLIENTE
		'topicId' => '17', //TOPIC

	);
 
 
	function_exists('curl_version') or die('CURL support required');
	function_exists('json_encode') or die('JSON support required');
 
	set_time_limit(30);
 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $config['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.8');
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 180);
	$result=curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	if ($code != 201)
		die('Error al generar el ticket: '.$result);
 
		$ticket_id = (int) $result;
 
		echo "Ticket abierto con n&uacute;mero ".$ticket_id;
 
	if ($code != 201)
		die('Error al generar el ticket: '.$result);
		//$valorTicket = (int) $result;
		return $valorTicket = (int) $result;

		//echo "Ticket abierto con n&uacute;mero ".$valorTicket;

}