<?php
require_once("modelSaveAnketa.php");
require_once("../../bazas.php");

//$_POST['anketa']=8;
//$_POST['odgovori']= '[{"pitanje":1, "odgovor":1, "otovreno":""},{"pitanje":2, "odgovor":3, "otvoreno":"asd"},{"pitanje":3, "odgovor":2, "otvoreno":""},{"pitanje":4, "odgovor":1, "otvoreno":""},{"pitanje":4, "odgovor":3, "otvoreno":"qwe"}]';
if(isset($_POST['anketa'])) {
$broj_ankete=$_POST['anketa'];
} else {
	//$broj_ankete=420;
	exit;
}
if(isset($_POST['odgovori'])) {
	$odgovori = $_POST['odgovori'];
}else {
	$odgovori="nema podataka";
}

$sql = "INSERT INTO info (poruka, vreme) VALUES('p: ".$odgovori."... broj_ankete: $broj_ankete', CURRENT_TIMESTAMP)";
$conn->query($sql);


$saveAnketa = new modelSaveAnketa($conn, $broj_ankete, $_SERVER['REMOTE_ADDR'], 99);


if($saveAnketa->getBrojIspitanika()==0) {
	$odgovor['status']=$saveAnketa->status; 
	$odgovor['error'] = $saveAnketa->error;
	$odgovor['info'] = $saveAnketa->info;
	echo json_encode($odgovor);
	exit;
}

$post = json_decode($odgovori);
for($i=0; $i<sizeof($post); $i++) {
	$saveAnketa->dodajOdgovor($post[$i]);
}

 $saveAnketa->izvrsiUpitOdgovori();
 //echo $saveAnketa->printStatus();

	$odgovor['status']=$saveAnketa->status; 
	$odgovor['error'] = $saveAnketa->error;
	$odgovor['info'] = $saveAnketa->info;

	echo json_encode($odgovor);

