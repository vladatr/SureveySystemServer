<?php

class Pitanje{
	public $redni_broj;
	public $pitanje;
	public $tip;
	
	public function __construct($s) {
		$this->redni_broj = $s['redni_broj'];
		$this->pitanje = $s['pitanje'];
		$this->tip = $s['tip'];
	}


	public function prt() {
		echo $this->redni_broj.". ".$this->pitanje." ".$this->tip."<br>";
	}

}