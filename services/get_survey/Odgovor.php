<?php

class Odgovor{
	public $pitanje;
	public $redni_broj;
	public $odgovor;
	public $otvoreno;
	public $skok;
	
	public function __construct($s) {
		$this->pitanje = $s['pitanje'];
		$this->redni_broj = $s['redni_broj'];
		$this->odgovor = $s['odgovor'];
		$this->otvoreno = $s['otvoreno'];
		$this->skok = $s['skok'];
	}


	public function prt() {
		echo $this->pitanje.". ".$this->redni_broj." ".$this->odgovor."<br>";
	}

}