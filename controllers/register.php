<?php 

require '../config.php';
require '../player/DAO/PlayerDAO.php';

session_start();

error_reporting(0);

if (isset($_SESSION['user'])) {
	header("Location: ../player/index.php");
}

$dao = new DAO('config.php');

if (isset($_POST['submit'])) {
	$email = $_POST['email'];
	$password = md5($_POST['password']);
	
	$result = $db->getUserByEmailPassword($email, $password);

	if (count($result) > 0) {
		$_SESSION['user'] = $result[0];
		echo $result[2];
		switch ($result[0]['role']){
			case "player" : 
				header("Location: ../player/index.php");
				break;
			case "club" : 
				header("Location: ../club/index.php");
				break;
			case "admin" : 
				header("Location: ../admin/index.php");
				break;
			case "guest" : 
				header("Location: ../guest/index.php");
				break;
		}
	} else {
		echo "<script>alert('Email or Password is Wrong.')</script>";
	}
}

if($action == 'logout') {
	session_start();
	session_destroy();

	header("Location: ../index.php");
}

?>
