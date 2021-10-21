<?php
	include('config/bd_conexao.php');

	$erros = array('nomeShow' => '', 'localidade' => '', 'descricao' => '', 'capacidade' => '');
	$nomeShow = $localidade = $descricao = $capacidade = '';

	if (isset($_POST['cadastrar'])) {

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

		//Incluir no banco e ir para a página de cadastro de evento
		if (array_filter($erros)) {
			//echo 'Erro no formulário';
		} else {
			//Limpando de códigos suspeitos
			$nomeShow = mysqli_real_escape_string($conn, $_POST['nomeShow']);
			$localidade = mysqli_real_escape_string($conn, $_POST['localidade']);
			$descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
			$capacidade = mysqli_real_escape_string($conn, $_POST['capacidade']);

			//Criando a query
			$sql = "INSERT INTO tb_show(nomeShow, localidade, descricao, capacidade)
			VALUES ('$nomeShow', '$localidade', '$descricao', '$capacidade')";

			//Salva no banco de dados
			if (mysqli_query($conn, $sql)) {
				//sucesso
				header('Location: index.php');
			} else {
				echo 'query error: ' . mysqli_error($conn);
			}
		}
	}

?>

<!DOCTYPE html>
<html>
	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">CADASTRAR UM SHOW</h4>
		<form class="white" action="novoShow.php" method="POST">
			<label>Nome do Show</label>
			<input type="text" name="nomeShow">
            <div class="red-text"><?php echo $erros['nomeShow'].'</br>'; ?></div>			
			<label>Local</label>
			<input type="text" name="localidade">	
            <div class="red-text"><?php echo $erros['localidade'].'</br>'; ?></div>		
			<label>Descrição</label>
			<input type="text" name="descricao">
			<div class="red-text"><?php echo $erros['descricao'].'</br>'; ?></div>	
			<label>Capacidade</label>
			<input type="number" min="0" name="capacidade" step="50">				
			<div class="red-text"><?php echo $erros['capacidade'].'</br>'; ?></div>
            <div class="center" style="margin-top: 10px;">
				<input type="submit" name="cadastrar" value="Cadastrar" class="btn brand z-depth-0 grey darken-4">
			</div>
		</form>
	</section>

	<?php include('templates/footer.php'); ?>
</html>