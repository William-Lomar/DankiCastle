<?php include 'config.php';

Painel::https();

$url = isset($_GET['url']) ? $_GET['url'] : 'base';

//Pegar valor atual da moeda
$data_array =  
    array(
          "56" => array(
            "0xCC42724C6683B7E57334c4E856f4c9965ED682bD","0x8257284054ed43bba8606526c3dce2b1ef918c97"
          )

);
$make_call = callAPI('POST', 'https://api.coinbrain.com/public/coin-info', json_encode($data_array));
$response = json_decode($make_call, true);

foreach ($response as $key => $value) {
    if ($value['address'] == "0x8257284054ed43bba8606526c3dce2b1ef918c97"){
        $valorDTtoDolar = $value['priceUsd'];
    }elseif ($value['address'] == "0xcc42724c6683b7e57334c4e856f4c9965ed682bd") {
        $valorMATICtoDolar = $value['priceUsd'];
    }
}

if (isset($_POST['batalhar'])){
    $hora = date("d/m/Y H:i"); 
    $proxHora = date('d/m/Y H:i', strtotime('+16 hours'));

    $dados = array(
        "hora"=>$hora,
        "proxHora"=>$proxHora,
        "status"=>"Batalha realizada com sucesso!"
    );

    Banco::insert($dados,'log');
    Painel::alerta("Batalhar inserida no banco com sucesso!");
    Painel::reload();
}

//Pegar valor do dolar
$get_data = callAPI('GET', 'https://economia.awesomeapi.com.br/json/last/USD-BRL', false);
$response = json_decode($get_data, true);
$dolarToReal = $response['USDBRL']['ask'];

$valorMATICtoReal = $valorMATICtoDolar*$dolarToReal;
$valorDTtoReal = $valorDTtoDolar*$dolarToReal;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DankiCastle - Log</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel='stylesheet' href="styles.css">
</head>
<body>
<?php 
if(file_exists('pg/'.$url.'.php')){
    include('pg/'.$url.'.php');
    }else{
        include('pg/base.php');
    }
?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>