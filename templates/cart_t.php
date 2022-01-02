<?php
require_once(dirname(dirname(__FILE__))."/utils.php");

$pdo = new PDO("");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$cart_id = 1;
$cart_query = $pdo->query("select * from cart where user_id is :user_id");
$product_query = $pdo->query("select * from product where id is :id");
$cart_query->bindParam(":user_id",$cart_id);
$cart_query->execute();
$cart = $cart_query->fetchAll(PDO::FETCH_ASSOC);
$delivery_price = 500;
$total_price = $delivery_price;
?>
<h1>Checkout</h1>
<form id="cart">
	<?php foreach($cart as $cart_item): ?>
	<?php
		$product_query->bindParam(":id",$cart_item["product_id"]);
		$product_query->execute();
		$product = $product_query->fetch(PDO::FETCH_ASSOC);
		$total_price += $product['price']*$cart_item['quantity'];
	?>
	<div class="row">
		<div class = "col-4">
			<img src="/image.php?id=<?= $product["id"] ?>" alt=""></a>
		</div>
		<div class = "col-8">
			<h2><?= $product['title'] ?></h2>
			<p><label>quantity <input id="product-<?= $product['id']?>-quantity" type="number" value="<?= $cart_item['quantity'] ?>" /></label> x <?= price_to_string($product['price']) ?></p>
		</div>
	</div>
	<?php endforeach; ?>
</form>
<div class="row">
	<div class="col">Spese di spedizione</div>
	<div class="col"><?= price_to_string($delivery_price) ?></div>
</div>
<div class="row">
	<div class="col">Totale ordine</div>
	<div class="col"><?= price_to_string($total_price) ?></div>
</div>
<div class="row"><button type="button" class="btn btn-primary m-2">Apple pay (fast checkout)</button></div>
<div class="row"><button type="button" class="btn btn-primary m-2">Google pay (fast checkout)</button></div>
<div class="row"><button type="button" class="btn btn-primary m-2">Paga con carta</button></div>
