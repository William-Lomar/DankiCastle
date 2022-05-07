<div class='titulo'>
    <h1>Danki Castle - Log</h1>
    <p> Cotação do DT: BRL -> <a href='dt'><?php echo "R$".number_format($valorDTtoReal, 2)?></a> / USD -><a href='dt'><?php echo "$".number_format($valorDTtoDolar, 2)?></a> </p>
    <p>Cotação do MATIC: BRL -> <a href='matic'><?php echo "R$".number_format($valorMATICtoReal, 2)?></a> / USD -><a href='matic'><?php echo "$".number_format($valorMATICtoDolar, 2)?></a>  </p>
</div>

<div class='container'>
    <table class="table">
    <thead>
        <tr>
        <th scope="col">#</th>
        <th scope="col">Hora</th>
        <th scope="col">Próxima Hora</th>
        <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
        // conexão com banco de dados
        $dados = Banco::newSelectAll('log');
        foreach ($dados as $key => $value) {
            ?>
        <tr>
        <th scope="row"><?php echo $value["id"]?></th>
        <td><?php echo $value["hora"]?></td>
        <td><?php echo $value["proxHora"]?></td>
        <td><?php echo $value["status"]?></td>
        </tr>
            <?php
        }
    ?>
    </tbody>
    </table>
    <form method='post'>
        <div class="right"> 
            <button name='batalhar' type="submit" class="btn btn-dark">Inserir Batalha</button>
        </div>
        <div class="clear"></div>
    </form>
</div>