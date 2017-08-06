<?php

class modelSaveAnketa
{
	private $conn;
	private $broj_ankete;
	private $broj_ispitanika;
	private $sqlOdgovori;
	private $sqlOtvoreni;
	public $error;
	public $info;
	public $status;
	private $broj_odgovora;
	private $broj_otvorenih;
	/* 401 - nije unet ispitanik
		402 - nisu uneti odgovori
		403 - nisu uneti otvoreni
		200 - sve ok */
	
	public function __construct($conn, $broj_ankete, $ip_adresa, $trajanje) {
		$this->conn = $conn;
		$this->broj_ankete = $broj_ankete;
		$this->broj_odgovora=0;
		$this->sqlOdgovori="INSERT INTO odgovori(idanketa, idpitanje, broj_na_listi, odgovor, idispitanik, vreme) VALUES ";
		$this->sqlOtvoreni="INSERT INTO otvoreni(idanketa, idpitanje, broj_na_listi, odgovor, idispitanik) VALUES ";
		//ubaci ispitanika
		$sqlIspitanik = "INSERT INTO ispitanici(idanketa, ip_adresa, trajanje, vreme) 
			VALUES ($broj_ankete, '$ip_adresa', $trajanje, CURRENT_TIMESTAMP)" ;

		if ($conn->query($sqlIspitanik) === TRUE) {
			$this->broj_ispitanika = $conn->insert_id;
			$this->postaviStatus(200, "Ispitanik ".$this->broj_ispitanika." je unet.", "") ;
		} else {
			//echo "Error 401: ".$conn->error;
			$this->postaviStatus(401, "Neuspelo dodavanje ispitanika, sql: " + $sqlIspitanik, $conn->error) ;
			$this->broj_ispitanika = 0;
		}
	}
	
	public function getBrojIspitanika() {
		return $this->broj_ispitanika;
	}
	
	private function postaviStatus($status, $info, $err) {
			$this->info = $info;
			$this->error = $err;
			$this->status = $status;
	}
	
	public function printStatus() {
		return "<br>".$this->status." info: ".$this->info." err: ".$this->error."<br>";
	}
	
	public function dodajOdgovor($red) {
		$this->sqlOdgovori .= "(".$this->broj_ankete.", ".$red->pitanje.", ".$red->odgovor.", ".$red->odgovor.
				", ".$this->broj_ispitanika.", CURRENT_TIMESTAMP),";
		$this->broj_odgovora++;
		if(strlen($red->otvoreno)>0) {
			$this->sqlOtvoreni .= "(".$this->broj_ankete.", ".$red->pitanje.", ".$red->odgovor.", '".$red->otvoreno.
				"', ".$this->broj_ispitanika."),";
		$this->broj_otvorenih++;
		}
	}
	
	public function getCeoUpit() {
		
		return $this->sqlOdgovori;
	}
	
	public function izvrsiUpitOdgovori() {
		//sacuvaj odgovore
		$this->sqlOdgovori = substr($this->sqlOdgovori, 0, strlen($this->sqlOdgovori)-1);
		if ($this->conn->query($this->sqlOdgovori) === TRUE) {
			//sacuvaj otvorene
			$this->sqlOtvoreni = substr($this->sqlOtvoreni, 0, strlen($this->sqlOtvoreni)-1);
			if ($this->conn->query($this->sqlOtvoreni) === TRUE) {
				$this->postaviStatus(200, "Sacuvani odgovori (".$this->broj_odgovora.")", "");
			} else {
				$this->postaviStatus(403, "err:".$this->sqlOtvoreni, $conn->error);
		}
			
		} else {
			$this->postaviStatus(402, "err:".$this->sqlOdgovori, $conn->error);
		}
	}
	
	
	
	
}