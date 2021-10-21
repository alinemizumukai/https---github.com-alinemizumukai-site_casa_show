<?php
include('config/bd_conexao.php');

//Verifica se o parâmetro id_evento foi enviado pelo get_browser
if (isset($_GET['id_venda'])) {
    //Limpa os dados de sql injection
    $id_venda = mysqli_real_escape_string($conn, $_GET['id_venda']);

    //Monta a query
    $sql = "
        SELECT v.qtde_inteira, v.qtde_meia, e.preco, e.dt_evento, e.horario, s.nomeShow, s.localidade
        FROM tb_venda v
        LEFT JOIN tb_evento e ON (v.id_evento = e.id_evento )
        LEFT JOIN tb_show s ON (e.id_show = s.id_show)
        WHERE id_venda = $id_venda;";

    //Executa a query e guarda em $result
    $result = mysqli_query($conn, $sql);

    //Busca o resultado em forma de vetor
    $vendas = mysqli_fetch_assoc($result);

    $qtde_inteira = $vendas['qtde_inteira'];
    $qtde_meia = $vendas['qtde_meia'];
    $valor_total = ($qtde_inteira * $vendas['preco']) + (($qtde_meia * $vendas['preco']) / 2);
    $nomeShow = $vendas['nomeShow'];
    $localidade = $vendas['localidade'];
    $dt_evento = $vendas['dt_evento'];
    $horario = $vendas['horario'];

    mysqli_free_result($result);

    mysqli_close($conn);
}



?>

<!DOCTYPE html>
<html lang="pt-br">

<?php include('templates/header.php'); ?>
<div>
    <img src="./assets/spetaculo.jpg" style="width:100%">
</div>
<div class="container">
    <div class="card z-depth-0 grey lighten-3">
        <div class="card-content center">
            <span class="card-title"><b>Confirmação de Venda</b></span>
            <h5 class="center"><?php echo $nomeShow ?></h5>
            <h6 class="center"><?php echo $localidade ?></h6>
            <br>
            <p class="center"><b>Dia: </b><?php echo date("d/m/Y", strtotime($dt_evento));?></p>
            <p class="center"><b>Horário: </b><?php echo $horario?></p> 
            <br>
            <p>Você comprou:</p>
            <?php if ($qtde_inteira > 0) {?>
            <p><?php echo $qtde_inteira ?> ingresso(s) do tipo inteiro.</p>
            <?php } 
            if($qtde_meia > 0){?>
            <p><?php echo $qtde_meia ?> ingresso(s) do tipo meia entrada.</p>
            <?php } ?>
            <br>
            <p><b>Valor total: R$ <?php echo $valor_total ?></b></p>
        </div>
        <div class="card-action center">
            <a class="black-text" href=" index.php">Ok</a>
        </div>
    </div>

</div>

<?php include('templates/footer.php'); ?>

</html>