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


if(isset($_POST['for_web'])) {
	$htmlAnketa = new DrawSurvey($ank['info'][0], $pitanja, $odgovori);
	echo $htmlAnketa->draw();
} else {
	//for android return json
	echo json_encode(array_merge($ank, $pitanja, $odgovori));
}

class DrawSurvey {
	
	public function __construct($oAnketi, $pitanja, $odgovori) {
		$this->oAnketi = $oAnketi;
		$this->pitanja = $pitanja;
		$this->odgovori = $odgovori;
	}
	
	public function draw() {
		//print_r($this->pitanja);
		/*
			Anketa se crta na 3 razlicita nivoa
			1. Okvir 'anketa' - samo naziv
			2. Okvir 'grupa_pitanja' - jedno ili vise pitanja, ima vidljivu liniju okvira i dugme dalje
			3. Okvir 'pitanje' - Naziv, ponudjeni odg.
		*/
		//grupe se trenutno ne unose, zato pretpostavljamo da su sva pitanja nezavisna ... niz $grupe - sve jedinice
		//print_r($this->pitanja); exit;
		$poslednjePitanje = sizeof($this->pitanja['pitanja']); 
		
		foreach($this->pitanja['pitanja'] as $key => $pitanje) {
			$grupe[] = 1; //svako pitanje (0, 1, 2, itd.) je grupa za sebe
			/*
				da su pitanja od 2 do 4 jedna grupa:
				$grupe = [0 => 1, 1 =>3, 4=>1, itd.
			
			*/
		}
		
		$html = "<h1>Under Construction!</h1>
				<div id='anketa'>
					<span id=naslovAnkete>".$this->oAnketi['naziv_ankete']."</span>";
		$g=1;
		//GRUPE
		foreach($grupe as $pitanje => $brojPitanja) {
			$html .= "<div class='grupa' id='grupa".$g."'>";
			for($i=$pitanje; $i<$pitanje+$brojPitanja; $i++) {
				//PITANJA
				$html .= "<div id='pitanje".($i+1)."'>";
				$html .= "<span class='nazivPitanja'>".$this->pitanja['pitanja'][$i]->pitanje."</span>";
					//ODGOVORI
					//print_r($this->odgovori['odgovori'][0]);
					foreach($this->odgovori['odgovori'] as $odgovor) {
						//print_r($odgovor); echo "<hr>";
						if($odgovor->pitanje == $i+1) {	
							$html .= "<div class='odgovor' tip=".$this->pitanja['pitanja'][$i]->tip." next=".$odgovor->skok.">"
							.$odgovor->odgovor."</div>";
						}
					} 
					
					
				$html .= "</div>"; //div pitanje
			}
			if($poslednjePitanje === $i) {
				$html .= "<span class='dugme'>Save</span>";
			} else {
				$html .= "<span class='dugme'>Next</span>";
			}
			$html .= "</div>"; //grupa div 
		}
		$html .= "</div>"; //anketa div
		
		return $html;
	}
	
}


?>