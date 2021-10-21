<?php
include('config/bd_conexao.php');

//query para buscar
$sql = 'SELECT s.nomeShow, s.localidade, s.id_show, e.dt_evento, e.id_evento 
FROM tb_show s
INNER JOIN tb_evento e ON (s.id_show = e.id_show)
ORDER BY e.dt_evento';

//resultado como um conjunto de linhas
$result = mysqli_query($conn, $sql);

//busca a query
$eventos = mysqli_fetch_all($result, MYSQLI_ASSOC);

//limpa a memória de $result
mysqli_free_result($result);

//fecha a conexão
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php include('templates/header.php'); ?>

<div>
    <img src="./assets/spetaculo.jpg" style="width:100%">
</div>
<div class="container">
    <div class="row">
        <?php foreach ($eventos as $evento) { ?>
            <div class="col s12 l6">
                <div class="card z-depth-0 grey lighten-3">
                    <div class="card-content center">
                        <span class="card-title"><b><?php echo htmlspecialchars($evento['nomeShow']); ?></b></span>
                        <p><?php echo htmlspecialchars($evento['localidade']); ?></p>
                        <p><?php echo htmlspecialchars(date("d/m/Y", strtotime($evento['dt_evento']))); ?></p>
                    </div>
                    <div class="card-action center">
                    <a class="black-text" href="alterarShow.php?id_show=<?php echo $evento['id_show'] ?>">Editar Show</a>
                    <a class="black-text" href="alterarEvento.php?id_evento=<?php echo $evento['id_evento'] ?>">Editar Evento</a>
                    <a class="black-text" href="compra.php?id_evento=<?php echo $evento['id_evento'] ?>">Comprar</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include('templates/footer.php'); ?>

</html>