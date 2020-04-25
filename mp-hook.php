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

if($stream)
    $parametros=json_decode($stream,true);

if($parametros) {
    switch($parametros["type"]) {
        case "payment":
            $rta = CallAPI("https://api.mercadopago.com/v1/payments/".$parametros["id"],MP_ACCESS_TOKEN
            );
            if( !($rta["httpCode"] >= 200 && $rta["httpCode"]<=201) ){
                file_put_contents("php://stderr", "Hook Payment Error \n");
                echo "Hook Payment Error ";

                file_put_contents("php://stderr", "payment rta:".$rta["httpCode"]."\n");
                echo "payment rta:".$rta["httpCode"];

                file_put_contents("php://stderr", "payment:".json_encode($rta["response"]) ."\n");
                echo "payment:".json_encode($rta["response"]);
                http_response_code(300);        
                exit;
            }
            break;
    }
    http_response_code(201);
}
else{
  http_response_code(300);  
}
?>