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
    foreach($comments as $comment) {
        //echo $comment["text"]." -- ";
        $mComment = urlencode($comment['text']);

        $watsonLink = "https://c4022c0b-2bab-441d-8271-22e57bdf9dc5:05iFNJMfxPuK@gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2017-09-21&text=".$mComment;
        $watsonAPI = file_get_contents($watsonLink);
        $w = json_decode($watsonAPI, true);

        echo $comment['text']. " <br><br>";
        var_dump($w);
        echo "<br>------------------------------------<br>";
    }
?>