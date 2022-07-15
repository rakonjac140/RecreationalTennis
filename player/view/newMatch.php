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
    $reservation = isset($_POST['reservation']) ? $_POST['reservation'] : "";

    $dao = new DAO("config.php");
    $reservations = $dao->selectReservations($_SESSION['user']['id']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Match</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body id="newMatchBody">
    <form class="form" action="../controllers/addMatch.php" method="post" style="height: 620px;">
        <div class="title text-center">MATCH DETAILS</div>
        <div class="subtitle">Please enter match details</div>
        <div class="input-container ic1">
            <select <?php if(count($reservations) == 0) echo 'disabled'; ?> class="input" form-select form-select-lg mb-3" name="reservation" required style="background: #303245 !important; border: none !important; color: white !important;">
                <?php foreach ($reservations as $reservation) { ?>
                    <option value="<?=$reservation['reservationDate'] . " " . $reservation['reservationTime']?>"><?=$reservation['reservationDate'] . " " . $reservation['reservationTime'] . "h"?></option>
                <?php } ?>
            </select>
            <div class="cut" style="width: 85px !important;"></div>
            <label id="select" for="reservation" class="placeholder">Reservation</label>
            <div style="color: white; width: 100%;">
                <?php if(count($reservations) == 0) echo 'Please make reservation first'; ?>
            </div>
        </div>
        <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
            <div class="input-container ic1" style="width: 30%;">
                <input id="you" class="input" required type="number" name="firstSetYou" oninput="if(this.value>7)this.value=7; if(this.value<0)this.value=0;" value="<?=$firstSetYou?>" />
                <div class="cut"></div>
                <label for="firstname" class="placeholder">you</label>
            </div>
            <div style="color: white; font-size: 40px; margin-bottom: -30px; width: 20px; padding-left: 20px;">:</div>
            <div class="input-container ic2" style="width: 30%;  margin-top: 40px; margin-left: 10px;">
                <input id="opponent" class="input" required type="number" value="<?=$firstSetOpponent?>" name="firstSetOpponent" oninput="if(this.value>7)this.value=7; if(this.value<0)this.value=0;" placeholder=" " />
                <div class="cut"></div>
                <label for="lastname" class="placeholder">Opponent</label>
            </div>
        </div>
        <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
            <div class="input-container ic1" style="width: 30%;">
                <input id="you" class="input" required type="number" value="<?=$secondSetYou?>" name="secondSetYou" oninput="if(this.value>7)this.value=7; if(this.value<0)this.value=0;"placeholder=" " />
                <div class="cut"></div>
                <label for="firstname" class="placeholder">you</label>
            </div>
            <div style="color: white; font-size: 40px; margin-bottom: -30px; width: 20px; padding-left: 20px;">:</div>
            <div class="input-container ic2" style="width: 30%;  margin-top: 40px; margin-left: 10px;">
                <input id="opponent" class="input" required type="number" value="<?=$secondSetOpponent?>" name="secondSetOpponent" oninput="if(this.value>7)this.value=7; if(this.value<0)this.value=0;" placeholder=" " />
                <div class="cut"></div>
                <label for="lastname" class="placeholder">opponent</label>
            </div>
        </div>
        <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
            <div class="input-container ic1" style="width: 30%;">
                <input id="you" class="input" required type="number" value="<?=$thirdSetYou?>" name="thirdSetYou" oninput="if(this.value>7)this.value=7; if(this.value<0)this.value=0;"placeholder=" " />
                <div class="cut"></div>
                <label for="firstname" class="placeholder">you</label>
            </div>
            <div style="color: white; font-size: 40px; margin-bottom: -30px; width: 20px; padding-left: 20px;">:</div>
            <div class="input-container ic2" style="width: 30%;  margin-top: 40px; margin-left: 10px;">
                <input id="opponent" class="input" required type="number" value="<?=$thirdSetOpponent?>" name="thirdSetOpponent" oninput="if(this.value>7)this.value=7; if(this.value<0)this.value=0;" placeholder=" " />
                <div class="cut"></div>
                <label for="lastname" class="placeholder">opponent</label>
            </div>
        </div>
        <input <?php if(count($reservations) == 0) echo 'disabled'; ?> value="ADD MATCH" type="submit" class="submit">
        <a style="color: white;"><?=$msg ?></a>
    </form>
  <script src="../script.js"></script>
</body>
</html>