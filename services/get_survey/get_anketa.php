<?php
require_once('modelGetAnketa.php');
require_once("../../bazas.php");

if(isset($_POST['kod'])) {
	$kod = $_POST['kod'];
} else {
	
		$odg['info'] = array("error"=>"There is no code.");
		echo json_encode($odg);
		exit;
	//	$kod='LVGZO';
	
}

$anketa = new modelGetAnketa($conn, $kod);

$broj_ankete=0;
$naziv_ankete="";

$anketa->getAnketaPodaci($broj_ankete, $naziv_ankete);

if($broj_ankete==0) {
	$odg['info'] = array("error"=>"Code $kod is not in the database.");
	echo json_encode($odg);
	exit;
}

$ank['info'][0] = array("naziv_ankete"=>$naziv_ankete, "broj_ankete"=>$broj_ankete, "error"=>"");

$pitanja = $anketa->getPitanja($broj_ankete);
$odgovori = $anketa->getOdgovori($broj_ankete);

echo json_encode(array_merge($ank, $pitanja, $odgovori));

?>