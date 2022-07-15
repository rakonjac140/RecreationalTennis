<?php 
    require "../../config.php";
    require "../DAO/PlayerDAO.php";

    session_start();

    error_reporting(0);

    if (!isset($_SESSION['user'])) {
        header("Location: ../index.php");
    }

    $firstSetYou = isset($_POST['firstSetYou']) ? $_POST['firstSetYou'] : 0;
    $firstSetOpponent = isset($_POST['firstSetOpponent']) ? $_POST['firstSetOpponent'] : 0;
    $secondSetYou = isset($_POST['secondSetYou']) ? $_POST['secondSetYou'] : 0;
    $secondSetOpponent = isset($_POST['secondSetOpponent']) ? $_POST['secondSetOpponent'] : 0;
    $thirdSetYou = isset($_POST['thirdSetYou']) ? $_POST['thirdSetYou'] : 0;
    $thirdSetOpponent = isset($_POST['thirdSetOpponent']) ? $_POST['thirdSetOpponent'] : 0;
    $user = $_SESSION['user']['id'];
    $opponent = isset($_POST['opponent']) ? $_POST['opponent'] : 0;
    $matchId = isset($_GET['match']) ? $_GET['match'] : 0;

    $scores = calculateScore($firstSetYou, $firstSetOpponent, $secondSetYou, $secondSetOpponent, $thirdSetYou, $thirdSetOpponent);

    if ($scores[0] > $scores[1])
        $winnerID = $user['id'];
    else
        $winnerID = $opponent; 

    $dao = new DAO("config.php");
    $dao->editMatch($scores[0], $scores[1], $winnerID, $opponent, 
                    $firstSetYou, $firstSetOpponent, $secondSetYou, 
                    $secondSetOpponent, $thirdSetYou, $thirdSetOpponent, $matchId); 
    
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