<?php

function CallAPI($url,$method,$service,$data , $bool_file = false){

    if( $bool_file ){
        $file_name = tempnam(sys_get_temp_dir(), "download_file");

        $file_d = fopen($file_name, "w");
    }
    $url_servicio = $url.$service;
    if( $method === "GET" and isset($data)) {
        if(is_array($data)) 
            $url_servicio = $api_url.$service."?".http_build_query($data);
        else
            $url_servicio = $api_url.$service."?".$data;
    }
    $curl = curl_init($url_servicio);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $hdr_options=['Content-Type: application/json'];
    curl_setopt($curl, CURLOPT_HTTPHEADER, $hdr_options);

    $datos = str_replace("\":null" , "\": \"\"",json_encode($data));

    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POSTFIELDS, $datos );
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_POSTFIELDS, $datos);
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_POSTFIELDS, $datos );
            break;
    }

    if( $bool_file ){
        curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt ($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_FILE, $file_d);        
        $response = curl_exec($curl);
        fclose($file_d);
        $res_data=$file_name;
        if( $response === false){
            $res_data=null;
        }
    }else{
        $response = curl_exec($curl);
        $res_data = json_decode(str_replace("\": null" , "\": \"\"",$response),true);
    }
    /* Check for 404 (file not found). */
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // Check the HTTP Status code
    file_put_contents("php://stderr", "dao_server_rest::CallApi:".$url_servicio."\n");
    file_put_contents("php://stderr", "dao_server_rest::method:".$method."\n");
    file_put_contents("php://stderr", "dao_server_rest::data:".$datos."\n");
    file_put_contents("php://stderr", "dao_server_rest::httpCode:".$httpCode."\n");
    file_put_contents("php://stderr", "dao_server_rest::response raw:".$response."\n");
    if( $bool_file ){
        file_put_contents("php://stderr", "self::response:".$res_data."\n");        
    }
    else 
        file_put_contents("php://stderr", "self::response:".json_encode($res_data)."\n");
    curl_close($curl);
    return array("httpCode"=>$httpCode , "response"=>$res_data);
  }
?>