<?php 

	require './config.php';
	require './player/DAO/PlayerDAO.php';

    session_start();

    error_reporting(0);

    if (isset($_SESSION['user'])) {
        header("Location: ./index.php");
    }

    $user = $_SESSION['user'];
	$dao = new DAO('config.php');

	$plays = $dao->getPlays();
	$clubs = $dao->getClubs();

	$fullname = isset($_POST['fullname']) ? $_POST['fullname'] : "";
	$email = isset($_POST['fullname']) ? $_POST['fullname'] : "";
	$password = isset($_POST['fullname']) ? $_POST['fullname'] : "";
	$cpassword = isset($_POST['fullname']) ? $_POST['fullname'] : "";
	$play = isset($_POST['fullname']) ? $_POST['fullname'] : "";
	$club = isset($_POST['fullname']) ? $_POST['fullname'] : "";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="style.css">

	<title>Register</title>
</head>
<body>
	<div class="container">
		<form action="./controllers/register.php" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Register</p>
			<div class="input-group">
				<input type="text" placeholder="Full name" name="fullname" required>
			</div>
			<div class="input-group">
				<input type="email" placeholder="Email" name="email" required>
			</div>
			<div class="input-group">
				<input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="input-group">
				<input type="password" placeholder="Confirm Password" name="cpassword" required>
			</div>
			<select style="margin-bottom: 10px; font-size: 1rem; padding-left: 15px; width: 100%; height: 50px; border-radius: 30px; background-color: white; border: 2px solid #EBEBEB; color: black;" name="play" id="plays">
				<?php 
					foreach ($plays as $play) { ?>
						<option value="<?=$play['value']?>" <?php $playValue = $dao->getPlayByValue($play['value']); if ($playValue['value'] == $user['plays']) {echo 'selected';}?>><?=$play['value']?></option>
				<?php } ?>
			</select>
			<select style="margin-bottom: 10px; font-size: 1rem; padding-left: 15px; width: 100%; height: 50px; border-radius: 30px; background-color: white; border: 2px solid #EBEBEB; color: black;" name="club" id="clubSelect">
				<?php 
					foreach ($clubs as $club) { ?>
						<option value="<?=$club['name']?>" <?php $clubName = $dao->getClubByName($club['name']); if ($clubName['name'] == $user['club']) {echo 'selected';}?> ><?=$club['name']?></option>
				<?php } ?>    
			</select>
			<div class="input-group">
				<button name="submit" class="btn">Register</button>
			</div>
			<p class="login-register-text">Have an account? <a href="index.php">Login Here</a>.</p>
		</form>
	</div>
</body>
</html>