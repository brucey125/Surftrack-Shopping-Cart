<?php

require_once "connect.php";
session_start();

class Cart {

	public $cart = array();
	
	public function __construct() {
		$this->cartSetup();
	}
	
	private function cartSetup() {
		if ($this->cartExists() == false) {
			$_SESSION['cart'] = array();
			$this->cart = $_SESSION['cart'];
		}
	}
	
	private function cartExists() {
		if (isset($this->cart)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function addItem() {
	
	}
	
}

$cart = new Cart();

?>