<?php

include('config/bd_conexao.php');

$erros = array('nomeShow' => '', 'localidade' => '', 'descricao' => '', 'capacidade' => '');
$nomeShow = $localidade = $descricao = $capacidade = '';

//Verifica se o parâmetro id_evento foi enviado pelo get_browser
if (isset($_GET['id_show'])) {
    //Limpa os dados de sql injection
    $id_show = mysqli_real_escape_string($conn, $_GET['id_show']);

    //Monta a query
    $sql = "SELECT *
            FROM tb_show
            WHERE id_show = $id_show;";

    //Executa a query e guarda em $result
    $result = mysqli_query($conn, $sql);

    //Busca o resultado em forma de vetor
    $shows = mysqli_fetch_assoc($result);

    $nomeShow = $shows['nomeShow'];
    $localidade = $shows['localidade'];
    $descricao = $shows['descricao'];
    $capacidade = $shows['capacidade'];

    mysqli_free_result($result);

    mysqli_close($conn);
}

//Atualiza as alterações
if (isset($_POST['alterar'])) {
    //Verificar nome
		if (empty($_POST['nomeShow'])) {
			$erros['nomeShow'] = 'Por gentileza informar um nome.';
		} else {
			$nomeShow = $_POST['nomeShow'];
			if (!preg_match('/^[a-zA-ZáàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÔÕÖÚÇÑ\s]+$/', $nomeShow)) {
				$erros['nomeShow'] =  'O nome deve conter somente letras.';
				$nomeShow = '';
			}
		}
		//Verificar localidade
		if (empty($_POST['localidade'])) {
			$erros['localidade'] = 'Por gentileza informar uma localidade.';
		} else {
			$localidade = $_POST['localidade'];
			if (!preg_match('/^[a-zA-ZáàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÔÕÖÚÇÑ\s]+$/', $localidade)) {
				$erros['localidade'] =  'A localidade deve conter somente letras.';
				$localidade = '';
			}
		}
		//Verificar descricao
		if (empty($_POST['descricao'])) {
			$erros['descricao'] = 'Por gentileza informar uma descrição.';
		} else {
			$descricao = $_POST['descricao'];
		}
		//Verificar capacidade
		if (empty($_POST['capacidade'])) {
			$erros['capacidade'] = 'Por gentileza informar a capacidade máxima.';
		} else {
			$capacidade = $_POST['capacidade'];
			if (!preg_match('/^[0-9]*$/', $capacidade)) {
				$erros['capacidade'] = 'Informar somente números.';
				$capacidade = '';
			}
		}

    if (array_filter($erros)) {
        //echo 'Erro no formulário';
    } else {
        //Limpando o conteúdo de códigos suspeitos
        $id_show = mysqli_real_escape_string($conn, $_POST['id_show']);
        $nomeShow = mysqli_real_escape_string($conn, $_POST['nomeShow']);
        $localidade = mysqli_real_escape_string($conn, $_POST['localidade']);
        $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
        $capacidade = mysqli_real_escape_string($conn, $_POST['capacidade']);

        //Criando a query
        $sql = "UPDATE tb_show
                SET nomeShow = '$nomeShow', localidade = '$localidade', descricao = '$descricao', capacidade = '$capacidade' 
                WHERE id_show = $id_show;";

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
    $id_show = mysqli_real_escape_string($conn, $_POST['id_show']);

    //Montando a query
    $sql = "DELETE FROM tb_show WHERE id_show = $id_show;";

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
    <h4 class="center">ALTERAR O SHOW</h4>
    <form class="white" action="alterarShow.php" method="POST">
        <input type="hidden" name="id_show" value="<?php echo $id_show ?>">
        <label>Nome do Show</label>
        <input type="text" name="nomeShow" value="<?php echo $nomeShow ?>">
        <div class="red-text"><?php echo $erros['nomeShow'] . '</br>'; ?></div>
        <label>Local</label>
        <input type="text" name="localidade" value="<?php echo $localidade ?>">
        <div class="red-text"><?php echo $erros['localidade'] . '</br>'; ?></div>
        <label>Descrição</label>
        <input type="text" name="descricao" value="<?php echo $descricao ?>">
        <div class="red-text"><?php echo $erros['descricao'] . '</br>'; ?></div>
        <label>Capacidade</label>
        <input type="number" min="0" name="capacidade" value="<?php echo $capacidade ?>" step="50">
        <div class="red-text"><?php echo $erros['capacidade'] . '</br>'; ?></div>
        <div class="center" style="margin-top: 10px;">
            <input type="submit" name="deletar" value="Excluir" class="btn brand z-depth-0 grey darken-4">
            <input type="submit" name="alterar" value="Alterar" class="btn brand z-depth-0 grey darken-4">
        </div>
    </form>
</section>

<?php include('templates/footer.php'); ?>

</html>