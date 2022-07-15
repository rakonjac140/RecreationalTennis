<?php 

require '../config.php';
require '../player/DAO/PlayerDAO.php';

session_start();

error_reporting(0);

if (isset($_SESSION['user'])) {
	header("Location: ../player/index.php");
}

$action = isset($_REQUEST["action"])? $_REQUEST["action"] : "";
$dao = new DAO('config.php');

$fullname = isset($_POST['fullname']) ? $_POST['fullname'] : "";
$email = isset($_POST['fullname']) ? $_POST['fullname'] : "";
$password = isset($_POST['fullname']) ? $_POST['fullname'] : "";
$cpassword = isset($_POST['fullname']) ? $_POST['fullname'] : "";
$play = isset($_POST['fullname']) ? $_POST['fullname'] : "";
$club = isset($_POST['fullname']) ? $_POST['fullname'] : "";

if(empty($fullname))
{
	$msg = 'Enter fullname';
	header('Location: ../register.php');
} 
else if(empty($email))
{
	$msg = 'Enter email';
	header('Location: ../register.php');
} 
else if(empty($password))
{
	$msg = 'Enter password';
	header('Location: ../register.php');
}
else if(empty($cpassword))
{
	$msg = 'Enter password again';
	header('Location: ../register.php');
}

?>
