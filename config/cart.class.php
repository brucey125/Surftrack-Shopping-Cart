<?php

require_once "connect.php";
session_start();

class Cart {

	public $totalPrice;
	public $htmlCart;
	public $count;
	
	private $itemQuery;
	private $itemRows;
	
	public function __construct() {
		$this->cartSetup();
	}
	
	public function hasItems() {
		return (bool) $_SESSION['cart'];
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
	
	public function displayCart() {
		if ($this->cartExists() && $this->hasItems()) {
			$this->htmlCart = '
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th>Product</th>
					<th>Price</th>
					<th>Quantity</th>
					<th></th>
			';
			
			$this->count = 1;
			
			foreach ($_SESSION['cart'] as $cart) {
				$this->htmlCart .= '
				<tr>
					<td>'.$cart['product_name'].'</td>
					<td>&pound;'.number_format($cart['product_price'], 2).'</td>
					<td>'.$cart['product_quantity'].'</td>
					<input type="hidden" name="amount_'.$this->count.'" value="'.$cart['product_price'].'" />
					<input type="hidden" name="quantity_'.$this->count.'" value="'.$cart['product_quantity'].'" />
					<input type="hidden" name="item_name_'.$this->count.'" value="'.$cart['product_name'].'" />
					<input type="hidden" name="item_number_'.$this->count.'" value="'.$cart['product_id'].'" />
				</tr>
				';
				
				$this->count++;
			}
			
			$this->htmlCart .= '
			</table>
			
			<input type="submit" name="submit" id="submit" value="Checkout with PayPal" />
			';
			
			echo $this->htmlCart;
		}
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

<!DOCTYPE html>
<html>
<head>
	<title>Shopping Cart</title>
</head>	
<body>

	<form name="pappalCheckout" action="https://www.paypal.co.uk/cgi-bin/webscr" method="post">
	
		<input type="hidden" name="business" value="brucey125@hotmail.co.uk" />
		<input type="hidden" name="cmd" value="_cart" />
		<input type="hidden" name="upload" value="1" />
		<input type="hidden" name="currency_code" value="GBP" />
		<input type="hidden" name="lc" value="UK" />
		
		<? $cart->displayCart() ?>
		
	</form>

</body>
</html>