<?php
require_once __DIR__ . '/../config/globalconfig.php';

/**
 * check if a user is logged in and return its privilege
 */
function checkLogin(){
    $privilege = null;
    if(isset($_SESSION["user"]) && isset( $_SESSION["password"])){
        $privilege = checkUser($_SESSION["user"], $_SESSION["password"]);
    }
    return $privilege;
}

/**
 * goes to login page (index), and gets back to the current page after a successful login
 */
function doLogin(){
    $current_page = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $_SESSION["previous_page"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $login_page = getRelativePath(getcwd(), ROOT_FOLDER."/public/login");
    header("Location: ".$login_page);
    die;
}
