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

	<form action="config/cart.php?action=addItem" method="post">
		<input type="text" name="item_id" />
		<input type="submit" name="submit" value="Submit" />
	</form>
	
	<form action="config/cart.php?action=removeItem" method="post">
		<input type="text" name="item_id" />
		<input type="submit" name="submit" value="Submit" />
	</form>
	
	<form action="config/cart.php?action=setItemQuantity" method="post">
		<input type="text" name="item_id" />
		<input type="text" name="quantity" />
		<input type="submit" name="submit" value="Submit" />
	</form>

</body>
</html>
