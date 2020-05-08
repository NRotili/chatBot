<?php

function processMessage($update) {
    //Comparo action con el valor
    if($update["queryResult"]["action"] == "input.Ticket"){
        //Obtengo parametros desde DialogFlow
        $params = $update["queryResult"]["parameters"];
        //Obtengo los valores de los parámetros
        $name = $params["name"];
		$email = $params["email"];
		$phone = $params["phone"];
		$topicId = $params["topicId"];
		$subject = $params["subject"];
		$message = $params["message"];
		
		//$ticket = getTicket($valorTicket);
        
        //Respuesta al contribuyente
        sendMessage(array(
            "fulfillmentText" => "El numero de ticket es ".$name."",
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

/*function getTicket($valorTicket){
	
	$config = array(
		'url'=>'http://TuSitioWeb/soporte/api/http.php/tickets.json',
		'key'=>'ELAPIQUEHAYASGENERADO'
	);
 
	$data = array(
		'name' => $_POST["nombre"], //NOMBRE
		'email' => $_POST["correo"], //CORREO
		'phone' => $_POST["telefono"], //TELEFONO
		'subject' => $_POST["resumen"], //TITULO
		'message' => $_POST["problema"], //MENSAJE
		'topicId' => '1', //TOPIC
		'Site' => $_POST["sitio"], //EJEMPLO DE CAMPO PERSONALIZADO
		'attachments' => array() //ARRELGO PARA ARCHIVOS
	);
 
	foreach ($_FILES as $file => $f){
		if (isset($f) && is_uploaded_file($f['tmp_name'])) {
			$nombre = $f["name"];
			$tipo = $f["type"];
			$ruta = $f['tmp_name'];
			$data['attachments'][] = array("$nombre" => 'data: '.$tipo.';base64,'.base64_encode(file_get_contents($ruta)));
		}
	}
 
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
 
	if ($code == 201)
		echo "Ticket abierto con n&uacute;mero ".$ticket_id;
	$ticket_id = (int) $result;
		die('Error al generar el ticket: '.$result);
}*/

 
function sendMessage($parameters) {
    echo json_encode($parameters);
}

//obtengo el post desde DialogFlow
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);
if(isset($update["queryResult"]["action"])) {
    processMessage($update);
}