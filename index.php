<?php

//phpinfo();

ini_set('max_execution_time', 3000);
header('content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$request = json_decode(file_get_contents("php://input"));

$key = "Sua Chave Aqui";

switch ($request->action) {
    case 'getAddress':
        $response = getAddress($request, $key);
        break;
    case 'getCoordinates':
        $response = getCoordinates($request, $key);
        break;
    default:
        # code...
        break;
}

function acronymToState($sigla) {
    switch (strtoupper($sigla)) {
        case 'AC': return 'Acre';
        case 'AL': return 'Alagoas';
        case 'AP': return 'Amapá';
        case 'AM': return 'Amazonas';
        case 'BA': return 'Bahia';
        case 'CE': return 'Ceará';
        case 'DF': return 'Distrito Federal';
        case 'ES': return 'Espírito Santo';
        case 'GO': return 'Goiás';
        case 'MA': return 'Maranhão';
        case 'MT': return 'Mato Grosso';
        case 'MS': return 'Mato Grosso do Sul';
        case 'MG': return 'Minas Gerais';
        case 'PA': return 'Pará';
        case 'PB': return 'Paraíba';
        case 'PR': return 'Paraná';
        case 'PE': return 'Pernambuco';
        case 'PI': return 'Piauí';
        case 'RJ': return 'Rio de Janeiro';
        case 'RN': return 'Rio Grande do Norte';
        case 'RS': return 'Rio Grande do Sul';
        case 'RO': return 'Rondônia';
        case 'RR': return 'Roraima';
        case 'SC': return 'Santa Catarina';
        case 'SP': return 'São Paulo';
        case 'SE': return 'Sergipe';
        case 'TO': return 'Tocantins';
        default: return 'Sigla inválida.';
    }
}

function callCurl($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Note: Em produção, considerar verificar o certificado SSL
    $response = curl_exec($curl);
    if (curl_errno($curl)) { // Verifica se ocorreu algum erro durante a chamada cURL
        throw new Exception(curl_error($curl));
    }
    curl_close($curl);
    return $response;
}


function getCoordinates($request, $key) {
    $addressString = "";

    if(isset($request->number) && $request->number != "") {
        $addressString .= $request->number;
    }
    if(isset($request->street) && $request->street != "") {
        $addressString .= "+" . $request->street;
    }
    if(isset($request->city) && $request->city != "") {
        $addressString .= "%20" . $request->city;
    }
    if(isset($request->state) && $request->state != "") {
        $addressString .= "%20" . acronymToState($request->state);
    }
    $addressString . "%20" . "Brasil";
    if(isset($request->postalCode) && $request->postalCode != "") {
        $addressString .= "%20" . $request->postalCode;
    }
    $addressString = str_replace(" ", "+", $addressString);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $addressString . "&key=" . $key;

    $mapsResponse = callCurl($url);
    $mapsResponse = json_decode($mapsResponse, 1);

    $coordinates = array();
    $status = false;
    if($mapsResponse['status'] != "INVALID_REQUEST" && count($mapsResponse['results']) > 0) {
        $coordinates = array(
            "lat" => $mapsResponse['results'][0]['geometry']['location']['lat'],
            "lng" => $mapsResponse['results'][0]['geometry']['location']['lng'],
        );
        $status = $mapsResponse['status'] == "OK";
    }

    return json_encode(["status" => $status, "data" => $coordinates, "message" => $status ? "Coordenadas retornadas com sucesso" : "Erro ao retornar coordenadas"]);
}

function getAddress($request, $key) {
    $formattedAddress = "";
    $status = false;
    if(isset($request->lat, $request->lng) && $request->lat != "" && $request->lng != "") {
        $coordinates = $request->lat . "%20" . $request->lng;
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $coordinates . "&key=" . $key;

        $mapsResponse = callCurl($url);
        $mapsResponse = json_decode($mapsResponse, 1);
        
        $formattedAddress = $mapsResponse['results'][0]['formatted_address']; 
        $status = $mapsResponse['status'] == "OK";
    }
        
    return json_encode(["status" => $status, "data" => ["address" => $formattedAddress], "message" => $status ?  "Endereço retornado com sucesso" : "Erro ao retornar endereço"]);

}

echo $response;
