<?php

require_once("cart.class.php");
session_start();
$Cart = new Cart();

if (isset($_GET['action'])) {
	$action = $_GET['action'];
	switch ($action) {
		case "addItem":
			if (isset($_POST['item_id'])) {
				$id = (int) $_POST['item_id'];
				$Cart->addItem($id);
			}
		break;
		
		case "removeItem":
			if (isset($_POST['item_id'])) {
				$id = (int) $_POST['item_id'];
				$Cart->removeItem($id);
			}
		break;
		
		case "setItemQuantity":
			if (isset($_POST['item_id']) && isset($_POST['quantity'])) {
				$id = (int) $_POST['item_id'];
				$quantity = (int) $_POST['quantity'];
				$Cart->setItemQuantity($id, $quantity);
			}
		break;
	}
}

$Cart->showCartArray();

?>