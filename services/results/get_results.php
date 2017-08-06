<script type="text/javascript" src="../../js/Chart.js"> </script>
<link href="../../css/styleResult.css" rel="stylesheet" type="text/css" />
<?php
require("../../model/modelListPitanja.php");
require("../../model/modelResultPitanje.php");
require("../../bazas.php");

//$_POST['anketa']=8; //debug

if(isset($_POST['anketa'])) {
	$anketa = $_POST['anketa'];
} else {
	$odg['info'] = "Parameter is missing!";
	echo json_encode($odg);
	exit;
}

	$pitanja = new modelListPitanja($conn, $anketa);
	$listPitanja = $pitanja->getListPitanja();
	
	$out = "<div id=results>";
	foreach($listPitanja as $redbr=>$podaci) {
		//prikazi resultate za svako pitanje
		$out .= "<div id=pitanje".$redbr." class=okvirPitanje >";
		$result = new modelResultPitanje($conn, $anketa, $redbr, $podaci['tekst']);
		$out .= $result->printResult();
		$out .= "</div>";
	}
	$out .= "</div>";
	
	echo $out;
