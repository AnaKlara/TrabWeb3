<?php
    include_once("config.php");
    //$redirect = urlencode($redirect);
    $url = "https://api.instagram.com/oauth/authorize/?client_id=".$client_id.
    "&redirect_uri=".$redirect_uri."&response_type=code";

    header("Location: ".$url);
?>