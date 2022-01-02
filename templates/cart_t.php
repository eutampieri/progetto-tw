<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<h1>Checkout</h1>
<form id="cart">
	<?php foreach($products as $product): ?>
	<div class="row">
		<div class = "col-4">
			<img src="<?= $product['image']?>" alt="">
		</div>
		<div class = "col-8">
			<h2><?= $product['title'] ?></h2>
			<p><label>quantity <input id="product-<?= $product['id']?>-quantity" type="number" value="<?= $product['quantity'] ?>" /></label> x <?= price_to_string($product['price']) ?></p>
		</div>
	</div>
	<?php endforeach; ?>
</form>
<div class="row">
	<div class="col">Spese di spedizione</div>
	<div class="col"><?= price_to_string(500) ?></div>
</div>
<div class="row">
	<div class="col">Totale ordine</div>
	<div class="col"><?= price_to_string(array_reduce(array_map(fn($product):int => $product['price']*$product['quantity'],$products), fn($a, $b):int => $a+$b)) ?></div>
</div>
<div class="row"><button type="button" class="btn btn-primary m-2">Apple pay (fast checkout)</button></div>
<div class="row"><button type="button" class="btn btn-primary m-2">Google pay (fast checkout)</button></div>
<div class="row"><button type="button" class="btn btn-primary m-2">Paga con carta</button></div>
