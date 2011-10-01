<?php

require_once "connect.php";
session_start();

class Cart {

	public $totalPrice;
	
	private $itemQuery;
	private $itemRows;
	
	public function __construct() {
		$this->cartSetup();
	}
	
	public function addItem($product_id) {
		if (isset($product_id)) {			
			$this->itemQuery = mysql_query("SELECT * FROM products WHERE product_id = '{$product_id}'");
			$this->itemRows = mysql_num_rows($this->itemQuery);
			
			if ($this->itemRows == 1) {
				while ($row = mysql_fetch_assoc($this->itemQuery)) {
					if ($this->cartExists()) {
						if (array_key_exists($product_id, $_SESSION['cart'])) {
							$_SESSION['cart'][$product_id]['product_quantity']++;
						} else {
							$_SESSION['cart'][$product_id]['product_id'] = $product_id;
							$_SESSION['cart'][$product_id]['product_name'] = $row['product_name'];
							$_SESSION['cart'][$product_id]['product_price'] = $row['product_price'];
							$_SESSION['cart'][$product_id]['product_quantity'] = 1;
						}
					} else {
						$this->cartSetup();
					}
				}
			}
		}
	}
	
	public function removeItem($product_id) {
		if (isset($product_id)) {
			if (array_key_exists($product_id, $_SESSION['cart'])) {
				unset($_SESSION['cart'][$product_id]);
			}
		}
	}
	
	public function setItemQuantity($product_id, $quantity) {
		if (isset($product_id) && isset($quantity)) {
			if (array_key_exists($product_id, $_SESSION['cart'])) {
				$_SESSION['cart'][$product_id]['product_quantity'] = $quantity;
			}
		}
	}
	
	public function getItemName($product_id) {
		if (isset($product_id)) {
			if (array_key_exists($product_id, $_SESSION['cart'])) {
				return $_SESSION['cart'][$product_id]['product_name'];
			}
		}
	}
	
	public function getItemPrice($product_id) {
		if (isset($product_id)) {
			if (array_key_exists($product_id, $_SESSION['cart'])) {
				return $_SESSION['cart'][$product_id]['product_price'];
			}
		}
	}
	
	public function getItemQuantity($product_id) {
		if (isset($product_id)) {
			if (array_key_exists($product_id, $_SESSION['cart'])) {
				return $_SESSION['cart'][$product_id]['product_quantity'];
			}
		}
	}
	
	public function getTotalPrice() {
		$this->totalPrice = 0;
		foreach ($_SESSION['cart'] as $cart) {
			$this->totalPrice += $cart['product_quantity'] * $cart['product_price'];
		}
		return $this->totalPrice;
	}
	
	public function showCartArray() {
		echo "<pre>";
		print_r($_SESSION['cart']);
		echo "</pre>";
	}
	
	private function cartSetup() {
		if ($this->cartExists() == false) {
			$_SESSION['cart'] = array();
		}
	}
	
	private function cartExists() {
		if (isset($_SESSION['cart'])) {
			return true;
		} else {
			return false;
		}
	}
	
}

$cart = new Cart();
$cart->showCartArray();

?>