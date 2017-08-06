<?php

class modelListPitanja {
	protected $conn;
	protected $anketa;
	protected $list;
	
	public function __construct($conn, $anketa) {
		$this->conn = $conn;
		$this->anketa = $anketa;
		
		$sql = "SELECT redni_broj, tekst, tip, ocene FROM pitanja
					WHERE idanketa='$anketa'
					ORDER BY redni_broj ASC";
		$rez = $conn->query($sql);
		if($rez->num_rows > 0) {
			while($red = $rez->fetch_assoc()) {
				$this->list[$red['redni_broj']] = 
					array("tekst"=>$red['tekst'], 
							"tip"=>$red['tip'],
							"ocene"=>$red['ocene']);
							
			}
		}
	}
	
	public function getListPitanja() {
		if(sizeof($this->list) > 0) {
			return $this->list;
		} else {
			return "There is no questions in survey no. ".$this->anketa;
		}
	}
}