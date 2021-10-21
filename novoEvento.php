<?php
	include('config/bd_conexao.php');

	//query para buscar
	$sql = 'SELECT id_show, nomeShow 
	FROM tb_show
	ORDER BY nomeShow;';

	//resultado como um conjunto de linhas
	$result = mysqli_query($conn, $sql);

	//busca a query
	$shows = mysqli_fetch_all($result, MYSQLI_ASSOC);

	//limpa a memória de $result
	mysqli_free_result($result);

	$erros = array('dt_evento' => '', 'horario' => '', 'preco' => '', 'id_show' => '');
	$dt_evento = $horario = $preco = '';

	if (isset($_POST['incluir'])) {

		//Verificar show
		if (empty($_POST['id_show'])) {
			$erros['id_show'] = 'Por gentileza selecionar uma opção.';
		} else {
			$id_show = $_POST['id_show'];
		}
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

		//Incluir no banco e retornar à página inicial
		if (array_filter($erros)) {
			//echo 'Erro no formulário';
		} else {
			//Limpando de códigos suspeitos
			$id_show = mysqli_real_escape_string($conn, $_POST['id_show']);
			$dt_evento = mysqli_real_escape_string($conn, $_POST['dt_evento']);
			$horario = mysqli_real_escape_string($conn, $_POST['horario']);
			$preco = mysqli_real_escape_string($conn, $_POST['preco']);

			//Criando a query
			$sql = "INSERT INTO tb_evento(id_show, dt_evento, horario, preco)
				VALUES ('$id_show', STR_TO_DATE('$dt_evento','%Y-%m-%d'), '$horario', '$preco')";

			//Salva no banco de dados
			if (mysqli_query($conn, $sql)) {
				//sucesso
				header('Location: index.php');
			} else {
				echo 'query error: ' . mysqli_error($conn);
			}
		}
	}

	//fecha a conexão
	mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>

<section class="container grey-text">
	<h4 class="center">INCLUIR UM EVENTO</h4>
	<form class="white" action="novoEvento.php" method="POST">
		<select class="browser-default" name="id_show">
			<option value="" disabled selected>Escolha um show</option>
			<?php foreach ($shows as $k => $v) { ?>
				<option value="<?php echo $k = $v['id_show']; ?>"><?php echo $v['nomeShow']; ?></option>
			<?php } ?>
		</select>
		<div class="red-text"><?php echo $erros['id_show'] . '</br>'; ?></div>
		<label>Dia</label>
		<input type="date" name="dt_evento">
		<div class="red-text"><?php echo $erros['dt_evento'] . '</br>'; ?></div>
		<label>Horário</label>
		<input type="time" name="horario">
		<div class="red-text"><?php echo $erros['horario'] . '</br>'; ?></div>
		<label>Preço do Ingresso (R$)</label>
		<input type="number" name="preco" step="0.01">
		<div class="red-text"><?php echo $erros['preco'] . '</br>'; ?></div>
		<div class="center" style="margin-top: 10px;">
			<input type="submit" name="incluir" value="Incluir" class="btn brand z-depth-0 grey darken-4">
		</div>
	</form>
</section>

<?php include('templates/footer.php'); ?>

</html>