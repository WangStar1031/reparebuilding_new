<?php
session_start();
$_SESSION['reparationUserName'] = "";
header('Location: login.php');
?>