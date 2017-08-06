<?php		
/*
	Podaci stizu u json nizu. PRvi zadatak klase je da sacuva anketu, da bi nakon toga mogla da
	sacuva i pitanja i ponudjene odgovore.
*/

class modelSaveNewAnketa
{
	protected $conn;
	public $broj_ankete;
	protected $sql_pitanja;
	protected $sql_ponudjeni_odgovori;
	protected $korisnik;
	protected $kod;
	
	protected $error;
	protected $info;
	protected $status;
		/* 401 - nije uneta anketa
		402 - nisu uneta pitanja
		403 - nisu uneti ponudjeni odgovori
		200 - sve ok */
	
	
	public function __construct($conn, $naziv, $korisnik) {
		$this->conn = $conn;
		$this->korisnik = $korisnik;
		//ako korisnik ima ankete, ucini ih neaktivnim
		$sql = "UPDATE ankete SET aktivna=0 WHERE kosrisnik='$korisnik'";
		$ra = $conn->query($sql);
		
		$sql = "INSERT INTO ankete(naziv, aktivna, korisnik, vreme)
				VALUES('$naziv', 1, '$korisnik', CURRENT_TIMESTAMP)";
		$rez = $conn->query($sql);
		if($rez === TRUE) {
			$this->broj_ankete = $conn->insert_id;
			$this->postaviStatus(200, "Anketa ".$this->broj_ankete." je uneta.", "") ;
		} else {
			$this->postaviStatus(401, "Neuspelo dodavanje ankete, sql: " + $sql, $conn->error) ;
			$this->broj_ankete = 0;			
		}
		
		$this->sql_pitanja = "INSERT INTO pitanja(tekst, idanketa, redni_broj, tip, ocene, vreme) VALUES ";
		$this->sql_ponudjeni_odgovori = "INSERT INTO ponudjeniOdgovori(tekst, idanketa, redbr_pitanja, broj_na_listi, otvoreno, skok, vreme) VALUES ";
	}
	
	public function dodajPitanje($tekst, $redni_broj, $tip) {
		$this->sql_pitanja .= "('".$tekst."', ".$this->broj_ankete.", ".$redni_broj.", ".$tip.", 0, CURRENT_TIMESTAMP),";
	}
	
	public function dodajPOnudjeniOdgovor($tekst, $redni_broj, $broj_na_listi, $otvoreno) {
		$this->sql_ponudjeni_odgovori .= "('".$tekst."', ".$this->broj_ankete.", ".$redni_broj.", ".$broj_na_listi.", ".$otvoreno.", 0, CURRENT_TIMESTAMP),";
	}
	
	public function unesiPitanjaOdgovore() {
		$this->sql_pitanja = substr($this->sql_pitanja, 0, strlen($this->sql_pitanja)-1);
		if ($this->conn->query($this->sql_pitanja) === TRUE) {
			$this->postaviStatus(200, "Sacuvana pitanja", "");
		} else {
			//echo "Error 402: " . $sql . "<br>" . $conn->error;
			$this->postaviStatus(402, "err:".$this->sqlOdgovori, $conn->error);
		}
		
		$this->sql_ponudjeni_odgovori = substr($this->sql_ponudjeni_odgovori, 0, strlen($this->sql_ponudjeni_odgovori)-1);
		if ($this->conn->query($this->sql_ponudjeni_odgovori) === TRUE) {
			$this->postaviStatus(200, "Sacuvani ponudjeni odgovori", "");
		} else {
			//echo "Error 403: " . $sql . "<br>" . $conn->error;
			$this->postaviStatus(403, "err:".$this->sql_ponudjeni_odgovori, $conn->error);
		}
	}
	
	private function generateRandomString($length = 5) {
		return substr(str_shuffle(str_repeat($x='ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
	
	public function sacuvajKodAnkete() {
		while(true) {
			$kod = $this->generateRandomString(5);
			$sql = "SELECT kod_ankete FROM korisnici_ankete WHERE kod_ankete='$kod'";
			$rez = $this->conn->query($sql);
			if($rez->num_rows == 0) {
					$this->kod=$kod;
					$rezka = $this->conn->query("INSERT INTO korisnici_ankete(kod_ankete, korisnik, idanketa, aktivna) 
							VALUES('$kod', '".$this->korisnik."', ".$this->broj_ankete.", 1)");
					if($rezka === TRUE) {
						$this->postaviStatus(200, "Generisan i sacuvan kod ankete ".$kod, "");
					} else {
						$this->postaviStatus(404, "Greska u generisanju i cuvanju ankete. $kod", "");
					}
					return;
				} 
		}
	}
	
public function printStatus() {
		if($this->error == "") {
			return "Kod: ".$this->kod;
		} else {
			return "<br>".$this->status." info: ".$this->info." err: ".$this->error."<br>";
		}
		
	}
	
	private function postaviStatus($status, $info, $err) {
			$this->info = $info;
			$this->error = $err;
			$this->status = $status;
	}
	
	public function debugString() {
		return $this->broj_ankete."<br>".$this->sql_pitanja."<br>".$this->sql_ponudjeni_odgovori."<br>";
	}

	
	
}