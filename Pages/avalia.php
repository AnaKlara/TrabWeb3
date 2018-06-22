<?php
	include_once("header.php");
    if(!isset($_SESSION["token"])) {
        header("Location: index.php");
        die();
    }
    if(!isset($_GET["link"])) {
		header("Location: link.php");
		die();
    }
	$aux_link = $_GET["link"];
	$link_test = preg_match("/(https:\/\/www\.instagram\.com|http:\/\/instagr.am)\/p\/\w+/", $aux_link, $link);
	if($link_test == 0) {
		$_SESSION["invalid_link"] = true;
		header("Location: link.php");
		die();
	}
	$ch_media = curl_init();
	curl_setopt($ch_media, CURLOPT_URL, "https://api.instagram.com/oembed?url=".$link[0]);
	curl_setopt($ch_media, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch_media, CURLOPT_FOLLOWLOCATION, true);
	
	$contents = curl_exec($ch_media);

	curl_close($ch_media);
	if($contents == "No Media Match") {
		$_SESSION["invalid_link"] = true;
		header("Location: link.php");
		die();
	}
	$media = json_decode($contents, true); 
	$media_id = $media['media_id'];
	$media_html = html_entity_decode($media["html"]);
	$token = $_SESSION["token"];

	$ch_comments = curl_init();
	curl_setopt($ch_comments, CURLOPT_URL, "https://api.instagram.com/v1/media/{$media_id}/comments?access_token={$token}");
	curl_setopt($ch_comments, CURLOPT_RETURNTRANSFER, 1);
	$commentsAPI = curl_exec($ch_comments);

	$aux_comments = json_decode($commentsAPI, true);
	
	if($aux_comments["meta"]["code"] == 400 && 
		$aux_comments["meta"]["error_type"] == "OAuthAccessTokenException") {
			unset($_SESSION["token"]);
			session_destroy();
		?>
		<div class="container">
            <div class="row">
                <div class="col l12">
                    <h3 class="center-align">Seu acesso expirou. Por favor, realize login novamente</h3>
                    <h6 class="center-align">Redirecionando em <span id="time"></span> segundos</h6>
                </div>
            </div>
            <script>
                var timeValue = document.getElementById("time");
                var time = 3;
                setInterval(() => {
                    time --;
                    timeValue.innerHTML = time;
                    if(time < 1) {
                        window.location = "/TrabWeb3/Pages";
                    }
                }, 1000);
            </script>
        </div>
	<?php
		die();	

	}

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
<div class="container">
	<div class="row">
		<div class=" col l2">
		</div>
		<div class=" central col l8">
			<h3 class="center-align">Avaliação da Postagem</h3>
			<h6 class="center-align"></h6>	
				<?=$media_html?>
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
<?php
	include_once("footer.php");