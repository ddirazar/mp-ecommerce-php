<?php

switch($_SERVER['REQUEST_METHOD'])
{
    case 'GET': $parametros = $_GET; break;
    case 'POST': $parametros = $_POST; break;
}


print( json_encode($parametros));
if(isset($parametros["back_url"])) {
    echo $parametros["back_url"];
    header("Location: ".$parametros["back_url"]);      
}else
        header("Location: /index.php");
?>                                        
