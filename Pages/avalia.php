<?php
	session_start();
    if(!isset($_SESSION["token"])) {
        echo "Você não tem acesso a essa área";
        die();
    }
    if(!isset($_GET["link"])) {
        header("Location: link.php");
    }
    $link = $_GET["link"];
    $contents = file_get_contents("https://api.instagram.com/oembed?url=".$link);
    $media_id = json_decode($contents, true)['media_id'];

    $token = $_SESSION["token"];
    $commentLink = "https://api.instagram.com/v1/media/{$media_id}/comments?access_token={$token}";
    $commentsAPI = file_get_contents($commentLink);
    $aux_comments = json_decode($commentsAPI, true);

    $comments = $aux_comments['data'];
	
	$response = array();
	foreach($comments as $comment) {
        //echo $comment["text"]." -- ";
        $mComment = urlencode($comment['text']);

        $watsonLink = "https://c4022c0b-2bab-441d-8271-22e57bdf9dc5:05iFNJMfxPuK@gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2017-09-21&text=".$mComment;
		$watsonAPI = file_get_contents($watsonLink);
		$w = json_decode($watsonAPI, true);
		$w["comment"] = $comment['text'];
		array_push($response, $w);
	}
?>

<!DOCTYPE html>
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
				<a href="#" class="brand-logo">Capybaras</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li><a href="sobre.html">Sobre A API</a></li>
					<li><a href="link.php">Avaliar</a></li>
				</ul>
			</div>
		</nav>
	</header>
	<main>
		<div class="container">
			<div class="row">
				<div class=" col l2">
				</div>
				<div class=" central col l8">
					<h3 class="center-align">Avaliação da Postagem</h3>
					<h6 class="center-align"></h6>	
					<blockquote class="instagram-media center-align" data-instgrm-captioned data-instgrm-permalink="<?=$link?>" data-instgrm-version="8" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);">
						<div style="padding:8px;">
							<div style=" background:#F8F8F8; line-height:0; margin-top:40px; padding:62.532299741602074% 0; text-align:center; width:100%;">
								<div style=" background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;">

								</div>
							</div> 
							<p style=" margin:8px 0 0 0; padding:0 4px;"><a href="<?=$link?>" style=" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;" target="_blank">Reativei o Instagram para fazer o trabalho da disciplina de sistemas web 3</a></p> 
							<p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">Uma publicação compartilhada por <a href="https://www.instagram.com/aclaracorrea/" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px;" target="_blank"> Ana Clara Correa Da Silva</a> (@aclaracorrea) em <time style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;" datetime="2018-06-08T01:41:10+00:00">7 de Jun, 2018 às 6:41 PDT</time></p>
						</div>
					</blockquote> 
					<h3 class="center-align">Comentários Avaliados</h3>
					<table class="centered responsive-table">
						<thead>
							<tr>
								<th>Comentário</th>
								<th>Avaliação Watson</th>
							</tr>
						</thead>

						<tbody>
						<?php
							foreach($response as $aux) { 
								?>
								<tr>
									<td><?=$aux["comment"]?></td>
									<td>
									<?php
										if(count($aux["document_tone"]["tones"]) == 0) {
											echo "Nao foi possivel avaliar";
										}
										foreach($aux["document_tone"]["tones"] as $tone) { 
										echo $tone["tone_name"]." - Pontuacao: ".$tone["score"]."<br>";		
									}
									?>
									</td>
								</tr>
						<?php
							}
						?>
						</tbody>
					</table>
				</div>
				<div class=" col l2">
				</div>
			</div>
		</div>
		<a href="link.php" class="novo-post waves-effect purple darken-4 btn"><i class="material-icons right">assignment_ind</i>Nova Postagem</a>
	</main>
	<footer>

	</footer>
	<script src="JS/jquery-3.3.1.js" type="text/javascript" charset="utf-8" async defer></script>
	<script src="JS/materialize.js"></script>
	<script src="JS/index.js"></script>
	<script async defer src="https://www.instagram.com/static/bundles/base/EmbedSDK.js/9131167473df.js"></script>
</body>	
</html>
