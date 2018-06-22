<?php 
    session_start();
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Trab Web3</title>
	<link rel="shortcut icon" href="image/instagran.png" type="image/x-icon"/> 
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link rel="stylesheet" href="CSS/materialize.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="CSS/index.css">
</head>
<body>
	<header>
		<nav>
			<div class="nav-wrapper">
				<a href="index.php" class="brand-logo">Capybaras</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li><a href="index.php">Sobre A API</a></li>
                    <?php
                        if(isset($_SESSION["token"])) { ?>
					        <li><a href="link.php">Avaliar</a></li>
					        <li><a href="logout.php">Sair</a></li>
                        <?php
                        } else { ?>
                    		<li><a href="login.php">Login</a></li>
						<?php }
                        ?>
				</ul>
			</div>
		</nav>
	</header>
	<main>