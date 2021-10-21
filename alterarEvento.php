<?php

    include('config/bd_conexao.php');

    $erros = array('dt_evento' => '', 'horario' => '', 'preco' => '', 'id_show' => '');
    $dt_evento = $horario = $preco = '';

    //Verifica se o parâmetro id_evento foi enviado pelo get_browser
    if (isset($_GET['id_evento'])) {
        //Limpa os dados de sql injection
        $id_evento = mysqli_real_escape_string($conn, $_GET['id_evento']);

        //Monta a query
        $sql = "SELECT *
            FROM tb_evento e
            INNER JOIN tb_show s ON (e.id_show = s.id_show)
            WHERE id_evento = $id_evento;";

        //Executa a query e guarda em $result
        $result = mysqli_query($conn, $sql);

        //Busca o resultado em forma de vetor
        $eventos = mysqli_fetch_assoc($result);

        $nomeShow = $eventos['nomeShow'];
        $dt_evento = $eventos['dt_evento'];
        $horario = $eventos['horario'];
        $preco = $eventos['preco'];

        mysqli_free_result($result);

        mysqli_close($conn);
    }

    //Atualiza as alterações
    if (isset($_POST['alterar'])) {
        //Verificar data
        if (empty($_POST['dt_evento'])) {
            $erros['dt_evento'] = 'Por gentileza informar uma data.';
        } else {
            $dt_evento = $_POST['dt_evento'];
        }
        //Verificar horario
        if (empty($_POST['horario'])) {
            $erros['horario'] = 'Por gentileza informar um horário.';
        } else {
            $horario = $_POST['horario'];
        }
        //Verificar preco
        if (empty($_POST['preco'])) {
            $erros['preco'] = 'Por gentileza informar o preço do ingresso.';
        } else {
            $preco = $_POST['preco'];
            if (!preg_match('/^[0-9]*$/', $preco)) {
                $erros['preco'] = 'Informar somente números.';
                $preco = '';
            }
        }

        if (array_filter($erros)) {
            //echo 'Erro no formulário';
        } else {
            //Limpando o conteúdo de códigos suspeitos
            $id_evento = mysqli_real_escape_string($conn, $_POST['id_evento']);
            $dt_evento = mysqli_real_escape_string($conn, $_POST['dt_evento']);
            $horario = mysqli_real_escape_string($conn, $_POST['horario']);
            $preco = mysqli_real_escape_string($conn, $_POST['preco']);

            //Criando a query
            $sql = "UPDATE tb_evento
                SET dt_evento = STR_TO_DATE('$dt_evento','%Y-%m-%d'), horario = '$horario', preco = '$preco'
                WHERE id_evento = $id_evento;";

            //Salva no banco de dados
            if (mysqli_query($conn, $sql)) {
                //Sucesso
                header('Location: index.php');
            } else {
                echo 'Query error: ' . mysqli_error($conn);
            }
        }
    }

    //Exclui o evento do banco de dados
    if (isset($_POST['deletar'])) {
        //Limpando a query
        $id_evento = mysqli_real_escape_string($conn, $_POST['id_evento']);

        //Montando a query
        $sql = "DELETE FROM tb_evento WHERE id_evento = $id_evento;";

        //Removendo do banco de dados
        if (mysqli_query($conn, $sql)) {
            header('Location: index.php');
        } else {
            echo 'Query error: ' . mysqli_error($conn);
        }
    }

?>

<!DOCTYPE html>
<html>

<?php include('templates/header.php'); ?>

<section class="container grey-text">
    <h4 class="center">ALTERAR O EVENTO</h4>
    <form class="white" action="alterarEvento.php" method="POST">
        <input type="hidden" name="id_evento" value="<?php echo $id_evento ?>">
        <h5 class="center"><?php echo $nomeShow ?></h5>
        <label>Dia</label>
        <input type="date" name="dt_evento" value="<?php echo $dt_evento ?>">
        <div class="red-text"><?php echo $erros['dt_evento'] . '</br>'; ?></div>
        <label>Horário</label>
        <input type="time" name="horario" value="<?php echo $horario ?>">
        <div class="red-text"><?php echo $erros['horario'] . '</br>'; ?></div>
        <label>Preço do Ingresso (R$)</label>
        <input type="number" name="preco" step="0.01" value="<?php echo $preco ?>">
        <div class="red-text"><?php echo $erros['preco'] . '</br>'; ?></div>
        <div class="center" style="margin-top: 10px;">
            <input type="submit" name="deletar" value="Excluir" class="btn brand z-depth-0 grey darken-4">
            <input type="submit" name="alterar" value="Alterar" class="btn brand z-depth-0 grey darken-4">
        </div>
    </form>
</section>

<?php include('templates/footer.php'); ?>

</html>