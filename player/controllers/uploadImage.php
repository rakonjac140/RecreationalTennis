<?php 

session_start();

error_reporting(0);

if (isset($_POST['submit']) && isset($_FILES['img'])) {
	require "../../config.php";
	require "../DAO/PlayerDAO.php";

	$img_name = $_FILES['img']['name'];
	$img_size = $_FILES['img']['size'];
	$tmp_name = $_FILES['img']['tmp_name'];
	$error = $_FILES['img']['error'];
	$type = isset($_GET['type']) ? $_GET['type'] : "";
    $id = $_SESSION['user']['id'];

	if ($error === 0) {
		if ($img_size > 600000) {
			$em = "Sorry, your file is too large.";
		    header("Location: ../index.php?error=$em");
		}else {
			$img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

			$allowed_ext = array("jpg", "jpeg", "png", "svg", "jfif"); 

			if (in_array($img_ext, $allowed_ext)) {
				$new_img_name = uniqid("IMG-", true).'.'.$img_ext;
				$img_upload_path = '../uploads/'.$new_img_name;
				$url = './uploads/'.$new_img_name;

				move_uploaded_file($tmp_name, $img_upload_path);

				// Insert into Database
				$dao = new DAO("config.php");
				$dao->insertPhoto($id, $type, $url);
				header("Location: ../index.php");
			}else {
				$em = "You can't upload files of this type";
		        header("Location: ../index.php?error=$em");
			}
		}
	}else {
		$em = "unknown error occurred!";
		header("Location: ../index.php?error=$em");
	}

}else {
	header("Location: ../index.php");
}