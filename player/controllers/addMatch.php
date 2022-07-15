<?php 
    require "../../config.php";
    require "../DAO/PlayerDAO.php";

    session_start();

    error_reporting(0);

    if (!isset($_SESSION['user'])) {
        header("Location: ../index.php");
    }
    
    $dao = new DAO("config.php");

    $firstSetYou = isset($_POST['firstSetYou']) ? $_POST['firstSetYou'] : 0;
    $firstSetOpponent = isset($_POST['firstSetOpponent']) ? $_POST['firstSetOpponent'] : 0;
    $secondSetYou = isset($_POST['secondSetYou']) ? $_POST['secondSetYou'] : 0;
    $secondSetOpponent = isset($_POST['secondSetOpponent']) ? $_POST['secondSetOpponent'] : 0;
    $thirdSetYou = isset($_POST['thirdSetYou']) ? $_POST['thirdSetYou'] : 0;
    $thirdSetOpponent = isset($_POST['thirdSetOpponent']) ? $_POST['thirdSetOpponent'] : 0;
    $user = $_SESSION['user']['id'];
    $reservation = isset($_POST['reservation']) ? $_POST['reservation'] : "";

    $dateTime = explode(" ", $reservation,2);
    $date = explode(" ", $reservation)[0];
    $time = explode(" ", $reservation)[1];

    $opponentUser = $dao->selectOpponentIdFromReservations($date, $time);
    if ($user != $opponentUser[0]['user'])
        $opponent = $user;
    else
        $opponent = $opponentUser[0]['opponent'];

    $scores = calculateScore($firstSetYou, $firstSetOpponent, $secondSetYou, $secondSetOpponent, $thirdSetYou, $thirdSetOpponent);

    if ($scores[0] > $scores[1])
        $winnerID = $user['id'];
    else
        $winnerID = $opponent; 

    $dao->insertMatch($date, $scores[0], $scores[1], $winnerID, $user['id'], $opponent, 
    0, $firstSetYou, $firstSetOpponent, $secondSetYou, $secondSetOpponent, $thirdSetYou, $thirdSetOpponent); 
    
    $dao->updateReservationsEvidented($date, $time);

    header("Location: ../index.php");
    
    function calculateScore($firstSetYou, $firstSetOpponent, $secondSetYou, $secondSetOpponent, $thirdSetYou, $thirdSetOpponent){
        $hostScore = 0;
        $opponentScore = 0;

        if ($firstSetYou > $firstSetOpponent)
            $hostScore ++;
        else if($firstSetYou < $firstSetOpponent) 
            $opponentScore++;

        if ($secondSetYou > $secondSetOpponent)
            $hostScore ++;
        else if($secondSetYou < $secondSetOpponent) 
            $opponentScore++;
        
        if ($thirdSetYou > $thirdSetOpponent)
            $hostScore ++;
        else if($thirdSetYou < $thirdSetOpponent)  
            $opponentScore++;
        
        return array($hostScore, $opponentScore);
    }
?>