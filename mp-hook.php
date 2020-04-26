 <?php
require __DIR__ .  '/vendor/autoload.php';
include_once(__DIR__ ."/access_token.php");
include_once(__DIR__ ."/call_api.php");

//
// TODO: Debería tomar la novedad y retornar rápidamente para evitar timeout.
//
$parametros=null;
switch($_SERVER['REQUEST_METHOD'])
{
    case 'GET': $parametros = $_GET; break;
    case 'POST': $parametros = $_POST; break;
}

//
// Procesar las novedades de tipo Payment
//
if(isset($parametros["type"])) {
    switch( $parametros["type"] ) {
        case "payment":
            //
            // NO DEBERÍA REALIZAR UN LLAMADO, DEBERÍA ENCOLAR LA NOVEDAD Y RETORNAR RÁPIDAMETE CON UN HTML STATUS 200.
            //
            $rta = CallAPI(MP_API_URL,"GET","/v1/payments/".$parametros["data"]["id"]."?access_token=".MP_ACCESS_TOKEN, null);
            if( !($rta["httpCode"] >= 200 && $rta["httpCode"]<=201) ){
                file_put_contents("php://stderr", "Hook Payment Error \n");
                echo "Hook Payment Error ";

                file_put_contents("php://stderr", "payment rta:".$rta["httpCode"]."\n");
                echo "payment rta:".$rta["httpCode"];

                file_put_contents("php://stderr", "payment:".json_encode($rta["response"]) ."\n");
                echo "payment:".json_encode($rta["response"]);
            }else{
                echo "Pago Procesado";
                echo "payment:".json_encode($rta["response"]);
                file_put_contents("php://stderr", "payment:".json_encode($rta["response"])."\n");             
            }
            break;
    }
}
//
// Retornar 200
//
http_response_code(200);
?>