<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<script>
function update_cart(item) {
	let total_price=parseInt(document.getElementById("total_price").innerHTML);
	let diff=parseInt(item.value)-parseInt(item.oldvalue);
	item.oldvalue=item.value;
	let price=parseFloat(item.price);
	total_price+=diff*price/100.0;
	update_item_quantity(parseInt(item.id), diff);
}
</script>
<h1>Checkout</h1>
<form id="cart">
	<?php foreach($cart as $cart_item): ?>
	<div class="row">
		<div class = "col-1 w-100">
			<img src="/image.php?id=<?= $product["id"] ?>" alt=""></a>
		</div>
		<div class = "col-11">
			<h2><?= $cart_item['title'] ?></h2>
			<p><label>quantity <input id="<?= $cart_item['id']?>" type="number" min=0 value="<?= $cart_item['quantity'] ?>" oldvalue="<?= $cart_item['quantity'] ?>" price="<?= price_to_string($cart_item['price']) ?>" onchange="update_cart(this);" /></label> x <?= price_to_string($cart_item['price']) ?></p>
		</div>
	</div>
	<?php endforeach; ?>
</form>
<div class="row">
	<div class="col">Spese di spedizione</div>
	<div class="col" id="delivery_price"><?= price_to_string($delivery_price) ?></div>
</div>
<div class="row">
	<div class="col">Totale ordine</div>
	<div class="col" id="total_price"><?= price_to_string(array_reduce(array_map(fn($product):int => $product['price']*$product['quantity'],$cart), fn($a, $b):int => $a+$b)) ?></div>
</div>
<div class="row"><button type="button" class="btn btn-primary m-2">Apple pay (fast checkout)</button></div>
<div class="row"><button type="button" class="btn btn-primary m-2">Google pay (fast checkout)</button></div>
<div class="row"><button type="button" class="btn btn-primary m-2">Paga con carta</button></div>
