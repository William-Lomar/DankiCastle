<?php
  class Painel{
    public static function https(){
      if ($_SERVER['HTTPS'] != "on"){
        self::redirecionar(INCLUDE_PATH.$_GET['url']);
      }
    }

    public static function reload(){
      echo "<script>location.href='".INCLUDE_PATH."'</script>";
    }

    public static function numeroPHP($post){
      foreach ($post as $key => $value) {
        $dados[$key] = str_replace(',', '.', $value);
      }
      return $dados;
    }

    public static function convertMoney($valor){
      return number_format($valor,2,',','.');
    }

    public static function atualizarPG($newGet = null){
      if (count($_GET) > 1) {
        $quantidade = count($_GET);
        $get = '';

        foreach ($_GET as $key => $value) {
          if ($key == 'url') {
            continue;
          }
          $get .= $key.'='.$value.'&';
        }

        if (is_null($newGet)) {
          echo "<script>location.href='".INCLUDE_PATH.$_GET['url'].'?'.$get."'</script>";
          die();
        }else{
          echo "<script>location.href='".INCLUDE_PATH.$_GET['url'].'?'.$get.$newGet."'</script>";
          die();
        }
      }else{
        if (is_null($newGet)) {
          echo "<script>location.href='".INCLUDE_PATH.@$_GET['url']."'</script>";
          die();
        }else{
          echo "<script>location.href='".INCLUDE_PATH.@$_GET['url'].'&'.$newGet."'</script>";
          die();
        }
      }
    }

    public static function pesquisa($busca,$tabela){
      $sql = MySql::conectar()->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".DB."' AND TABLE_NAME = '$tabela'");
      $sql->execute();
      $colunas = $sql->fetchAll();
      $query = "SELECT * FROM `$tabela` WHERE ";


      foreach ($colunas as $key => $value) {
        # code...
        if ($value[0] == 'id') {
          # code...
          continue;
        }

        if (count($colunas)-1 == $key) {
          # code...
          $query .= "$value[0] LIKE '%$busca%'";
        }else{
          $query .= "$value[0] LIKE '%$busca%' OR ";
        }
      }

      $sql = MySql::conectar()->prepare($query);
      $sql->execute();
      return $sql->fetchAll();
    }

    public static function logado(){
      return isset($_SESSION['login']) ? true : false;
    }

    public static function alerta($msg){
      ?>
        <script type="text/javascript">
          alert('<?php echo $msg ?>');
        </script>
      <?php
    }

    public static function imagemValida($img){
      if ($img['type'] == 'image/jpg' || 
          $img['type'] == 'image/jpeg' ||
          $img['type'] == 'image/png') {

        $tamanho = intval($img['size']/1024);//intval -> deixa número redondo
      // /1024 -> converte de bytes para KB
        if ($tamanho < 300) {
          # code... tamanho maximo de 300KB
          return true;
        }else{
          return false;
        }
        # code...
        
      }else{
        return false;
      }
    }

    public static function uploadFile($file){
      $formatoArquivo = explode('.', $file['name']);
      $imagemNome = uniqid().'.'.$formatoArquivo[count($formatoArquivo) - 1];
      if (move_uploaded_file($file['tmp_name'],BASE_DIR.'/img/'.$imagemNome)) {
         # code...
        return $imagemNome;
       }else{
        return false;
       }
    }

    public static function deleteFile($file){
      @unlink('img/'.$file);
    }

    public static function insert($post,$nomeTabela){
      $tag = true;
      
      $query = "INSERT INTO `$nomeTabela` VALUES (null";
      foreach ($post as $key => $value) {
        # code...
        if ($key == 'enviar') {
         continue;
        }
        /*if ($value == '') {
          # code...
          $tag = false;
          break;
        }*/
        $query .= ",?";
        $parametros[] = $value;
      }

      $query .= ")";

      
      if ($tag == true) {
        # code...
        $sql = MySql::conectar()->prepare($query);
        $sql->execute($parametros);
      }
      return $tag;
    }

    public static function selectFilter($tabela,$local,$tipoEmpresa){

      if ($tipoEmpresa == 'geral' && $local == 'total') {
            # code...

              $dados = self::selectAll($tabela);
              return $dados;
          }elseif ($tipoEmpresa == 'geral' && $local == 'almoxarifado') {
            # code...
            $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE local <> 'bancada'");
            $sql->execute(); 
          }elseif ($tipoEmpresa == 'geral' && $local == 'bancada') {
            # code...
            $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE local = 'bancada'");
            $sql->execute();    
          }elseif (($tipoEmpresa == 'iema' || $tipoEmpresa == 'ecosoft') && $local == 'total') {
            # code...
            $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE empresa = ?");
            $sql->execute(array($tipoEmpresa));
          }elseif (($tipoEmpresa == 'iema' || $tipoEmpresa == 'ecosoft') && $local == 'almoxarifado') {
            # code...
            $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE empresa = ? AND local <> 'bancada'");
            $sql->execute(array($tipoEmpresa));
          }elseif (($tipoEmpresa == 'iema' || $tipoEmpresa == 'ecosoft') && $local == 'bancada') {
            # code...
            $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE empresa = ? AND local = 'bancada'");
            $sql->execute(array($tipoEmpresa));
          }elseif($tipoEmpresa == 'clientes' && $local == 'total'){
            $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE empresa <> 'IEMA' AND empresa <> 'Ecosoft'");
            $sql->execute(); 
          }elseif($tipoEmpresa == 'clientes' && $local == 'almoxarifado'){
            $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE empresa <> 'IEMA' AND empresa <> 'Ecosoft' AND local <> 'bancada'");
            $sql->execute(); 
          }elseif($tipoEmpresa == 'clientes' && $local == 'bancada'){
            $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE empresa <> 'IEMA' AND empresa <> 'Ecosoft' AND local = 'bancada'");
            $sql->execute(); 
          }

      return $sql->fetchAll();
    }

     
    public static function selectAll($tabela,$start = null,$end = null){
      if ($start == null && $end == null) {
        # code...

        $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela`");
      }else{
        
        $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` LIMIT $start,$end");
      }
      $sql->execute();

      return $sql->fetchAll();
    }

    public static function deletar($tabela,$id=false){
      if ($id == false) {
        # code...
        $sql = MySql::conectar()->prepare("DELETE FROM `$tabela`");
      }else{
        $sql = MySql::conectar()->prepare("DELETE FROM `$tabela` WHERE id = $id");
      }
      if ($sql->execute()) {
        # code...
        return true;
      }else{
        return false;
      }
    }

    public static function redirecionar($url){
      echo "<script>location.href='".$url."'</script>";
      die();
    }

    public static function selecionarID($tabela,$id){
      
      $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE id = ?");
      $sql->execute(array($id));
      $dados = $sql->fetchAll();
      return $dados;
    }

    public static function update($arr,$single = false){
      $certo = true;
      $first = false;
      $nome_tabela = $arr['nome_tabela'];

      $query = "UPDATE `$nome_tabela` SET ";
      foreach ($arr as $key => $value) {
        $nome = $key;
        $valor = $value;
        if($nome == 'acao' || $nome == 'nome_tabela' || $nome == 'id' || $nome == 'atualizar')
          continue;
        /*if($value == ''){
          $certo = false;
          break;
        }*/
        
        if($first == false){
          $first = true;
          $query.="$nome=?";
        }
        else{
          $query.=",$nome=?";
        }

        $parametros[] = $value;
      }

      if($certo == true){
        if($single == false){
          $parametros[] = $arr['id'];
          $sql = MySql::conectar()->prepare($query.' WHERE id=?');
          $sql->execute($parametros);
        }else{
          $sql = MySql::conectar()->prepare($query);
          $sql->execute($parametros);
        }
      }
      return $certo;
    }

    public static function validaEntrada($dataEntrada,$situacao){
      $style = '';
      $dataEntrada = date_create_from_format('d/m/Y',$dataEntrada);
      $dataHoje = date("Y-m-d");
      

      $dataEntrada = $dataEntrada->format('Y-m-d');

      $intervaloEntrada = (strtotime($dataHoje)-strtotime($dataEntrada))/86400; // dividido por 86400 transforma valor em dias
   
      if ($intervaloEntrada > 15 && $situacao == 'Ag Avaliação' ) {
        # code...
        $style = "style='background: #F2DEDE;'";
      }

      echo $style;
    }

    public static function validaNF($dataNF){
      $style = '';
      $dataNF = date_create_from_format('d/m/Y',$dataNF);
      $dataHoje = date("Y-m-d");

      $dataNF = $dataNF->format('Y-m-d');

      $intervaloNF = (strtotime($dataHoje)-strtotime($dataNF))/86400;
      
      if ($intervaloNF > 150) {
        # code...
        $style = "style='background: #F2DEDE;'";
      }
      echo $style;
    }

    public static function validaPadrao($dataPadrao){
      $style = '';
      
      $dataPadrao = date_create_from_format("d/m/Y",$dataPadrao);
      
      $dataPadrao = $dataPadrao->format('Y-m-d');
      $dataHoje = date("Y-m-d");

      $intervalo = (strtotime($dataHoje)-strtotime($dataPadrao))/86400;

      
      if ($intervalo > 365) {
        # code...
        $style = "style='background: #F2DEDE;'";
      }

      echo $style;
    }

    public static function validaPadraoBool($dataPadrao){
      $validacao = true;
      
      $dataPadrao = date_create_from_format("d/m/Y",$dataPadrao);
      
      $dataPadrao = $dataPadrao->format('Y-m-d');
      $dataHoje = date("Y-m-d");

      $intervalo = (strtotime($dataHoje)-strtotime($dataPadrao))/86400;

      
      if ($intervalo > 365) {
        # code...
        $validacao = false;
      }

      return $validacao;
    }
}

?>
