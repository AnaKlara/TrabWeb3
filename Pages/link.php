<?php     
	include_once("header.php");
	if(!isset($_SESSION["token"])) {
        header("Location: index.php");
        die();
    }
?>
<div class="container">
	<div class="row">
		<div class="col l12">
			<h3 class="center-align">Link da Postagem</h3>
			<h6 class="center-align" >Forneça o link da sua postagem que você deseja avaliar automáticamente a reação dos usuários que comentaram.</h6>
			<?php
				if(isset($_SESSION["invalid_link"])) { ?>
					<h6>Insira um link válido! O link deve ser de uma postagem sua!</h6>
			<?php
				unset($_SESSION["invalid_link"]);
				}
			?>
			
			<form action="avalia.php">
				<div class="input-field c">
					<input id="link" name="link" type="text" class="validate">
					<label for="link">Link</label>
				</div>
				<button class="btn waves-effect purple darken-4" type="submit" name="action">Enviar
					<i class="material-icons right">send</i>
				</button>
			</form>
			
		</div>
	</div>
</div>
<?php
	include_once("footer.php");