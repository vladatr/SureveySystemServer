<?php

class modelListAnkete {
	protected $conn;
	protected $user;
	protected $list;
	
	public function __construct($conn, $user) {
		$this->conn = $conn;
		$this->user = $user;
		
		$sql = "SELECT ka.idanketa id, naziv, kod_ankete
				FROM korisnici_ankete ka join ankete a on ka.idanketa=a.idanketa
					WHERE ka.korisnik='$user'
					ORDER BY ka.idanketa DESC";
		$rez = $conn->query($sql);
		if($rez->num_rows > 0) {
			while($red = $rez->fetch_assoc()) {
				$this->list[$red['id']] = [$red['naziv'], $red['kod_ankete']];
			}
		}
	}
	
	public function getListAnkete($id) {
		if(sizeof($this->list) > 0) {
			$out = "Choose the Survey $id <select id='surveys".$id."' >
						<option value=0></option>";
			foreach($this->list as $id=>$anketa) {
				$out .= "<option value=$id kod=".$anketa[1].">".$anketa[0]."</option>";
			}
			$out .= "</select>";
			return $out;
		} else {
			return "There is no surveys for user ".$this->user;
		}
	}
}