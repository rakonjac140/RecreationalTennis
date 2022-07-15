<?php 
    require "../../config.php";
    require "../DAO/PlayerDAO.php";

    session_start();

    error_reporting(0);

    if (!isset($_SESSION['user'])) {
        header("Location: ../index.php");
    }

    $id = $_GET['match'];

    $dao = new DAO("config.php");
    $dao->deleteMatch($id); 
    header("Location: ../index.php");
?>