<?php
require_once("Pitanje.php");
require_once("Odgovor.php");

class modelGetAnketa
{
	protected $conn;
	protected $kod;
	
	public function __construct($conn, $kod) {
		$this->conn=$conn;
		$this->kod = $kod;
	}
	
		public function getPitanja($broj_ankete) {
		//procitaj pitanja
		$sql = "SELECT redni_broj, tekst pitanje, tip FROM pitanja WHERE idanketa=$broj_ankete ORDER BY redni_broj";
		$rez = $this->conn->query($sql);
		$i=0;
		while($red=$rez->fetch_assoc()) {
			$p= new Pitanje($red);
			$data['pitanja'][$i] = $p;
			$i++;
		}
		return $data;
	}

	
	public function getOdgovori($broj_ankete) {
		//procitaj ponudjene odgovore
		$sql = "SELECT redbr_pitanja pitanje, broj_na_listi redni_broj, tekst odgovor, otvoreno, skok
				FROM ponudjeniOdgovori  WHERE idanketa=$broj_ankete 
				ORDER BY redbr_pitanja, broj_na_listi";
		$rez = $this->conn->query($sql);
		$i=0;
		while($red=$rez->fetch_assoc()) {
			$o= new Odgovor($red);
			$data['odgovori'][$i] = $o;
			$i++;
		}

		return $data;
	}
	
	
	public function getAnketaPodaci(&$broj_ankete, &$naziv_ankete) {
		$sql = "SELECT ka.idanketa, naziv FROM korisnici_ankete ka INNER JOIN ankete on ankete.idanketa=ka.idanketa 
				WHERE kod_ankete='".$this->kod."'";
		//echo $sql;
		$rez = $this->conn->query($sql);
		if( $rez->num_rows == 1) {
			$red = $rez->fetch_assoc();
			$naziv_ankete = $red['naziv'];
			$broj_ankete = $red['idanketa'];
		} 
	}

	
}