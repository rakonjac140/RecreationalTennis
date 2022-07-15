<?php 

    require_once '../DAO/PlayerDAO.php';
    require_once '../../config.php';

    session_start();

    error_reporting(0);

    if (!isset($_SESSION['user'])) {
        header("Location: ../index.php");
    }

    $dao = new DAO('config.php');
    $opponents = $dao->selectOpponents($_SESSION['user']['id']);
    $fields = $dao->selectFields();
    $matchtypes = $dao->selectMatchTypes();
    $date =  date("Y-m-d");
    $msg = isset($msg) ? $msg : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Reservation</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body id="newMatchBody">
    <form id="editForm" class="form" action="../controllers/addReservation.php" method="post">
        <div class="title text-center">MAKE RESERVATION</div>
        <div class="subtitle">Please enter reservation details</div>
        <div class="input-container ic1">
        <select name="opponent" class="input" form-select form-select-lg mb-3" required aria-label=".form-select-lg example" style="background: #303245 !important; border: none !important; color: white !important;">
            <?php 
                $pom = 0;
                foreach ($opponents as $opponent) { ?>
                    <option value="<?= $opponent['fullname']?>" <?php if($pom == 0) { echo 'selected'; $pom++; } ?>><?= $opponent['fullname']?></option>
               <?php } ?>
            ?>
        </select>
        <select name="matchType" class="input" form-select form-select-lg mb-3" required aria-label=".form-select-lg example" style="margin-block: 10px !important; background: #303245 !important; border: none !important; color: white !important;">
            <?php 
                $pom = 0;
                foreach ($matchtypes as $matchtype) { ?>
                    <option value="<?= $matchtype['type']?>" <?php if($pom == 0) { echo 'selected'; $pom++; } ?>><?= $matchtype['type']?></option>
               <?php } ?>
            ?>
        </select>
        <select name="field" class="input" form-select form-select-lg mb-3" required aria-label=".form-select-lg example" style="background: #303245 !important; border: none !important; color: white !important;">
            <?php 
                $pom = 0;
                foreach ($fields as $field) { ?>
                    <option value="<?= $field['name']?>" <?php if($pom == 0) { echo 'selected'; $pom++; } ?>><?= $field['name']?></option>
               <?php } ?>
            ?>
        </select>
            <div class="cut"></div>
            <label id="select" for="select" class="placeholder">Opponent</label>
        <div class="input-container ic1">
            <input id="date" min="<?=date("Y-m-d") ?>" class="input" required name="date" type="date" value="<?= $date?>" required/>
            <div class="cut"></div>
            <label for="firstname" class="placeholder">Match date</label>
        </div>
        <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
            <div class="input-container ic1" style="color: white;">
                <input id="time" class="input" name="time" required type="number" oninput="if(this.value>24)this.value=24; if(this.value<0)this.value=0;"placeholder=" " />
                <div class="cut"></div>
                <label for="time" class="placeholder">Time [hours]</label>
                <div style="width: 100%; text-align: center; padding-top: 10px;">
                    <?= $msg ?>
                </div>
            </div>
        </div>
        <input type="submit" value="MAKE RESERVATION" class="submit" value="ADD">
    </form>
  <script src="script.js"></script>
</body>
</html>