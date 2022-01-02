<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<h1>Checkout</h1>
<form id="cart">
	<?php foreach($cart as $cart_item): ?>
	<div class="row">
		<div class = "col-4">
			<img src="/image.php?id=<?= $product["id"] ?>" alt=""></a>
		</div>
		<div class = "col-8">
			<h2><?= $cart_item['title'] ?></h2>
			<p><label>quantity <input id="product-<?= $cart_item['id']?>-quantity" type="number" value="<?= $cart_item['quantity'] ?>" /></label> x <?= price_to_string($cart_item['price']) ?></p>
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
	<div class="col"><?= price_to_string(array_reduce(array_map(fn($product):int => $product['price']*$product['quantity'],$cart), fn($a, $b):int => $a+$b)) ?></div>
</div>
<div class="row"><button type="button" class="btn btn-primary m-2">Apple pay (fast checkout)</button></div>
<div class="row"><button type="button" class="btn btn-primary m-2">Google pay (fast checkout)</button></div>
<div class="row"><button type="button" class="btn btn-primary m-2">Paga con carta</button></div>
