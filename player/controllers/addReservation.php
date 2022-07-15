<?php 
    require "../../config.php";
    require "../DAO/PlayerDAO.php";

    session_start();

    error_reporting(0);

    if (!isset($_SESSION['user'])) {
        header("Location: ../index.php");
    }
    
    $opponent = isset($_POST['opponent']) ? $_POST['opponent'] : "";
    $matchType = isset($_POST['matchType']) ? $_POST['matchType'] : "";
    $field = isset($_POST['field']) ? $_POST['field'] : "";
    $time = isset($_POST['time']) ? $_POST['time'] : "";
    $user = $_SESSION['user']['id'];
    $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');

    $dao = new DAO("config.php");
    if (!$dao->selectReservationByDateTimeField($date, $time, $field))
    {
        $dao->insertReservation($date, $time, $field, $user, $matchType, $opponent);
        header('Location: ../index.php');
    }
    else
    {
        $msg = "Termin je zauzet. Molimo odaberite drugi termin";
        include_once '../view/makeReservation.php';
    }
?>