<?php 
    require "../../config.php";
    require "../DAO/PlayerDAO.php";

    session_start();

    error_reporting(0);

    if (!isset($_SESSION['user'])) {
        header("Location: ../index.php");
    }
    
    $action = isset($_GET["action"])? $_GET["action"] : "";
    $match =  isset($_GET["match"])? $_GET["match"] : -1;
    
    $dao = new DAO('sonfig.php');
    
    switch ($action) {
        case 'confirm':
            $dao->confirmMatch($match);
            header('Location: ../index.php');
            break;
        case 'decline':
            $dao->deleteMatch($match);
            header('Location: ../index.php');
            break;
        
        default:
            header('Location: ../index.php');
            break;
    }
?>