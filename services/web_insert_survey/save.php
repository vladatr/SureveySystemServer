<?php
session_start();
/*
Cuvanje ankete koja je uneta na stranici.
*/

if(isset($_POST['survey'])) {
	$obj = json_decode($_POST['survey']);
} else {
	/*
	$data = '{ "survey": { "title": "klnlknkl",  "questions": [ { "question": "qwe", "tip":"1", "answers": [{ "answer":"da", "open_input":"false" }, { "answer":"ne", "open_input":"false" }] } ,  { "question": "ert", "tip":"2", "answers": [{ "answer":"j1", "open_input":"false" }, { "answer":"j2", "open_input":"false" }, { "answer":"nd", "open_input":"true" }] } ] } }';
	$obj = json_decode($data);
	*/
	exit;
}

require_once("modelSaveNewAnketa.php");
require_once("../../bazas.php");

$naslov = $obj->survey->title;
$korisnik = $obj->survey->user;

$anketa = new modelSaveNewAnketa($conn, $naslov, $_SESSION['user']);
if($anketa->broj_ankete == 0) {
	echo $anketa->printStatus();
	exit;
}
/*
$sql = "INSERT INTO info (poruka, vreme) VALUES('p: ".($data)."', CURRENT_TIMESTAMP)";
$conn->query($sql);
*/
$redni_broj=0;
foreach($obj->survey->questions as $q) {
	$redni_broj++;
	$pitanje = $q->question;
	$tip = $q->tip;
	$anketa->dodajPitanje($pitanje, $redni_broj, $tip);
	
	$broj_na_listi=0;
	foreach($q->answers as $a) {
		$broj_na_listi++;
		$odgovor = $a->answer;
		$otvoreno = $a->open_input;
		$anketa->dodajPonudjeniOdgovor($odgovor, $redni_broj, $broj_na_listi, $otvoreno);
	}
}

//echo $anketa->debugString();

$anketa->unesiPitanjaOdgovore();
echo $anketa->sacuvajKodAnkete();
echo $anketa->printStatus();
