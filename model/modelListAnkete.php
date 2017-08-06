<?php

class modelListAnkete {
	protected $conn;
	protected $user;
	protected $list;
	
	public function __construct($conn, $user) {
		$this->conn = $conn;
		$this->user = $user;
		
		$sql = "SELECT ka.idanketa id, naziv  FROM korisnici_ankete ka join ankete a on ka.idanketa=a.idanketa
					WHERE ka.korisnik='$user'
					ORDER BY ka.idanketa DESC";
		$rez = $conn->query($sql);
		if($rez->num_rows > 0) {
			while($red = $rez->fetch_assoc()) {
				$this->list[$red['id']] = $red['naziv'];
			}
		}
	}
	
	public function getListAnkete() {
		if(sizeof($this->list) > 0) {
			$out = "Choose the Survey <select id=surveys>
						<option value=0></option>";
			foreach($this->list as $id=>$naziv) {
				$out .= "<option value=$id>$naziv</option>";
			}
			$out .= "</select>";
			return $out;
		} else {
			return "There is no surveys for user ".$this->user;
		}
	}
}