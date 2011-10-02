<?php

require_once("config/connect.php");

?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Surftrack Shopping Cart</title>
	
	<link type="text/css" rel="stylesheet" href="css/main.css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<script type="text/javascript" src="js/core.js"></script>
</head>

<body>

	<?php
		$products = mysql_query("SELECT * FROM products");
		while ($row = mysql_fetch_assoc($products)) {
			echo '<a href="config/cart.php?action=addItem&item_id='.$row['product_id'].'"><input type="button" id="'.$row['product_id'].'" value="Add '.$row['product_name'].'" /></a>';
			echo '<a href="config/cart.php?action=removeItem&item_id='.$row['product_id'].'"><input type="button" id="'.$row['product_id'].'" value="Remove '.$row['product_name'].'" /><br /></a>';
		}
	?>

</body>
</html>
