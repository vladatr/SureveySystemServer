<?php
require_once("lock.php");

require_once("header.php");
require_once("model/modelListAnkete.php");
?>
<html>
<head>

<title>Results</title>
<script type="text/javascript" src="js/jquery-3.2.1.js"> </script>
<script type="text/javascript" src="js/fje.js"> </script>
<link href="css/styleEnter.css" rel="stylesheet" type="text/css" />

 </head>
 <body>
 <div id=okvir>
	<div id=okvirSelect>
<?php
	$ankete = new modelListAnkete($conn, $ulogovani);
	echo $ankete->getListAnkete("Enter");
	
	?>
	</div>
	<div id=okvirEnter >
	
	</div>
	
	
</div>