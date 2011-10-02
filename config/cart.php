<?php

require_once("cart.class.php");
session_start();
$Cart = new Cart();

if (isset($_GET['action'])) {
	$action = $_GET['action'];
	switch ($action) {
		case "addItem":
			if (isset($_GET['item_id'])) {
				$id = (int) $_GET['item_id'];
				$Cart->addItem($id);
			}
		break;
		
		case "removeItem":
			if (isset($_GET['item_id'])) {
				$id = (int) $_GET['item_id'];
				$Cart->removeItem($id);
			}
		break;
		
		case "setItemQuantity":
			if (isset($_GET['item_id']) && isset($_GET['quantity'])) {
				$id = (int) $_GET['item_id'];
				$quantity = (int) $_GET['quantity'];
				$Cart->setItemQuantity($id, $quantity);
			}
		break;
	}
}

$Cart->showCartArray();

?>