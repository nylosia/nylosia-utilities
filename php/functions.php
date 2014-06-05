<?php

//get current URL
//http://wp-mix.com/php-get-current-url/
function get_current_URL() {
     $current_url  = 'http';
     $server_https = $_SERVER["HTTPS"];
     $server_name  = $_SERVER["SERVER_NAME"];
     $server_port  = $_SERVER["SERVER_PORT"];
     $request_uri  = $_SERVER["REQUEST_URI"];
     if ($server_https == "on") $current_url .= "s";
     $current_url .= "://";
     if ($server_port != "80") $current_url .= $server_name . ":" . $server_port . $request_uri;
     else $current_url .= $server_name . $request_uri;
     return $current_url;
}

//ritorna id univoco
//TODO da migliorare
function get_unique_id() {
     return uniqid($_SERVER['REMOTE_ADDR'], true);
}

?>