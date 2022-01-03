<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<script>
async function update_cart(item) {
	let diff=parseInt(item.value)-parseInt(item.dataset["oldvalue"]);
	item.dataset["oldvalue"]=item.value;
	let cart = await update_item_quantity(parseInt(item.id), diff);
	let delivery_price = cart.delivery_price;
	let total_price=delivery_price;
	for(const item of cart.items) {
		total_price+=parseInt(item.price)*parseInt(item.quantity);
	}
	document.getElementById("total_price").innerHTML=((total_price/100.0).toFixed(2)+" &euro;").replace('.',',');
	document.getElementById("delivery_price").innerHTML=((delivery_price/100.0).toFixed(2)+" &euro;").replace('.',',');
}
</script>
<h1>Checkout</h1>
<form id="cart">
	<?php foreach($cart as $cart_item): ?>
	<div class="row">
		<div class="col-1">
			<img src="/image.php?id=<?= $product["id"] ?>" alt="" class="w-100"></a>
		</div>
		<div class = "col-11">
			<h2><?= $cart_item['title'] ?></h2>
			<p><label>quantity <input id="<?= $cart_item['id']?>" type="number" min=0 value="<?= $cart_item['quantity'] ?>" data-oldvalue="<?= $cart_item['quantity'] ?>" onchange="update_cart(this);" /></label> x <?= price_to_string($cart_item['price']) ?></p>
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
	<div class="col" id="total_price"><?= price_to_string($delivery_price+array_reduce(array_map(fn($product):int => $product['price']*$product['quantity'],$cart), fn($a, $b):int => $a+$b)) ?></div>
</div>
<div class="row"><button type="button" class="btn btn-primary m-2">Apple pay (fast checkout)</button></div>
<div class="row"><button type="button" class="btn btn-primary m-2">Google pay (fast checkout)</button></div>
<div class="row"><button type="button" class="btn btn-primary m-2">Paga con carta</button></div>
