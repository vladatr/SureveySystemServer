<?php

class modelResultPitanje {
	protected $conn;
	protected $anketa;
	protected $tekst_pitanja;
	protected $redni_broj; //pitanja
	protected $naziv;
	protected $ukupni_uzorak;
	protected $broj_odgovora;
	protected $tip;
	protected $kolone = array("No.", "Option", "Frequences", "Percents of respondents");
	protected $tekst_odgovora; //[]
	protected $uzorak; //[] - po odgovoru
	protected $frekvencije;
	protected $procenti; 
	
	public function __construct($conn, $anketa, $redni_broj, $tekst_pitanja) {
		$this->conn = $conn;
		$this->anketa = $anketa;
		$this->redni_broj = $redni_broj;
		$this->tekst_pitanja = $tekst_pitanja;
		
		//ukupni uzorak
		$sql = "SELECT distinct(idispitanik) FROM odgovori
					WHERE idanketa=$anketa AND idpitanje=$redni_broj";
		$rez = $conn->query($sql);
		$this->ukupni_uzorak = $rez->num_rows;
		
		//odgovori
		$sql = "SELECT broj_na_listi, tekst, otvoreno FROM ponudjeniOdgovori
					WHERE idanketa=$anketa AND redbr_pitanja=$redni_broj
					ORDER BY redbr_pitanja ASC";
		$rez = $conn->query($sql);
		while($red = $rez->fetch_assoc()) {
			$this->tekst_odgovora[$red['broj_na_listi']] = $red['tekst'];
			$this->frekvencije[$red['broj_na_listi']] = 0;
			$this->procenti[$red['broj_na_listi']] = 0;
		}
		
		//frekvencije
		$sql = "SELECT broj_na_listi, count(odgovor) bro FROM odgovori
					WHERE idanketa=$anketa AND idpitanje=$redni_broj
					GROUP BY broj_na_listi";
		$rez = $conn->query($sql);
		while($red = $rez->fetch_assoc()) {
			$this->frekvencije[$red['broj_na_listi']] = $red['bro'];
			$this->procenti[$red['broj_na_listi']] = round(100*$red['bro']/$this->ukupni_uzorak);
		}
		
	}
	
	
	public function printResult() {
		$out = "<div class='tekst_pitanja' >".$this->redni_broj.".".$this->tekst_pitanja."</div>
				<div class='sample'>Sample: ".$this->ukupni_uzorak."</div>";
		$out .= "<table class=tab_results>
					<tr>";
		//naslovni red - kolone
		foreach($this->kolone as $br=>$natpis) {
			$out .= "<th>".$natpis."</th>";
		}	
		$out .= "</tr>";
		//ostali redovi
		foreach($this->tekst_odgovora as $br=>$tekst) {
			$out.= "<tr><td>$br</td> <td>$tekst</td> <td>".$this->frekvencije[$br]."</td><td>".$this->procenti[$br]."</td></tr>";
		}
		$out .="</table>";
		$out .= $this->grafik($this->redni_broj);
		$out .="</div>";
		return $out;
	}
	
	
	private function grafik($br) {
		$a= '<canvas id="resultChart'.$br.'" ></canvas>
				<script>
				var ctx = document.getElementById("resultChart'.$br.'").getContext("2d");
ctx.canvas.width = 500; 
				var myChart = new Chart(ctx, {
					type: "bar",
					data: {
						labels: [';
		foreach($this->tekst_odgovora as $br=>$natpis) {
			$a .= '"'.$natpis.'"';
			if($br<sizeof($this->tekst_odgovora)) $a .=",";
		}
		$a .= "], ";
						//"Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
		$a .= 'datasets: [{';
						
		$a.="label: '#',";

		$a.="data: ["; //[12, 19, 3, 5, 2, 3],
		foreach($this->frekvencije as $br=>$frekvencija) {
			$a .= '"'.$frekvencija.'"';
			if($br<sizeof($this->frekvencije)) $a .=",";
		}		
						
						$a .= "],";
						$a .= "
							backgroundColor: [
							'rgba(75, 192, 192, 0.2)',
								'rgba(153, 102, 255, 0.2)',
								'rgba(205, 99, 132, 0.2)',
								'rgba(154, 162, 235, 0.2)',
								'rgba(255, 206, 86, 0.2)',
								'rgba(255, 159, 64, 0.2)'
							],
							borderColor: [
								'rgba(75, 192, 192, 1)',
								'rgba(153, 102, 255, 1)',
								'rgba(255,99,132,1)',
								'rgba(54, 162, 235, 1)',
								'rgba(255, 206, 86, 1)',
								'rgba(255, 159, 64, 1)'
							],
							borderWidth: 1
						}]
					},
					options: {
							responsive: true,
								   maintainAspectRatio: false,
									scales: {
										yAxes: [{
											ticks: {
												beginAtZero:true
											}
										}]
									}
						
					}
				});
		</script>";

		return $a;
	}
	
	
}