<?php
	session_start();
	unset($_SESSION['logged_user']);
	header('Location: ./login/index.php');
?>
