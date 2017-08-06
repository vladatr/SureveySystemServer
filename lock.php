<?php
session_start();

require_once('bazas.php');
$user_check=$_SESSION['user'];


$sql = ("select user from korisnici where user='$user_check' ");
$rez = $conn->query($sql);
$red=$rez->fetch_assoc();

$ulogovani=$red['user'];

//require_once('funkcije.php');

if(!isset($ulogovani))
{
	header("Location: login.php");
} 
?>