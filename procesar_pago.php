 <?php


require __DIR__ .  '/vendor/autoload.php';
include_once(__DIR__ ."/access_token.php");


function CallAPI($api_url, $access_token  ){

    $url_servicio = $api_url."?access_token=$access_token";

    $curl = curl_init($url_servicio);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $hdr_options=['Content-Type: application/json'];
    curl_setopt($curl, CURLOPT_HTTPHEADER, $hdr_options);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

    $response = curl_exec($curl);
    $res_data = json_decode(str_replace("\": null" , "\": \"\"",$response),true);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return array("httpCode"=>$httpCode , "response"=>$res_data);
  }




$parametros=null;

$stream = file_get_contents('php://input');

echo $stream;

http_response_code(200);
?>