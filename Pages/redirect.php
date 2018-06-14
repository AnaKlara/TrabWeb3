<?php
    include_once("config.php");
    $code = $_GET["code"];
    $post_url = "https://api.instagram.com/oauth/access_token";
 
    $post_data = array("client_id" => $client_id, "client_secret" => $client_secret, 
        "grant_type" => "authorization_code", "redirect_uri" => $redirect_uri, "code" => $code);
    $ch = curl_init($post_url);

    
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $jsonData = curl_exec($ch);
    if(!$jsonData) {
        echo curl_error($ch);
        die();
    }

    curl_close($ch);

    session_start();
    $data = json_decode($jsonData, true);

    $accessToken = $data['access_token'];
    $_SESSION['token'] = $accessToken;
    header("Location: avalia.php");
    die();

?>
