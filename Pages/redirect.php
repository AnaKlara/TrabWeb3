<?php
    include_once("config.php");
    include_once("header.php");

    if(!isset($_GET["code"])) { ?>
        <div class="container">
            <div class="row">
                <div class="col l12">
                    <h3 class="center-align">Ops... Algo deu errado</h3>
                    <h6 class="center-align">Você precisa autorizar o aplicativo para continuar</h6>
                </div>
            </div>
        </div>
    <?php
        die();
    }
    $code = $_GET["code"];
    $post_url = "https://api.instagram.com/oauth/access_token";
 
    $post_data = array("client_id" => $client_id, "client_secret" => $client_secret, 
        "grant_type" => "authorization_code", "redirect_uri" => $redirect_uri, "code" => $code);
    $ch = curl_init($post_url);
    
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $jsonData = curl_exec($ch);
    if(!$jsonData) { ?>
        <div class="container">
            <div class="row">
                <div class="col l12">
                    <h3 class="center-align">Ops... Algo deu errado</h3>
                    <h6 class="center-align">Redirecionando em <span id="time"></span> segundos</h6>
                </div>
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
    <?php
        die();
    }

    curl_close($ch);

    session_start();
    $data = json_decode($jsonData, true);

    $accessToken = $data['access_token'];
    $_SESSION['token'] = $accessToken;
    header("Location: avalia.php");
?>
