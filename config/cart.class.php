<?php

/**
 * Copyright 2011 Surftrack, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once "ac.connect.php";

class Cart {

	public $totalPrice;
	public $htmlCart;
	public $count;
	
	private $itemQuery;
	private $itemRows;
	
	private static $productQuery;
	
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
						$this->addItem($product_id);
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
				if ($quantity == 0) {
					unset($_SESSION['cart'][$product_id]);
				} else {
					$_SESSION['cart'][$product_id]['product_quantity'] = $quantity;
				}
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
			
			$this->count = 1;
			$this->htmlCart = '';
			
			foreach ($_SESSION['cart'] as $cart) {
				$this->htmlCart .= '
					<input type="hidden" name="item_name_'.$this->count.'" value="'.$cart['product_name'].'" />
					<input type="hidden" name="item_number_'.$this->count.'" value="'.$cart['product_id'].'" />
					<input type="hidden" name="amount_'.$this->count.'" value="'.$cart['product_price'].'" />
					<input type="hidden" name="quantity_'.$this->count.'" value="'.$cart['product_quantity'].'" />
				';
				
				$this->count++;
			}
			
			echo $this->htmlCart;
		}
	}
	
	public function emptyCart() {
		if ($this->cartExists()) {
			foreach ($_SESSION['cart'] as $cart) {
				unset($_SESSION['cart'][$cart['product_id']]);
			}
		}
	}
	
	public function destroyCart() {
		if ($this->cartExists()) {
			unset($_SESSION['cart']);
		}
	}
	
	public static function getProducts($limitStart, $limitEnd, $category) {
		self::$productQuery = mysql_query("SELECT * FROM products WHERE category_id = '{$category}' ORDER BY product_price DESC LIMIT {$limitStart}, {$limitEnd}");
		$products = '';
		
		while ($rows = mysql_fetch_assoc(self::$productQuery)) {
			$products .= '
				<div class="productsHolder clear">
					<div class="products">
						<div class="left">
							<a href="config/ac.product.php?product_id='.$rows['product_id'].'"><img src="images/product_example.png" alt="Photo" height="180" width="200" /></a>
						</div> <!-- end left -->
						<div class="right">
							<div class="productContent">
								<div class="productContentTop">
									<div class="productTitle">
										<a href="config/ac.product.php?product_id='.$rows['product_id'].'"><h1>'.$rows['product_name'].'</h1></a>
									</div> <!-- end productTitle -->
									<div class="productDescription">
										<p>'.$rows['product_description'].'</p>
									</div> <!-- end productDescription -->
								</div> <!-- end productContentTop -->
								<div class="productContentBottom">
									<div class="productOrdering">
										<h1 class="priceHolder payLeft">&pound;'.$rows['product_price'].'</h1>
										<a href="config/ac.product.php?product_id='.$rows['product_id'].'" class="product_id_'.$rows['product_id'].'"><input type="button" class="button payRight" value="More Info" /></a>
										<a href="config/ac.shop.php?action=addItem&product_id='.$rows['product_id'].'" class="product_id_'.$rows['product_id'].'"><input type="button" class="button payRight" value="Add to Cart" /></a>
									</div> <!-- end productOrdering -->
								</div> <!-- end productContentBottom -->
							</div> <!-- end productContent -->
						</div> <!-- end right -->
					</div> <!-- end products -->
				</div> <!-- end productsHolder clear -->
			';
		}
		
		echo $products;
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

?>