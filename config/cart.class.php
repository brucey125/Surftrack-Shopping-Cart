<?php

require_once "connect.php";
session_start();

class Cart {

	public $cart = array();
	public $totalPrice;
	
	private $itemQuery;
	
	public function __construct() {
		$this->cartSetup();
	}
	
	public function addItem($product_id) {
		if (isset($product_id)) {			
			$this->itemQuery = mysql_query("SELECT * FROM products WHERE product_id = '{$product_id}'");
		}
	}
	
	public function removeItem($product_id) {
		if (isset($product_id)) {
			if (array_key_exists($product_id, $this->cart)) {
				unset($this->cart[$product_id]);
			}
		}
	}
	
	public function setItemQuantity($product_id, $quantity) {
		if (isset($product_id) && isset($quantity)) {
		
		}
	}
	
	public function getItemName($product_id) {
		if (isset($product_id)) {
		
		}
	}
	
	public function getItemPrice($product_id) {
		if (isset($product_id)) {
		
		}
	}
	
	public function getItemQuantity($product_id) {
		if (isset($product_id)) {
		
		}
	}
	
	public function getTotalPrice() {
		$this->totalPrice = 0;
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
	
}

$cart = new Cart();

?>