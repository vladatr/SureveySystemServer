<?php
require_once("lock.php");

if(session_destroy())
{
header("Location: index.php");
}
?>