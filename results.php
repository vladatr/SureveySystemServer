<?php
require_once("lock.php");

require_once("header.php");
require_once("model/modelListAnkete.php");
?>
<html>
<head>

<title>Results</title>
<script type="text/javascript" src="js/jquery-3.2.1.js"> </script>
<script type="text/javascript" src="js/Chart.js"> </script>
<link href="css/styleResult.css" rel="stylesheet" type="text/css" />

<script>
 $(document).ready(function() {
	 $(document).on("change", "#surveys", function() {
		 $("#okvirResult").html("");
		var anketa=$(this).find("option:selected").val();
		$.post("services/results/get_results.php",
			{
				"anketa": anketa
			},
			function(data, status){
				$("#okvirResult").html(data);
			});
	 });
	 
 });
 </script>

 </head>
 <body>
 <div id=okvir>
	<div id=okvirSelect>
<?php
	$ankete = new modelListAnkete($conn, $ulogovani);
	echo $ankete->getListAnkete();
	
	?>
	</div>
	<div id=okvirResult>
	
	</div>
	
	
</div>