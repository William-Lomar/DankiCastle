<?php 
//setar timezone
date_default_timezone_set('America/Sao_Paulo');

//incluir classes dinamicamente
$autoload = function($class){
    include('class/'.$class.'.php');
  };

  spl_autoload_register($autoload);

  define('INCLUDE_PATH', "https://seu_endereço/DankiCastle/");
  define('BASE_DIR',__DIR__);

  //Conectar com banco de dados
  define('HOST',"seu_host");
  define("USER", "seu_usuario");
  define("PASS", "sua_senha");
  define("DB", "sua_database");

  //functions
  //referência da função callAPI: https://weichie.com/blog/curl-api-calls-with-php/
  function callAPI($method, $url, $data){
    $curl = curl_init();
    switch ($method){
       case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
          break;
       default:
          if ($data)
             $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'APIKEY: 111111111111111111111',
       'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;
 }


?>