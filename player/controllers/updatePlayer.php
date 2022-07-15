<?php 
    require "../../config.php";
    require "../DAO/PlayerDAO.php";

    session_start();

    error_reporting(0);

    if (!isset($_SESSION['user'])) {
        header("Location: ../index.php");
    }
    
    $button = isset($_POST['button']) ? $_POST['button'] : '';
    $user = $_SESSION['user']['id'];
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $height = isset($_POST['height']) ? $_POST['height'] : '';
    $age = isset($_POST['age']) ? $_POST['age'] : '';
    $plays = isset($_POST['plays']) ? $_POST['plays'] : '';
    $club = isset($_POST['club']) ? $_POST['club'] : '';
    $birthplace = isset($_POST['birthplace']) ? $_POST['birthplace'] : '';

    if ($button == 'save')
    {
        $dao = new DAO('config.php');
        $fullname = $firstName . " " . $lastName;
        $matches = $dao->updatePlayer($fullname,$height, $age, $plays, $birthplace, $club, $user);
        $newUser = $dao->getUserById($user);
        $_SESSION['user'] = $newUser;
        header('Location: ../index.php?flag=save');
    }
    else {
        header('Location: ../index.php?flag=edit');
    }
?>